<?php

namespace App\Repositories\Contracts;

interface FollowRepositoryInterface
{
    public function follow(int $followerId, int $followingId): object;

    public function unfollow(int $followerId, int $followingId): bool;

    public function followersOf(int $userId, int $perPage = 15);

    public function followingOf(int $userId, int $perPage = 15);

    public function isFollowing(int $followerId, int $followingId): bool;
}
