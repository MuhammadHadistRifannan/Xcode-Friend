<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StreamController extends Controller
{
    public function store(Request $request)
    {
        $maxLength = (int) \App\Helpers\SettingHelper::get('miniblog_maximum', \App\Helpers\SettingHelper::get('max_miniblog_length', 5000));
        if ($maxLength <= 0) $maxLength = 5000;
        $request->validate([
            'message' => 'nullable|string|max:' . $maxLength,
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'photos' => 'nullable|array|max:10', // Max 10 photos
            'video_url' => 'nullable|string|max:255',
            'privacy' => 'nullable|in:public,friends,private',
        ]);

        if (empty($request->message) && !$request->hasFile('photos') && empty($request->video_url) && !$request->hasFile('music_file')) {
            return back()->withErrors(['message' => 'Postingan tidak boleh kosong.']);
        }

        // Cek kuota pengiriman harian untuk akun pending (Pending Member Submission Limit)
        $currentUser = Auth::user();
        if ($currentUser && (int) $currentUser->disabled === 1) {
            $pendingLimit = (int) \App\Helpers\SettingHelper::get('pending_post_limit', 5);
            if ($pendingLimit >= 0) {
                $todayStart = strtotime('today midnight');
                $todayPostsCount = DB::table('jcow_streams')
                    ->where('uid', $currentUser->id)
                    ->where('created', '>=', $todayStart)
                    ->count();

                if ($todayPostsCount >= $pendingLimit) {
                    $limitMessage = "Batas pengiriman untuk akun pending telah tercapai ({$pendingLimit} postingan per hari).";
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['error' => $limitMessage, 'message' => $limitMessage], 422);
                    }
                    return back()->withErrors(['message' => $limitMessage]);
                }
            }
        }

        $message = $request->message ?? '';
        $wordsFilter = \App\Helpers\SettingHelper::get('words_filter', '');
        if ($wordsFilter && !empty($message)) {
            $badWords = array_map('trim', explode(',', strtolower($wordsFilter)));
            foreach ($badWords as $word) {
                if (!empty($word)) {
                    $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
                    $message = preg_replace($pattern, str_repeat('*', strlen($word)), $message);
                }
            }
        }

        $attachment = '';
        $type = 1; // 1 = teks
        $albumId = $request->input('album_id', 0);
        $videoAlbumId = $request->input('video_album_id', 0);

        if ($albumId == 0 && $request->hasFile('photos')) {
            $defaultPhotoAlbum = \App\Models\Album::firstOrCreate(
                ['gid' => auth()->id(), 'app' => 'photos', 'name' => 'Timeline Photos'],
                ['description' => 'Foto dari postingan timeline', 'weight' => 0]
            );
            $albumId = $defaultPhotoAlbum->id;
        }

        if ($videoAlbumId == 0 && !empty($request->video_url)) {
            $defaultVideoAlbum = \App\Models\Album::firstOrCreate(
                ['gid' => auth()->id(), 'app' => 'video', 'name' => 'Timeline Videos'],
                ['description' => 'Video dari postingan timeline', 'weight' => 0]
            );
            $videoAlbumId = $defaultVideoAlbum->id;
        }

        if ($request->hasFile('photos')) {
            if ($albumId == 0) {
                $defaultAlbum = \App\Models\Album::firstOrCreate(
                    ['gid' => auth()->id(), 'app' => 'photos', 'name' => 'Unggahan Beranda'],
                    ['description' => 'Foto yang diunggah langsung dari beranda', 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $albumId = $defaultAlbum->id;
            }
            $files = $request->file('photos');
            $savedPhotos = [];
            foreach($files as $index => $file) {
                $filename = 'POST-' . auth()->id() . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posts', $filename, 'public');
                $savedPhotos[] = $filename;
                
                // Tambahkan ke My Apps (Foto)
                \App\Models\Photo::create([
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
            if ($videoAlbumId == 0) {
                $defaultAlbum = \App\Models\Album::firstOrCreate(
                    ['gid' => auth()->id(), 'app' => 'video', 'name' => 'Unggahan Beranda'],
                    ['description' => 'Video yang diunggah langsung dari beranda', 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $videoAlbumId = $defaultAlbum->id;
            }
            // Kita bisa juga simpan judul_video, desc_video, dll jika diperlukan nanti
            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video
            
            // Tambahkan ke My Apps (Video)
            $createdVideo = \App\Models\Video::create([
                'title'   => $request->video_title ?? 'Video Post',
                'content' => $request->video_desc ?? ($request->message ?? ''),
                'var1'    => $request->video_url,
                'tags'    => $request->video_tags ?? '',
                'cid'     => $videoAlbumId,
                'uid'     => auth()->id(),
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

        $wallId = (int) $request->input('wall_id', 0);
        $app = $request->input('app', 'feed');

        if ($wallId > 0 && $wallId !== (int) auth()->id() && $app === 'feed') {
            $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
            if ($friendRepo->areBlocked(auth()->id(), $wallId)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak dapat memposting di dinding pengguna ini.'], 403);
            }
        }

        $stream = Stream::create([
            'message' => $message,
            'uid' => auth()->id(),
            'created' => time(),
            'type' => $type,
            'app' => $app,
            'wall_id' => $wallId,
            'attachment' => $attachment,
            'aid' => $request->input('aid', 0),
            'hide' => 0,
            'likes' => 0,
            'privacy' => $request->input('privacy', 'public')
        ]);

        if (isset($createdVideo) && $createdVideo) {
            $createdVideo->update(['stream_id' => $stream->id]);
        }

        $stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);
        $html = view('components.single-stream', ['stream' => $stream])->render();

        // Broadcast realtime ke seluruh user yang membuka beranda
        try {
            \App\Events\StreamCreated::dispatch($stream, $html);
        } catch (\Throwable $e) {
            \Log::warning('Gagal broadcast StreamCreated: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil dibagikan ke jaringan!',
                'stream_id' => $stream->id,
                'html' => $html
            ]);
        }

        return back()->with('success_post', 'Status berhasil dibagikan ke jaringan!');
    }

    public function update(Request $request, $id)
    {
        $stream = Stream::findOrFail($id);
        
        if ((int) auth()->id() !== (int) $stream->uid) {
            abort(403, 'Unauthorized action.');
        }

        $maxLength = (int) \App\Helpers\SettingHelper::get('miniblog_maximum', \App\Helpers\SettingHelper::get('max_miniblog_length', 5000));
        if ($maxLength <= 0) $maxLength = 5000;
        $request->validate([
            'message' => 'nullable|string|max:' . $maxLength,
        ]);

        $message = $request->message ?? '';
        $wordsFilter = \App\Helpers\SettingHelper::get('words_filter', '');
        if ($wordsFilter && !empty($message)) {
            $badWords = array_map('trim', explode(',', strtolower($wordsFilter)));
            foreach ($badWords as $word) {
                if (!empty($word)) {
                    $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
                    $message = preg_replace($pattern, str_repeat('*', strlen($word)), $message);
                }
            }
        }

        $stream->update([
            'message' => $message
        ]);

        return back()->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stream = Stream::findOrFail($id);

        $canDelete = false;
        
        $user = auth()->user();
        $isAdmin = $user && $user->isAdmin();

        if ((int) auth()->id() === (int) $stream->uid || $isAdmin) {
            $canDelete = true;
        } else if ($stream->app === 'group') {
            $group = \App\Models\Group::find($stream->wall_id);
            if ($group && (int) $group->uid === (int) auth()->id()) {
                $canDelete = true;
            }
        } else if ($stream->app === 'page') {
            $page = \App\Models\Page::find($stream->wall_id);
            if ($page && (int) $page->uid === (int) auth()->id()) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            abort(403, 'Unauthorized action.');
        }

        if ($stream->attachment) {
            $att = json_decode($stream->attachment, true);
            if (isset($att['photos']) && is_array($att['photos'])) {
                foreach ($att['photos'] as $photo) {
                    $cleanPhoto = ltrim(trim($photo), '/');
                    $filename = basename($cleanPhoto);

                    $pathsToDelete = [
                        $cleanPhoto,
                        'posts/' . $cleanPhoto,
                        'photos/' . $cleanPhoto,
                        'posts/' . $filename,
                        'photos/' . $filename,
                        str_replace(['storage/', 'uploads/'], '', $cleanPhoto),
                    ];

                    foreach (array_unique($pathsToDelete) as $p) {
                        if (!empty($p) && \Illuminate\Support\Facades\Storage::disk('public')->exists($p)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($p);
                        }
                    }

                    // Hapus record Photo dari jcow_story_photos agar tidak meninggalkan jejak di album
                    \App\Models\Photo::where(function($q) use ($photo, $cleanPhoto, $filename) {
                        $q->where('uri', $photo)
                          ->orWhere('uri', $cleanPhoto)
                          ->orWhere('uri', 'posts/' . $filename)
                          ->orWhere('uri', 'photos/' . $filename)
                          ->orWhere('uri', 'LIKE', '%' . $filename)
                          ->orWhere('thumb', $photo)
                          ->orWhere('thumb', $cleanPhoto)
                          ->orWhere('thumb', 'posts/' . $filename)
                          ->orWhere('thumb', 'photos/' . $filename)
                          ->orWhere('thumb', 'LIKE', '%' . $filename);
                    })->delete();
                }
            }
        }

        $stream->delete();
        return back()->with('success', 'Postingan berhasil dihapus.');
    }

    /**
     * Menampilkan halaman detail satu postingan (single stream).
     */
    public function show($id)
    {
        $stream = Stream::with([
            'user',
            'comments.user',
            'targetPage',
            'targetWallUser'
        ])->findOrFail($id);

        $currentUser = auth()->user();
        $isAdmin = $currentUser && $currentUser->isAdmin();

        // Pengecekan izin akses jika bukan admin dan bukan pemilik postingan
        if (!$isAdmin && (!$currentUser || $currentUser->id !== $stream->uid)) {
            if ($currentUser) {
                $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
                if ($friendRepo->areBlocked($currentUser->id, $stream->uid)) {
                    abort(404, 'Postingan tidak ditemukan atau telah dihapus.');
                }
            }

            // 1. Postingan tersembunyi
            if ($stream->hide == 1) {
                abort(404, 'Postingan tidak ditemukan atau telah disembunyikan.');
            }

            // 2. Postingan private
            if ($stream->privacy === 'private') {
                abort(403, 'Postingan ini bersifat pribadi.');
            }

            // 3. Postingan hanya untuk teman
            if ($stream->privacy === 'friends') {
                if (!$currentUser) {
                    return redirect()->guest(route('login'))->with('error', 'Silakan masuk untuk melihat postingan ini.');
                }
                $isFriend = DB::table('jcow_friends')
                    ->where(function ($q) use ($currentUser, $stream) {
                        $q->where('uid', $currentUser->id)->where('fid', $stream->uid);
                    })
                    ->orWhere(function ($q) use ($currentUser, $stream) {
                        $q->where('uid', $stream->uid)->where('fid', $currentUser->id);
                    })
                    ->exists();
                if (!$isFriend) {
                    abort(403, 'Postingan ini hanya dibagikan kepada teman.');
                }
            }

            // 4. Postingan pada grup privat
            if ($stream->app === 'group' && $stream->wall_id) {
                $group = \App\Models\Group::find($stream->wall_id);
                if ($group && $group->type === 'private_group') {
                    if (!$currentUser) {
                        return redirect()->guest(route('login'))->with('error', 'Silakan masuk untuk melihat postingan pada grup ini.');
                    }
                    $isMember = ($group->uid === $currentUser->id) || $group->members()->where('uid', $currentUser->id)->exists();
                    if (!$isMember) {
                        abort(403, 'Postingan ini berada di grup privat.');
                    }
                }
            }
        }

        return view('stream.show', compact('stream'));
    }
}