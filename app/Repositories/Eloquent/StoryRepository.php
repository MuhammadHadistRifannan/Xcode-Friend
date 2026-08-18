<?php

namespace App\Repositories\Eloquent;

use App\Models\Story;
use App\Repositories\Contracts\StoryRepositoryInterface;

class StoryRepository implements StoryRepositoryInterface
{
    public function findById(int $id): ?Story
    {
        return Story::find($id);
    }

    public function create(array $data): Story
    {
        return Story::create($data);
    }

    public function update(object $story, array $data): bool
    {
        return $story->update($data);
    }

    public function delete(object $story): bool
    {
        return $story->delete();
    }

    public function paginate(int $perPage = 15)
    {
        return Story::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function published(int $perPage = 15)
    {
        return Story::where('closed', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
