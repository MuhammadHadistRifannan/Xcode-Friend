<?php

namespace App\Services;

use App\Events\NotificationCreated;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notifRepo
    ) {}

    public function getNotifications(int $userId, int $perPage = 20)
    {
        return $this->notifRepo->getNotifications($userId, $perPage);
    }

    public function getById(int $id, int $userId): ?object
    {
        return $this->notifRepo->getById($id, $userId);
    }

    public function markAsRead(int $id, int $userId): bool
    {
        $result = $this->notifRepo->markAsRead($id, $userId);
        Cache::forget('unread:notif:' . $userId);
        return $result;
    }

    public function markAllAsRead(int $userId): void
    {
        $this->notifRepo->markAllAsRead($userId);
        Cache::forget('unread:notif:' . $userId);
    }

    public function countUnread(int $userId): int
    {
        return $this->notifRepo->countUnread($userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $result = $this->notifRepo->delete($id, $userId);
        Cache::forget('unread:notif:' . $userId);
        return $result;
    }

    public function create(int $userId, string $type, array $data = []): void
    {
        $this->notifRepo->create($userId, $type, $data);
        Cache::forget('unread:notif:' . $userId);

        $unreadCount = $this->notifRepo->countUnread($userId);
        try {
            broadcast(new NotificationCreated((object) [
                'id' => 0,
                'subject' => $type,
                'message' => $data['display_name'] ?? $data['user_name'] ?? $type,
                'created' => time(),
            ], $userId, $unreadCount))->toOthers();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast NotificationCreated gagal: ' . $e->getMessage());
        }
    }
}
