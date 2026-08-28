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
        $data['type'] = 'group';
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
        $group->load([
            'creator',
            'members' => function ($query) {
                $query->take(5);
            },
            'streams.user', // Eager load postingan wall beserta data posternya
        ]);
        
        $isMember = $group->members()->where('uid', Auth::id())->exists();
        
        return view('groups.show', compact('group', 'isMember'));
    }

    public function postStream(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // Hanya member & owner yang boleh posting
        $isMember = $group->members()->where('uid', Auth::id())->exists();
        if (!$isMember && $group->uid !== Auth::id()) abort(403);

        $request->validate([
            'message'     => 'required_without_all:file,youtube_url|nullable|string|max:5000',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:20480',
            'youtube_url' => 'nullable|url',
        ]);

        $attachment = '';

        // Prioritas 1: Upload file gambar
        if ($request->hasFile('file')) {
            $attachment = $request->file('file')->store('streams', 'public');
        }
        // Prioritas 2: Link YouTube — simpan sebagai "youtube:VIDEO_ID"
        elseif ($request->filled('youtube_url')) {
            $url = $request->input('youtube_url');
            // Ekstrak video ID dari berbagai format URL YouTube
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/', $url, $matches);
            $videoId = $matches[1] ?? null;
            if ($videoId) {
                $attachment = 'youtube:' . $videoId;
            }
        }

        Stream::create([
            'message'    => $request->input('message', ''),
            'wall_id'    => $id,
            'uid'        => Auth::id(),
            'attachment' => $attachment,
            'created'    => time(),
            'type'       => 1,
            'app'        => 'group',
            'aid'        => $id,
            'hide'       => 0,
            'likes'      => 0,
        ]);

        return redirect()->route('groups.show', $id)->with('success', 'Postingan berhasil diunggah!');
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
}
