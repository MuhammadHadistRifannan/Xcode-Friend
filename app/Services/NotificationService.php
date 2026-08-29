<?php

namespace App\Services;

use App\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notifRepo
    ) {}

    public function getNotifications(int $userId, int $perPage = 20)
    {
        return $this->notifRepo->getNotifications($userId, $perPage);
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return $this->notifRepo->markAsRead($id, $userId);
    }

    public function markAllAsRead(int $userId): void
    {
        $this->notifRepo->markAllAsRead($userId);
    }

    public function countUnread(int $userId): int
    {
        return $this->notifRepo->countUnread($userId);
    }

    public function delete(int $id, int $userId): bool
    {
        return $this->notifRepo->delete($id, $userId);
    }

    public function create(int $userId, string $type, array $data = []): void
    {
        $this->notifRepo->create($userId, $type, $data);
    }
}
