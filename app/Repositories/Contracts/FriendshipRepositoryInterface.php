<?php

namespace App\Repositories\Contracts;

interface FriendshipRepositoryInterface
{
    public function sendRequest(int $senderId, int $receiverId, string $message = ''): object;

    public function acceptRequest(int $requestId): bool;

    public function rejectRequest(int $requestId): bool;

    public function cancelRequest(int $requestId): bool;

    public function beFriends(int $userId1, int $userId2): void;

    public function unfriend(int $userId1, int $userId2): bool;

    public function friendsOf(int $userId, int $perPage = 15);

    public function pendingRequests(int $userId, int $perPage = 15);

    public function sentRequests(int $userId, int $perPage = 15);
}
