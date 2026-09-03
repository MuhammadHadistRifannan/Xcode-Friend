<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Stream;
use App\Models\Like;
use App\Models\Comment;
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

    public function browse()
    {
        $groups = Group::withCount('members')->latest('updated')->get();
        return view('groups.browse', compact('groups'));
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
        return view('groups.create');
    }

    public function store(Request $request)
    {
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
                    $query->where('attachment', '!=', '')
                          ->where('attachment', 'not like', 'youtube:%');
                } elseif ($filter === 'video') {
                    $query->where('attachment', 'like', 'youtube:%');
                }
                $query->with('user');
                $query->orderBy('created', 'desc');
            },
        ]);
        
        $isMember = $group->uid === Auth::id() || $group->members()->where('uid', Auth::id())->exists();
        $isPending = $group->type === 'private_group' && $group->pendingMembers()->where('uid', Auth::id())->exists();
        
        $recentPhotos = \App\Models\Stream::where('wall_id', $group->id)
            ->where('attachment', '!=', '')
            ->where('attachment', 'not like', 'youtube:%')
            ->orderBy('created', 'desc')
            ->take(9)
            ->get();

        $recentVideos = \App\Models\Stream::where('wall_id', $group->id)
            ->where('attachment', 'like', 'youtube:%')
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

        $query = \App\Models\Stream::where('wall_id', $group->id);

        if ($type === 'photo') {
            $query->where('attachment', '!=', '')
                  ->where('attachment', 'not like', 'youtube:%');
            $title = 'Foto Grup';
        } elseif ($type === 'video') {
            $query->where('attachment', 'like', 'youtube:%');
            $title = 'Vidio Grup';
        } else {
            abort(404);
        }

        $mediaList = $query->orderBy('created', 'desc')->paginate(12);

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
            return back()->withErrors(['message' => 'Postingan tidak boleh kosong.']);
        }

        $attachment = '';
        $type = 1; // 1 = teks
        $albumId = $request->input('album_id', 0);
        $videoAlbumId = $request->input('video_album_id', 0);

        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $savedPhotos = [];
            foreach($files as $index => $file) {
                $filename = 'POST-' . auth()->id() . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $file->storeAs('posts', $filename, 'public');
                $savedPhotos[] = $filename;
            }
            $attachment = json_encode([
                'photos' => $savedPhotos,
                'album_id' => $albumId
            ]);
            $type = 2; // 2 = gambar
        } elseif (!empty($request->video_url)) {
            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video
        }

        Stream::create([
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
        ]);

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

    public function likeStream($id)
    {
        $stream = Stream::findOrFail($id);
        
        $existingLike = Like::where('stream_id', $id)->where('uid', Auth::id())->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'stream_id' => $id,
                'uid' => Auth::id()
            ]);
        }

        return back();
    }

    public function commentStream(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $stream = Stream::findOrFail($id);

        Comment::create([
            'stream_id' => $id,
            'uid' => Auth::id(),
            'message' => $request->message,
            'created' => time(),
            'target_id' => ''
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $comment = Comment::findOrFail($id);

        // Hanya pembuat komentar yang bisa mengedit komentar
        if (Auth::id() !== $comment->uid) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update([
            'message' => $request->message
        ]);

        return back()->with('success', 'Komentar berhasil diedit.');
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $stream = Stream::findOrFail($comment->stream_id);
        $group = Group::findOrFail($stream->wall_id);

        // Hanya pembuat komentar atau admin grup yang bisa menghapus komentar
        if (Auth::id() !== $comment->uid && Auth::id() !== $group->uid) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function edit(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);

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
            $isInvited = DB::table('jcow_messages')
                ->where('to_id', Auth::id())
                ->where('from_id', $group->uid)
                ->where('subject', 'Undangan Grup: ' . $group->name)
                ->exists();

            if (!$isInvited) {
                // Cek apakah sudah pending
                if (!$group->pendingMembers()->where('uid', Auth::id())->exists()) {
                    DB::table('jcow_group_members_pending')->insert([
                        'uid' => Auth::id(),
                        'gid' => $group->id,
                        'created' => time(),
                        'ignored' => 0
                    ]);
                }
                return back()->with('success', 'Permintaan bergabung telah dikirim dan menunggu persetujuan admin.');
            }
        }

        if (!$group->members()->where('uid', Auth::id())->exists()) {
            $group->members()->attach(Auth::id());
        }
        return back()->with('success', 'Berhasil bergabung dengan grup.');
    }

    public function leave(Group $group)
    {
        $userId = Auth::id();
        
        // 1. Lepaskan status keanggotaan
        $group->members()->detach($userId);

        // 2. Destruktif: Hapus semua legacy content post di grup tersebut
        // Asumsi page_id dan wall_id adalah penanda identitas grup untuk story/stream
        DB::table('jcow_stories')->where('uid', $userId)->where('page_id', $group->id)->delete();
        DB::table('jcow_streams')->where('uid', $userId)->where('wall_id', $group->id)->delete();
        // Tambahkan relasi hapus jcow_story_photos jika strukturnya di-support

        return redirect()->route('groups.browse')->with('success', 'Anda telah keluar. Semua jejak konten di grup ini dihapus.');
    }

    public function members(Group $group)
    {
        $group->load('members');
        return view('groups.members', compact('group'));
    }

    public function pending(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);
        
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

        $existingMemberIds = $group->members()->pluck('users.id')->toArray();
        $pendingMemberIds = $group->pendingMembers()->pluck('users.id')->toArray();
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

        $message = "Halo! Saya mengundang Anda untuk bergabung ke grup: " . $group->name . ". Silakan klik link ini untuk melihat: " . url('groups/' . $group->id);
        
        foreach ($request->uids as $uid) {
            DB::table('jcow_messages')->insert([
                'uid' => $uid,
                'fid' => Auth::id(),
                'title' => 'Undangan Grup: ' . $group->name,
                'message' => $message,
                'hasread' => 0,
                'created' => time(),
                'replyto' => 0
            ]);
        }
        
        return redirect()->route('groups.show', $group->id)->with('success', 'Undangan berhasil dikirim ke ' . count($request->uids) . ' pengguna.');
    }

    public function destroy(Group $group)
    {
        if ($group->uid !== Auth::id()) abort(403);

        // Cascade delete members
        $group->members()->detach();
        $group->pendingMembers()->detach();

        // Bersihkan post dari seluruh tabel legacy
        DB::table('jcow_stories')->where('page_id', $group->id)->delete();
        DB::table('jcow_streams')->where('wall_id', $group->id)->delete();

        if ($group->logo) Storage::disk('public')->delete($group->logo);

        $group->delete();

        return redirect()->route('groups.mine')->with('success', 'Grup berhasil dibongkar secara permanen.');
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
