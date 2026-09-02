<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Stream;

class CommentController extends Controller
{
    public function store(Request $request, $streamId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $stream = Stream::findOrFail($streamId);

        $comment = Comment::create([
            'target_id' => (string) $stream->uid,
            'uid' => auth()->id(),
            'message' => $request->message,
            'created' => time(),
            'stream_id' => $streamId
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comment' => [
                    'id' => $comment->id,
                    'message' => $comment->message,
                    'user' => [
                        'fullname' => auth()->user()->fullname,
                        'username' => auth()->user()->username,
                        'avatar' => auth()->user()->avatar 
                            ? asset('storage/avatars/'.auth()->user()->avatar) 
                            : asset('assets/img/default.png')
                    ]
                ],
                'comments_count' => $stream->comments()->count()
            ]);
        }

        return back()->with('success_post', 'Komentar berhasil ditambahkan.');
    }
}
