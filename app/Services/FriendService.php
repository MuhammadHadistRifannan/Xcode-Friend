<?php

namespace App\Services;

use App\Repositories\Contracts\FriendRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class FriendService
{
    public function __construct(
        private FriendRepositoryInterface $friendRepo,
        private NotificationRepositoryInterface $notifRepo
    ) {}

    public function getFriends(int $userId)
    {
        return $this->friendRepo->getFriends($userId);
    }

    public function getIncomingRequests(int $userId)
    {
        return $this->friendRepo->getIncomingRequests($userId);
    }

    public function getOutgoingRequests(int $userId)
    {
        return $this->friendRepo->getOutgoingRequests($userId);
    }

    public function getSuggestions(int $userId, int $limit = 10)
    {
        return $this->friendRepo->getSuggestions($userId, $limit);
    }

    public function sendRequest(int $fromId, int $toId, ?string $message = null): void
    {
        if ($fromId === $toId) {
            throw new Exception('Tidak bisa menambahkan diri sendiri.');
        }

        if ($this->friendRepo->areFriends($fromId, $toId)) {
            throw new Exception('Anda sudah berteman dengan user ini.');
        }

        if ($this->friendRepo->hasPendingRequest($fromId, $toId)) {
            throw new Exception('Permintaan pertemanan sudah dikirim.');
        }

        if ($this->friendRepo->areBlocked($fromId, $toId)) {
            throw new Exception('Tidak bisa mengirim permintaan ke user ini.');
        }

        $this->friendRepo->sendRequest($fromId, $toId, $message);

        $sender = \App\Models\User::query()->where('id', $fromId)->first();
        $this->notifRepo->create(
            $toId,
            'friend_request',
            [
                'user_id' => $fromId,
                'user_name' => $sender->fullname ?? 'User',
            ]
        );
    }

    public function acceptRequest(int $requesterId, int $userId): void
    {
        if (!$this->friendRepo->hasPendingRequest($requesterId, $userId)) {
            throw new Exception('Permintaan pertemanan tidak ditemukan.');
        }

        $this->friendRepo->acceptRequest($requesterId, $userId);

        // Kirim notifikasi ke pengirim request
        $user = \App\Models\User::query()->where('id', $userId)->first();
        $this->notifRepo->create(
            $requesterId,
            'friend_accepted',
            [
                'user_id' => $userId,
                'user_name' => $user->fullname ?? 'User',
            ]
        );
    }

    public function rejectRequest(int $requesterId, int $userId): void
    {
        if (!$this->friendRepo->hasPendingRequest($requesterId, $userId)) {
            throw new Exception('Permintaan pertemanan tidak ditemukan.');
        }

        $this->friendRepo->rejectRequest($requesterId, $userId);
    }

    public function unfriend(int $friendId, int $userId): void
    {
        if (!$this->friendRepo->areFriends($userId, $friendId)) {
            throw new Exception('Anda tidak berteman dengan user ini.');
        }

        $this->friendRepo->unfriend($friendId, $userId);
    }

    public function areFriends(int $userId, int $otherId): bool
    {
        return $this->friendRepo->areFriends($userId, $otherId);
    }

    public function follow(int $userId, int $targetId): void
    {
        if ($userId === $targetId) {
            throw new Exception('Tidak bisa mengikuti diri sendiri.');
        }
        $this->friendRepo->follow($userId, $targetId);

        $follower = \App\Models\User::query()->where('id', $userId)->first();
        if ($follower) {
            $this->notifRepo->create(
                $targetId,
                'follow',
                [
                    'user_id' => $userId,
                    'user_name' => $follower->fullname ?? $follower->username ?? 'User',
                ]
            );
        }
    }

    public function unfollow(int $userId, int $targetId): void
    {
        $this->friendRepo->unfollow($userId, $targetId);
    }

    public function isFollowing(int $userId, int $targetId): bool
    {
        return $this->friendRepo->isFollowing($userId, $targetId);
    }

    public function block(int $userId, int $targetId): void
    {
        if ($userId === $targetId) {
            throw new Exception('Tidak bisa memblokir diri sendiri.');
        }
        $this->friendRepo->block($userId, $targetId);
    }

    public function unblock(int $userId, int $targetId): void
    {
        $this->friendRepo->unblock($userId, $targetId);
    }

    public function isBlocked(int $userId, int $targetId): bool
    {
        return $this->friendRepo->isBlocked($userId, $targetId);
    }

    public function getFollowerCount(int $userId): int
    {
        return $this->friendRepo->getFollowerCount($userId);
    }
}
