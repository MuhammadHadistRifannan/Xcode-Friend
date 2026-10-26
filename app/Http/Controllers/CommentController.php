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

        if (auth()->check() && (int) auth()->id() !== (int) $stream->uid) {
            $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
            if ($friendRepo->areBlocked(auth()->id(), (int) $stream->uid)) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Tidak dapat berkomentar pada postingan ini.'], 403);
                }
                return back()->with('error', 'Tidak dapat berkomentar pada postingan ini.');
            }
        }

        $message = $this->filterBadWords($request->message);

        $comment = Comment::create([
            'target_id' => (string) $stream->uid,
            'uid' => auth()->id(),
            'message' => $message,
            'created' => time(),
            'stream_id' => $streamId
        ]);

        $commentsCount = $stream->comments()->count();

        // Broadcast realtime ke seluruh pengguna via Reverb
        try {
            \App\Events\CommentCreated::dispatch($comment, (int) $streamId, $commentsCount);
        } catch (\Throwable $e) {
            \Log::warning('Gagal broadcast CommentCreated: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comment' => [
                    'id' => $comment->id,
                    'message' => $comment->message,
                    'user' => [
                        'id' => auth()->id(),
                        'fullname' => auth()->user()->fullname,
                        'username' => auth()->user()->username,
                        'avatar' => auth()->user()->avatar_url
                    ]
                ],
                'comments_count' => $commentsCount
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
        if ((int) auth()->id() !== (int) $comment->uid) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }

        $message = $this->filterBadWords($request->message);

        $comment->update([
            'message' => $message
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            $parsedMessage = htmlspecialchars($comment->message);
            $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);

            return response()->json([
                'status' => 'success',
                'id' => (int) $comment->id,
                'message' => $comment->message,
                'parsed_message' => $parsedMessage
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
        $isAdmin = $user && $user->isAdmin();
        $currentUserId = (int) auth()->id();
        $commentUid = (int) $comment->uid;
        $streamUid = $stream ? (int) $stream->uid : null;

        if ($currentUserId === $commentUid || ($stream && $currentUserId === $streamUid) || $isAdmin) {
            $canDelete = true;
        } elseif ($stream && $stream->app === 'group' && $stream->wall_id > 0) {
            $group = \App\Models\Group::find($stream->wall_id);
            if ($group && (int) $group->uid === $currentUserId) {
                $canDelete = true;
            }
        } elseif ($stream && in_array($stream->app, ['page', 'pages']) && $stream->wall_id > 0) {
            $page = \App\Models\Page::find($stream->wall_id);
            if ($page && (int) $page->uid === $currentUserId) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        $commentsCount = $stream ? $stream->comments()->count() : 0;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comment_id' => (int) $id,
                'stream_id' => $stream ? (int) $stream->id : null,
                'comments_count' => $commentsCount
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
