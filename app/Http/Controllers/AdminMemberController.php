<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('status')) {
            if ($filter === 'active') {
                $query->where(function($q) {
                    $q->where('disabled', 0)->orWhereNull('disabled');
                });
            }
            elseif ($filter === 'pending') $query->where('disabled', 1);
            elseif ($filter === 'suspended') $query->where('disabled', 2);
            elseif ($filter === 'admin') $query->where(function($q) {
                $q->where('level', 1)->orWhereIn('roles', ['admin', 'administrator', 'Administrator', '3']);
            });
        }

        $members = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total'     => User::count(),
            'active'    => User::where(function($q) {
                $q->where('disabled', 0)->orWhereNull('disabled');
            })->count(),
            'pending'   => User::where('disabled', 1)->count(),
            'suspended' => User::where('disabled', 2)->count(),
        ];

        // Load semua role dari DB untuk dropdown assign role
        $roles = DB::table('jcow_roles')->orderBy('name')->get();

        return view('admin.members.index', compact('members', 'stats', 'roles'));
    }

    public function show($id)
    {
        $member = User::findOrFail($id);

        $streamCount  = DB::table('jcow_streams')->where('uid', $id)->count();
        $commentCount = DB::table('jcow_comments')->where('uid', $id)->count();
        $reportCount  = $this->getReportedCount($member);

        $recentStreams = DB::table('jcow_streams')
            ->where('uid', $id)
            ->orderBy('created', 'desc')
            ->limit(5)
            ->get();

        $roles = DB::table('jcow_roles')->orderBy('name')->get();

        return view('admin.members.show', compact('member', 'streamCount', 'commentCount', 'reportCount', 'recentStreams', 'roles'));
    }

    /**
     * Menghitung berapa kali akun atau konten milik pengguna dilaporkan oleh orang lain.
     */
    private function getReportedCount(User $member): int
    {
        $userId = (int) $member->id;
        $username = trim($member->username ?? '');

        // 1. Ambil semua ID postingan/stream milik pengguna
        $userStreamIds = DB::table('jcow_streams')
            ->where('uid', $userId)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        // 2. Ambil semua ID komentar milik pengguna
        $userCommentIds = DB::table('jcow_comments')
            ->where('uid', $userId)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        // 3. Ambil semua ID foto milik album pengguna
        $userAlbumIds = DB::table('jcow_story_categories')
            ->where('gid', $userId)
            ->pluck('id')
            ->toArray();

        $userPhotoIds = [];
        if (!empty($userAlbumIds)) {
            $userPhotoIds = DB::table('jcow_story_photos')
                ->whereIn('sid', $userAlbumIds)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->toArray();
        }

        // 4. Ambil semua ID grup milik pengguna (jika grup dilaporkan)
        $userGroupIds = DB::table('jcow_pages')
            ->where('uid', $userId)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        // 5. Ambil semua laporan yang ada
        $reports = DB::table('jcow_reports')->select('id', 'url')->get();
        $count = 0;

        foreach ($reports as $report) {
            $url = $report->url ?? '';
            $matched = false;

            // Cek komentar
            if (preg_match('/#comment-(\d+)/', $url, $m)) {
                if (in_array((int) $m[1], $userCommentIds, true)) {
                    $matched = true;
                }
            }
            // Cek postingan via anchor #post-
            elseif (preg_match('/#post-(\d+)/', $url, $m)) {
                if (in_array((int) $m[1], $userStreamIds, true)) {
                    $matched = true;
                }
            }
            // Cek stream langsung via /stream/{id}
            elseif (preg_match('/\/stream\/(\d+)/', $url, $m)) {
                if (in_array((int) $m[1], $userStreamIds, true)) {
                    $matched = true;
                }
            }
            // Cek foto
            elseif (preg_match('/\/foto(?:s)?\/(\d+)/', $url, $m) || preg_match('/\/photo(?:s)?\/(\d+)/', $url, $m)) {
                if (in_array((int) $m[1], $userPhotoIds, true)) {
                    $matched = true;
                }
            }
            // Cek grup
            elseif (preg_match('/\/groups\/(\d+)/', $url, $m)) {
                if (in_array((int) $m[1], $userGroupIds, true)) {
                    $matched = true;
                }
            }
            // Cek anchor angka murni e.g. /beranda#1234 atau #1234
            elseif (preg_match('/#(\d+)/', $url, $m)) {
                $cid = (int) $m[1];
                if (in_array($cid, $userCommentIds, true) || in_array($cid, $userStreamIds, true)) {
                    $matched = true;
                }
            }

            // Cek jika URL adalah profil user (@username, /profile/username, dsb.)
            if (!$matched && !empty($username)) {
                if (preg_match('/(?:@|\/profile\/|p=u\/|\/u\/)' . preg_quote($username, '/') . '(?:\b|[?&#]|$)/i', $url)) {
                    $matched = true;
                } elseif (preg_match('/\/members\/' . $userId . '(?:\b|[?&#]|$)/', $url)) {
                    $matched = true;
                }
            }

            if ($matched) {
                $count++;
            }
        }

        return $count;
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'disabled' => 'required|in:0,1,2',
        ]);

        $user = User::findOrFail($id);
        $user->disabled = $request->disabled;
        $user->save();

        $statusLabel = ['0' => 'diaktifkan', '1' => 'dijadikan pending', '2' => 'disuspend'];
        $label = $statusLabel[$request->disabled] ?? 'diperbarui';

        return back()->with('success', "Status pengguna {$user->username} berhasil {$label}.");
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        $user->roles = $request->roles;
        $user->level = (strtolower($request->roles) === 'administrator') ? 1 : 0;
        $user->save();

        return back()->with('success', "Role pengguna {$user->username} berhasil diperbarui.");
    }

    public function approveMember($id)
    {
        $user = User::findOrFail($id);
        $user->disabled = 0;
        $user->save();

        return back()->with('success', "Akun pending {$user->username} berhasil disetujui dan diaktifkan.");
    }

    public function banMember($id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->disabled === 1) {
            // Jika akun pending diklik aktifkan/ban, langsung setujui dan aktifkan
            $user->disabled = 0;
            $user->save();
            return back()->with('success', "Akun pending {$user->username} berhasil disetujui dan diaktifkan.");
        }

        // Toggle only between active and suspended; pending is a separate state.
        $user->disabled = ((int) $user->disabled === 0) ? 2 : 0;
        $user->save();

        $status = ($user->disabled == 2) ? 'disuspend' : 'diaktifkan kembali';
        return back()->with('success', "Pengguna {$user->username} berhasil {$status}.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Protect: cannot delete yourself or other admins
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus akun Administrator.');
        }

        // Hapus akun secara permanen beserta seluruh postingan, foto, video, album, komentar, like, dan file fisik
        app(\App\Services\AccountDeletionService::class)->deleteAccount($user);

        return redirect()->route('admin.members')->with('success', "Pengguna {$user->username} dan seluruh data serta kontennya berhasil dihapus secara permanen.");
    }

    /**
     * Membersihkan sisa konten dan postingan orphan yang pemiliknya sudah tidak ada di database.
     */
    public function cleanupOrphans()
    {
        $result = app(\App\Services\AccountDeletionService::class)->purgeOrphans();

        return redirect()->route('admin.members')->with(
            'success',
            "Pembersihan konten selesai: {$result['streams']} postingan, {$result['stories']} video, {$result['photos']} foto, dan {$result['comments']} komentar orphan berhasil dibersihkan."
        );
    }
}
