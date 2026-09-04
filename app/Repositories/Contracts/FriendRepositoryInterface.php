<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface FriendRepositoryInterface
{
    public function getFriends(int $userId): Collection;

    public function getIncomingRequests(int $userId): Collection;

    public function getOutgoingRequests(int $userId): Collection;

    public function areFriends(int $userId, int $otherId): bool;

    public function hasPendingRequest(int $fromId, int $toId): bool;

    public function getSuggestions(int $userId, int $limit = 10): Collection;

    public function sendRequest(int $fromId, int $toId, ?string $message = null): void;

    public function acceptRequest(int $requesterId, int $userId): void;

    public function rejectRequest(int $requesterId, int $userId): void;

    public function unfriend(int $friendId, int $userId): void;

    public function areBlocked(int $userId, int $otherId): bool;

    public function follow(int $userId, int $targetId): void;

    public function unfollow(int $userId, int $targetId): void;

    public function isFollowing(int $userId, int $targetId): bool;

    public function block(int $userId, int $targetId): void;

    public function unblock(int $userId, int $targetId): void;

    public function isBlocked(int $userId, int $targetId): bool;

    public function getFollowerCount(int $userId): int;
}
