<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function getNotifications(int $userId, int $perPage = 20): Collection;

    public function getById(int $id, int $userId): ?object;

    public function markAsRead(int $id, int $userId): bool;

    public function markAllAsRead(int $userId): void;

    public function countUnread(int $userId): int;

    public function delete(int $id, int $userId): bool;

    public function create(int $userId, string $type, array $data = []): void;
}
