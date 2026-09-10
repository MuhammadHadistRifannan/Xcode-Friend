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
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
        }

        if ($filter = $request->input('status')) {
            if ($filter === 'active') $query->where('disabled', 0);
            elseif ($filter === 'pending') $query->where('disabled', 1);
            elseif ($filter === 'suspended') $query->where('disabled', 2);
            elseif ($filter === 'admin') $query->where(function($q) {
                $q->where('level', 1)->orWhereIn('roles', ['admin', 'administrator', 'Administrator']);
            });
        }

        $members = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total'     => User::count(),
            'active'    => User::where('disabled', 0)->count(),
            'pending'   => User::where('disabled', 1)->count(),
            'suspended' => User::where('disabled', 2)->count(),
        ];

        return view('admin.members.index', compact('members', 'stats'));
    }

    public function show($id)
    {
        $member = User::findOrFail($id);

        $streamCount  = DB::table('jcow_streams')->where('uid', $id)->count();
        $commentCount = DB::table('jcow_comments')->where('uid', $id)->count();
        $reportCount  = DB::table('jcow_reports')->where('uid', $id)->count();

        $recentStreams = DB::table('jcow_streams')
            ->where('uid', $id)
            ->orderBy('created', 'desc')
            ->limit(5)
            ->get();

        return view('admin.members.show', compact('member', 'streamCount', 'commentCount', 'reportCount', 'recentStreams'));
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

    public function banMember($id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->disabled === 1) {
            return back()->with('error', 'Akun pending harus diubah melalui pilihan status.');
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
        if ($user->level == 1 || in_array(strtolower($user->roles), ['admin', 'administrator'])) {
            return back()->with('error', 'Tidak bisa menghapus akun Administrator.');
        }

        $uid = $user->id;

        // Cascade delete (mirror from xcode_source logic)
        DB::table('jcow_streams')->where('uid', $uid)->delete();
        DB::table('jcow_comments')->where('uid', $uid)->delete();
        DB::table('jcow_followers')->where('uid', $uid)->orWhere('fid', $uid)->delete();
        DB::table('jcow_friends')->where('uid', $uid)->orWhere('fid', $uid)->delete();
        DB::table('jcow_group_members')->where('uid', $uid)->delete();
        DB::table('jcow_messages')->where('from_id', $uid)->orWhere('to_id', $uid)->delete();
        DB::table('jcow_liked')->where('uid', $uid)->delete();
        DB::table('jcow_reports')->where('uid', $uid)->delete();
        DB::table('jcow_profile_comments')->where('uid', $uid)->delete();
        DB::table('jcow_friend_reqs')->where('uid', $uid)->orWhere('fid', $uid)->delete();

        $user->delete();

        return redirect()->route('admin.members')->with('success', "Pengguna {$user->username} dan semua datanya berhasil dihapus.");
    }
}
