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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video_url' => 'nullable|string|max:255',
        ]);

        if (empty($request->message) && !$request->hasFile('photo') && empty($request->video_url)) {
            return back()->withErrors(['message' => 'Postingan tidak boleh kosong.']);
        }

        $attachment = '';
        $type = 1; // 1 = teks

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'POST-' . auth()->id() . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('posts', $filename, 'public');
            $attachment = json_encode(['photo' => $filename]);
            $type = 2; // 2 = gambar
        } elseif (!empty($request->video_url)) {
            // Kita bisa juga simpan judul_video, desc_video, dll jika diperlukan nanti
            $attachment = json_encode(['video_url' => $request->video_url]);
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