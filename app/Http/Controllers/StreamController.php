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
                $file->storeAs('posts', $filename, 'public');
                $savedPhotos[] = $filename;
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
        }

        Stream::create([
            'message' => $request->message,
            'uid' => auth()->id(),
            'created' => time(),
            'type' => $type,
            'app' => 'feed',
            'wall_id' => 0,
            'attachment' => $attachment,
            'aid' => 0,
            'hide' => 0,
            'likes' => 0
        ]);

        return back()->with('success_post', 'Status berhasil dibagikan ke jaringan!');
    }
}