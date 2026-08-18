<?php

namespace App\Repositories\Eloquent;

use App\Models\Stream;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Support\Collection;

class PostRepository implements PostRepositoryInterface
{
    public function findById(int $id): ?Stream
    {
        return Stream::find($id);
    }

    public function create(array $data): Stream
    {
        return Stream::create($data);
    }

    public function update(object $post, array $data): bool
    {
        return $post->update($data);
    }

    public function delete(object $post): bool
    {
        return $post->delete();
    }

    public function feedForUser(int $userId, int $perPage = 15)
    {
        return Stream::where('uid', $userId)
            ->orWhere('wall_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function paginate(int $perPage = 15)
    {
        return Stream::orderBy('created_at', 'desc')->paginate($perPage);
    }
}
