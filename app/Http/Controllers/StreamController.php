<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:5000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'photos' => 'nullable|array|max:10', // Max 10 photos
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
            // Kita bisa juga simpan judul_video, desc_video, dll jika diperlukan nanti
            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video
            
            // Tambahkan ke My Apps (Video)
            \App\Models\Video::create([
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
        }

        Stream::create([
            'message' => $request->message ?? '',
            'uid' => auth()->id(),
            'created' => time(),
            'type' => $type,
            'app' => $request->input('app', 'feed'),
            'wall_id' => $request->input('wall_id', 0),
            'attachment' => $attachment,
            'aid' => $request->input('aid', 0),
            'hide' => 0,
            'likes' => 0
        ]);

        return back()->with('success_post', 'Status berhasil dibagikan ke jaringan!');
    }

    public function update(Request $request, $id)
    {
        $stream = Stream::findOrFail($id);
        
        if (auth()->id() !== $stream->uid) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'message' => 'nullable|string|max:5000',
        ]);

        $stream->update([
            'message' => $request->message ?? ''
        ]);

        return back()->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stream = Stream::findOrFail($id);

        $canDelete = false;
        
        if (auth()->id() === $stream->uid) {
            $canDelete = true;
        } else if ($stream->app === 'group') {
            $group = \App\Models\Group::find($stream->wall_id);
            if ($group && $group->uid === auth()->id()) {
                $canDelete = true;
            }
        } else if ($stream->app === 'page') {
            $page = \App\Models\Page::find($stream->wall_id);
            if ($page && $page->uid === auth()->id()) {
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
                    \Illuminate\Support\Facades\Storage::disk('public')->delete('posts/' . $photo);
                }
            }
        }

        $stream->delete();
        return back()->with('success', 'Postingan berhasil dihapus.');
    }
}