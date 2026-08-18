<?php

namespace App\Repositories\Eloquent;

use App\Models\Forum;
use App\Models\ForumThread;
use App\Repositories\Contracts\ForumRepositoryInterface;

class ForumRepository implements ForumRepositoryInterface
{
    public function findById(int $id): ?Forum
    {
        return Forum::find($id);
    }

    public function threadsFor(int $forumId, int $perPage = 15)
    {
        return ForumThread::where('fid', $forumId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function paginate(int $perPage = 15)
    {
        return Forum::orderBy('weight', 'asc')
            ->paginate($perPage);
    }

    public function tree()
    {
        return Forum::orderBy('weight', 'asc')->get();
    }
}
