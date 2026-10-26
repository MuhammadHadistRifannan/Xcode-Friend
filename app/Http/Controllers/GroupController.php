<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Stream;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Video;
use App\Models\AlbumVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index()
    {
        return redirect()->route('groups.browse');
    }

    public function browse(Request $request)
    {
        $query = Group::withCount('members')->latest('updated');

        $q = trim((string) $request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $groups = $query->get();
        return view('groups.browse', compact('groups', 'q'));
    }

    public function mine()
    {
        $user = Auth::user();
        
        $createdGroups = Group::where('uid', $user->id)->withCount('members')->latest('updated')->get();
        
        $joinedGroups = Group::whereHas('members', function ($query) use ($user) {
            $query->where('uid', $user->id);
        })->where('uid', '!=', $user->id)->withCount('members')->get();

        return view('groups.mine', compact('createdGroups', 'joinedGroups'));
    }

    public function create()
    {
        if (Auth::check() && (int) Auth::user()->disabled === 1) {
            return redirect()->route('groups.browse')->with('error', 'Akun Anda masih berstatus pending verifikasi. Pembuatan grup baru hanya dapat dilakukan setelah akun disetujui penuh oleh Administrator.');
        }

        return view('groups.create');
    }

    public function store(Request $request)
    {
        if (Auth::check() && (int) Auth::user()->disabled === 1) {
            return redirect()->route('groups.browse')->with('error', 'Akun Anda masih berstatus pending verifikasi. Pembuatan grup baru hanya dapat dilakukan setelah akun disetujui penuh oleh Administrator.');
        }
        $request->validate([
            'uri' => 'required|alpha_num|unique:jcow_pages,uri',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'background' => 'nullable|image|max:10240',
        ]);

        $data = $request->only(['uri', 'name']);
        $data['description'] = $request->input('description', ''); // NOT NULL in DB
        $data['uid'] = Auth::id();
        $data['type'] = $request->has('is_private') ? 'private_group' : 'group';
        $data['updated'] = time();
        $data['views'] = 0;
        $data['users'] = 1;
        $data['logo'] = '';
        $data['style_ids'] = '';
        $data['custom_css'] = '';
        $data['background'] = '';

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('group_logos', 'public');
        }
        if ($request->hasFile('background')) {
            $data['background'] = $request->file('background')->store('group_backgrounds', 'public');
        }

        $group = Group::create($data);
        
        // Pembuat otomatis menjadi member pertama
        $group->members()->attach(Auth::id());

        return redirect()->route('groups.show', $group->id)->with('success', 'Grup berhasil dibuat.');
    }

    public function show(Group $group)
    {
        $filter = request()->query('filter');

        $group->load([
            'creator',
            'members' => function ($query) {
                $query->take(6);
            },
            'streams' => function ($query) use ($filter) {
                if ($filter === 'photo') {
                    $query->where(function($q) {
                        $q->where('type', 2)
                          ->orWhereIn('app', ['photo', 'photos'])
                          ->orWhere(function($sq) {
                              $sq->whereNotIn('app', ['events', 'blogs', 'video', 'videos', 'music'])
                                 ->where('type', '!=', 3)
                                 ->where('attachment', '!=', '')
                                 ->where('attachment', 'not like', 'youtube:%')
                                 ->where('attachment', 'not like', 'a:%');
                          });
                    });
                } elseif ($filter === 'video') {
                    $query->where(function($q) {
                        $q->where('type', 3)
                          ->orWhere('attachment', 'like', 'youtube:%')
                          ->orWhereIn('app', ['video', 'videos']);
                    });
                }
                $query->with(['user', 'comments.user', 'targetPage', 'targetWallUser']);
                $query->orderBy('created', 'desc');
            },
        ]);
        
        $isMember = $group->uid === Auth::id() || $group->members()->where('jcow_page_users.uid', Auth::id())->exists();
        $isPending = $group->type === 'private_group' && $group->pendingMembers()->where('jcow_group_members_pending.uid', Auth::id())->where('ignored', 0)->exists();
        
        $recentPhotos = \App\Models\Stream::where('wall_id', $group->id)
            ->where('attachment', '!=', '')
            ->where(function($q) {
                $q->where('type', 2)
                  ->orWhereIn('app', ['photo', 'photos'])
                  ->orWhere(function($sq) {
                      $sq->whereNotIn('app', ['events', 'blogs', 'video', 'videos', 'music'])
                         ->where('type', '!=', 3)
                         ->where('attachment', 'not like', 'youtube:%');
                  });
            })
            ->with('user')
            ->orderBy('created', 'desc')
            ->take(30)
            ->get()
            ->filter(function($stream) {
                return count($stream->photo_urls) > 0;
            })
            ->take(9);

        $recentVideos = \App\Models\Stream::where('wall_id', $group->id)
            ->where('attachment', '!=', '')
            ->where(function($q) {
                $q->where('type', 3)
                  ->orWhere('attachment', 'like', 'youtube:%')
                  ->orWhereIn('app', ['video', 'videos']);
            })
            ->with('user')
            ->orderBy('created', 'desc')
            ->take(9)
            ->get();
        
        return view('groups.show', compact('group', 'isMember', 'isPending', 'filter', 'recentPhotos', 'recentVideos'));
    }

    public function media(Group $group, $type)
    {
        $isMember = $group->uid === Auth::id() || $group->members()->where('uid', Auth::id())->exists();
        if (!$isMember && $group->type === 'private_group') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($type === 'photo') {
            $rawList = \App\Models\Stream::where('wall_id', $group->id)
                ->where('attachment', '!=', '')
                ->where(function($q) {
                    $q->where('type', 2)
                      ->orWhereIn('app', ['photo', 'photos'])
                      ->orWhere(function($sq) {
                          $sq->whereNotIn('app', ['events', 'blogs', 'video', 'videos', 'music'])
                             ->where('type', '!=', 3)
                             ->where('attachment', 'not like', 'youtube:%');
                      });
                })
                ->with('user')
                ->orderBy('created', 'desc')
                ->get()
                ->filter(function($stream) {
                    return count($stream->photo_urls) > 0;
                });

            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 12;
            $currentItems = $rawList->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $mediaList = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $rawList->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
            ]);
            $title = 'Foto Grup';
        } elseif ($type === 'video') {
            $query = \App\Models\Stream::where('wall_id', $group->id)->where('attachment', '!=', '');
            $query->where(function($q) {
                $q->where('type', 3)
                  ->orWhere('attachment', 'like', 'youtube:%')
                  ->orWhereIn('app', ['video', 'videos']);
            });
            $mediaList = $query->with('user')->orderBy('created', 'desc')->paginate(12);
            $title = 'Vidio Grup';
        } else {
            abort(404);
        }

        return view('groups.media', compact('group', 'mediaList', 'type', 'title', 'isMember'));
    }

    public function postStream(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // Hanya member & owner yang boleh posting
        $isMember = $group->members()->where('uid', Auth::id())->exists();
        if (!$isMember && $group->uid !== Auth::id()) abort(403);

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'photos' => 'nullable|array|max:10',
            'video_url' => 'nullable|string|max:255',
        ]);

        if (empty($request->message) && !$request->hasFile('photos') && empty($request->video_url)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Postingan tidak boleh kosong.'
                ], 422);
            }
            return back()->withErrors(['message' => 'Postingan tidak boleh kosong.']);
        }

        $attachment = '';
        $type = 1; // 1 = teks
        $albumId = (int) $request->input('album_id', 0);
        $videoAlbumId = (int) $request->input('video_album_id', 0);

        if ($request->hasFile('photos')) {
            if ($albumId > 0) {
                $exists = Album::where('id', $albumId)->exists();
                if (!$exists) {
                    $albumId = 0;
                }
            }
            if ($albumId === 0) {
                $defaultAlbum = Album::firstOrCreate(
                    ['gid' => Auth::id(), 'app' => 'photos', 'name' => 'Unggahan Grup ' . $group->name],
                    ['description' => 'Foto yang diunggah di grup ' . $group->name, 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $albumId = $defaultAlbum->id;
            }

            $files = $request->file('photos');
            $savedPhotos = [];
            foreach($files as $index => $file) {
                $filename = 'POST-' . Auth::id() . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $file->storeAs('posts', $filename, 'public');
                $savedPhotos[] = $filename;

                // Simpan ke jcow_story_photos agar masuk ke album
                Photo::create([
                    'sid'   => $albumId,
                    'uri'   => 'posts/' . $filename,
                    'des'   => $request->message ?? '',
                    'thumb' => 'posts/' . $filename,
                    'size'  => $file->getSize(),
                ]);
            }
            $attachment = json_encode([
                'photos' => $savedPhotos,
                'album_id' => $albumId
            ]);
            $type = 2; // 2 = gambar
        } elseif (!empty($request->video_url)) {
            if ($videoAlbumId > 0) {
                $exists = AlbumVideo::where('id', $videoAlbumId)->exists();
                if (!$exists) {
                    $videoAlbumId = 0;
                }
            }
            if ($videoAlbumId === 0) {
                $defaultAlbum = AlbumVideo::firstOrCreate(
                    ['gid' => Auth::id(), 'app' => 'video', 'name' => 'Unggahan Grup ' . $group->name],
                    ['description' => 'Video yang diunggah di grup ' . $group->name, 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $videoAlbumId = $defaultAlbum->id;
            }

            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video

            // Simpan ke jcow_stories agar masuk ke album video
            $createdVideo = Video::create([
                'title'   => $request->video_title ?? 'Video Grup',
                'content' => $request->video_desc ?? ($request->message ?? ''),
                'var1'    => $request->video_url,
                'tags'    => $request->video_tags ?? '',
                'cid'     => $videoAlbumId,
                'uid'     => Auth::id(),
                'app'     => 'video',
                'created' => time(),
                'updated' => time(),
                'views'   => 0,
                'featured'=> 0,
                'sticky'  => 0,
                'closed'  => 0,
                'digg'    => 0,
                'dugg'    => 0,
                'photos'  => 0,
                'rating'  => 0,
                'blob1'   => '',
                'thumbnail' => '',
                'lastreply' => 0,
                'lastreplyuname' => '',
                'lastreplyuid' => 0,
                'comments' => 0,
                'stream_id' => 0,
                'var2' => '',
                'var3' => '',
                'var4' => '',
                'var5' => '',
                'text1' => '',
                'text2' => '',
                'page_id' => 0,
                'page_type' => '',
            ]);

            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'video_id' => $createdVideo->id,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video
        }

        $stream = Stream::create([
            'message'    => $request->message ?? '',
            'wall_id'    => $id,
            'uid'        => Auth::id(),
            'attachment' => $attachment,
            'created'    => time(),
            'type'       => $type,
            'app'        => 'group',
            'aid'        => $id,
            'hide'       => 0,
            'likes'      => 0,
            'privacy'    => $request->input('privacy', 'public'),
        ]);

        if (isset($createdVideo) && $createdVideo) {
            $createdVideo->update(['stream_id' => $stream->id]);
        }

        $stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);
        $html = view('components.single-stream', ['stream' => $stream])->render();

        try {
            \App\Events\StreamCreated::dispatch($stream, $html);
        } catch (\Throwable $e) {
            \Log::warning('Gagal broadcast StreamCreated: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Postingan berhasil diunggah!',
                'stream_id' => $stream->id,
                'html' => $html
            ]);
        }

        return redirect()->route('groups.show', $id)->with('success', 'Postingan berhasil diunggah!');
    }

    public function destroyStream($id)
    {
        $stream = Stream::findOrFail($id);
        $group = Group::findOrFail($stream->wall_id);

        // Hanya admin grup atau pemilik post yang bisa menghapus
        if (Auth::id() !== $group->uid && Auth::id() !== $stream->uid) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus attachment file lokal jika ada
        if ($stream->attachment && !str_starts_with($stream->attachment, 'youtube:')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($stream->attachment);
        }

        $stream->delete();

        return back()->with('success', 'Postingan berhasil dihapus.');
    }


    public function edit(Group $group)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        if ((int) $group->uid !== (int) Auth::id() && !$isAdmin) abort(403);
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        if ((int) $group->uid !== (int) Auth::id() && !$isAdmin) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'background' => 'nullable|image|max:10240',
        ]);

        $data = $request->only(['name']);
        $data['description'] = $request->input('description', '');
        $data['type'] = $request->has('is_private') ? 'private_group' : 'group';
        $data['updated'] = time();

        if ($request->hasFile('logo')) {
            if ($group->logo) Storage::disk('public')->delete($group->logo);
            $data['logo'] = $request->file('logo')->store('group_logos', 'public');
        }
        if ($request->hasFile('background')) {
            if ($group->background) Storage::disk('public')->delete($group->background);
            $data['background'] = $request->file('background')->store('group_backgrounds', 'public');
        }

        $group->update($data);
        return redirect()->route('groups.show', $group->id)->with('success', 'Grup diperbarui.');
    }
    public function join(Group $group)
    {
        if ($group->type === 'private_group') {
            // Cek apakah user sudah diundang oleh admin grup
            $isInvited = \App\Models\Message::where('to_id', Auth::id())
                ->where('from_id', $group->uid)
                ->where('subject', 'Undangan Grup: ' . $group->name)
                ->exists();

            if (!$isInvited) {
                // Cek apakah sudah pending
                if (!$group->pendingMembers()->where('jcow_group_members_pending.uid', Auth::id())->exists()) {
                    $group->pendingMembers()->attach(Auth::id(), [
                        'created' => time(),
                        'ignored' => 0
                    ]);
                }
                return back()->with('success', 'Permintaan bergabung telah dikirim dan menunggu persetujuan admin.');
            }
        }

        if (!$group->members()->where('jcow_page_users.uid', Auth::id())->exists()) {
            $group->members()->attach(Auth::id());
        }
        $group->pendingMembers()->detach(Auth::id());

        return back()->with('success', 'Berhasil bergabung dengan grup.');
    }

    public function leave(Group $group)
    {
        $userId = Auth::id();
        
        // 1. Lepaskan status keanggotaan dan pending
        $group->members()->detach($userId);
        $group->pendingMembers()->detach($userId);

        // 2. Destruktif: Hapus semua legacy content post di grup tersebut
        // Asumsi page_id dan wall_id adalah penanda identitas grup untuk story/stream
        \App\Models\Story::where('uid', $userId)->where('page_id', $group->id)->delete();
        $group->streams()->where('uid', $userId)->delete();

        return redirect()->route('groups.show', $group->id)->with('success', 'Anda telah berhasil keluar dari grup.');
    }

    public function removeMember(Request $request, Group $group, $uid)
    {
        if ($group->uid !== Auth::id()) {
            return back()->with('error', 'Hanya admin yang dapat mengeluarkan anggota.');
        }

        if ($group->uid == $uid) {
            return back()->with('error', 'Admin tidak bisa dikeluarkan dari grupnya sendiri.');
        }

        // 1. Hapus keanggotaan
        $group->members()->detach($uid);
        
        // 2. Hapus request pending jika ada
        $group->pendingMembers()->detach($uid);

        // 3. Destruktif: Hapus post user di dalam grup ini
        \App\Models\Story::where('uid', $uid)->where('page_id', $group->id)->delete();
        $group->streams()->where('uid', $uid)->delete();

        // Bisa tambahkan ignored=2 di pending untuk menandai kick/blocked
        $group->pendingMembers()->syncWithoutDetaching([
            $uid => [
                'created' => time(),
                'ignored' => 2
            ]
        ]);

        return back()->with('success', 'Anggota berhasil dikeluarkan.');
    }

    public function approveRequest(Group $group, $uid)
    {
        if ($group->uid !== Auth::id()) {
            return back()->with('error', 'Hanya admin yang berhak.');
        }

        $group->pendingMembers()->detach($uid);
        
        if (!$group->members()->where('uid', $uid)->exists()) {
            $group->members()->attach($uid);
        }

        return back()->with('success', 'Permintaan bergabung disetujui.');
    }

    public function rejectRequest(Group $group, $uid)
    {
        if ($group->uid !== Auth::id()) {
            return back()->with('error', 'Hanya admin yang berhak.');
        }

        // Tandai ditolak / ignore
        $group->pendingMembers()->syncWithoutDetaching([
            $uid => ['ignored' => 1]
        ]);

        return back()->with('success', 'Permintaan bergabung ditolak.');
    }



    public function destroy(Group $group)
    {
        if ($group->uid !== Auth::id()) {
            return back()->with('error', 'Hanya pembuat grup yang berhak menghapus grup.');
        }

        // Hapus logo dan background jika ada
        if ($group->logo) Storage::disk('public')->delete($group->logo);
        if ($group->background) Storage::disk('public')->delete($group->background);

        // Cascade hapus konten
        \App\Models\Story::where('page_id', $group->id)->delete();
        $group->streams()->delete();

        // Pivot members dan pending secara otomatis bisa dibersihkan 
        // jika ada foreign key on delete cascade, atau kita detach manual:
        $group->members()->detach();
        $group->pendingMembers()->detach();

        $group->delete();

        return redirect()->route('groups.mine')->with('success', 'Grup beserta seluruh isinya berhasil dihapus permanen.');
    }

    public function members(Group $group)
    {
        $group->load('members');
        return view('groups.members', compact('group'));
    }
    public function pending(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);
        if ($group->type !== 'private_group') {
            return redirect()->route('groups.show', $group->id)->with('error', 'Menu ini hanya untuk grup privat.');
        }
        
        // Asumsikan ignored=0 adalah pending, ignored=2 adalah kick/block
        $group->load(['pendingMembers' => function($query) {
            $query->where('ignored', 0); 
        }]);
        
        return view('groups.pending', compact('group'));
    }

    public function approve(Request $request, Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);
        
        $uid = $request->input('uid');
        $action = $request->input('action'); // 'approve' or 'reject'
        
        if ($action === 'approve') {
            if (!$group->members()->where('uid', $uid)->exists()) {
                $group->members()->attach($uid);
            }
        }
        
        // Remove from pending
        DB::table('jcow_group_members_pending')
            ->where('gid', $group->id)
            ->where('uid', $uid)
            ->delete();
            
        return back()->with('success', 'Anggota berhasil ' . ($action === 'approve' ? 'disetujui.' : 'ditolak.'));
    }

    public function kickMember(Group $group, $uid)
    {
        if ($group->uid !== Auth::id()) abort(403);

        // Detach dan hapus konten (mirip logic leave)
        $group->members()->detach($uid);
        DB::table('jcow_stories')->where('uid', $uid)->where('page_id', $group->id)->delete();
        DB::table('jcow_streams')->where('uid', $uid)->where('wall_id', $group->id)->delete();

        // 3. Masukkan ke pending dengan flag ignored=2 supaya blacklist
        DB::table('jcow_group_members_pending')->updateOrInsert(
            ['gid' => $group->id, 'uid' => $uid],
            ['ignored' => 2]
        );

        return back()->with('success', 'Member di-kick dan post dihapus.');
    }
    
    public function invite(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);

        $existingMemberIds = $group->members()->pluck('jcow_accounts.id')->toArray();
        $pendingMemberIds = $group->pendingMembers()->pluck('jcow_accounts.id')->toArray();
        $excludeIds = array_merge([Auth::id()], $existingMemberIds, $pendingMemberIds);
        
        $users = \App\Models\User::whereNotIn('id', $excludeIds)->get();
        
        return view('groups.invite', compact('group', 'users'));
    }

    public function sendInvite(Request $request, Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);

        $request->validate([
            'uids' => 'required|array',
            'uids.*' => 'exists:jcow_accounts,id'
        ]);

        $notificationRepo = app(\App\Repositories\Contracts\NotificationRepositoryInterface::class);
        $currentUser = Auth::user();
        
        foreach ($request->uids as $uid) {
            $notificationRepo->create($uid, 'group_invite', [
                'user_name' => $currentUser->username ?? $currentUser->name ?? '',
                'display_name' => $currentUser->name ?? '',
                'group_id' => $group->id,
                'group_name' => $group->name
            ]);
        }
        
        return redirect()->route('groups.show', $group->id)->with('success', 'Undangan berhasil dikirim ke ' . count($request->uids) . ' pengguna.');
    }



    public function reports(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);

        $reports = \App\Models\Report::where('url', 'LIKE', '%groups/' . $group->id . '%')
            ->with('user')
            ->orderBy('created', 'desc')
            ->get();
            
        return view('groups.reports', compact('group', 'reports'));
    }

    public function reportsResolve(Group $group, $id)
    {
        if ($group->uid !== Auth::id()) abort(403);

        $report = \App\Models\Report::where('id', $id)
            ->where('url', 'LIKE', '%groups/' . $group->id . '%')
            ->firstOrFail();
            
        $report->update(['hasread' => 1]);
        
        return back()->with('success', 'Laporan telah ditandai sebagai diselesaikan.');
    }
}
