<?php

namespace App\Repositories\Eloquent;

use App\Models\Follower;
use App\Repositories\Contracts\FollowRepositoryInterface;
use Illuminate\Support\Facades\DB;

class FollowRepository implements FollowRepositoryInterface
{
    public function follow(int $followerId, int $followingId): Follower
    {
        return Follower::firstOrCreate([
            'uid' => $followerId,
            'fid' => $followingId,
        ]);
    }

    public function unfollow(int $followerId, int $followingId): bool
    {
        return Follower::where('uid', $followerId)
            ->where('fid', $followingId)
            ->delete() > 0;
    }

    public function followersOf(int $userId, int $perPage = 15)
    {
        return Follower::where('fid', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function followingOf(int $userId, int $perPage = 15)
    {
        return Follower::where('uid', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function isFollowing(int $followerId, int $followingId): bool
    {
        return Follower::where('uid', $followerId)
            ->where('fid', $followingId)
            ->exists();
    }
}
