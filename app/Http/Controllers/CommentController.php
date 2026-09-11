<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Stream;

class CommentController extends Controller
{
    public function store(Request $request, $streamId = null)
    {
        $streamId = $streamId ?? $request->route('stream') ?? $request->route('id');

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $stream = Stream::findOrFail($streamId);
        $message = $this->filterBadWords($request->message);

        $comment = Comment::create([
            'target_id' => (string) $stream->uid,
            'uid' => auth()->id(),
            'message' => $message,
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $comment = Comment::findOrFail($id);

        // Hanya pembuat komentar yang bisa mengedit komentar
        if (auth()->id() !== $comment->uid) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }

        $message = $this->filterBadWords($request->message);

        $comment->update([
            'message' => $message
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $comment->message
            ]);
        }

        return back()->with('success', 'Komentar berhasil diedit.');
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $stream = Stream::find($comment->stream_id);
        
        $canDelete = false;
        $user = auth()->user();
        $isAdmin = $user && ($user->level == 1 || in_array(strtolower($user->roles ?? ''), ['admin', 'administrator']));

        if (auth()->id() === $comment->uid || ($stream && auth()->id() === $stream->uid) || $isAdmin) {
            $canDelete = true;
        } elseif ($stream && $stream->app === 'group' && $stream->wall_id > 0) {
            $group = \App\Models\Group::find($stream->wall_id);
            if ($group && $group->uid === auth()->id()) {
                $canDelete = true;
            }
        } elseif ($stream && $stream->app === 'pages' && $stream->wall_id > 0) {
            $page = \App\Models\Page::find($stream->wall_id);
            if ($page && $page->uid === auth()->id()) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    private function filterBadWords($message)
    {
        if (empty($message)) return $message;
        $wordsFilter = \App\Helpers\SettingHelper::get('words_filter', '');
        if ($wordsFilter) {
            $badWords = array_map('trim', explode(',', strtolower($wordsFilter)));
            foreach ($badWords as $word) {
                if (!empty($word)) {
                    $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
                    $message = preg_replace($pattern, str_repeat('*', strlen($word)), $message);
                }
            }
        }
        return $message;
    }
}
