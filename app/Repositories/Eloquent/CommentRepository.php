<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    public function findById(int $id): ?Comment
    {
        return Comment::find($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function delete(object $comment): bool
    {
        return $comment->delete();
    }

    public function forStream(int $streamId, int $perPage = 15)
    {
        return Comment::where('stream_id', $streamId)
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function forUser(int $userId, int $perPage = 15)
    {
        return Comment::where('uid', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
