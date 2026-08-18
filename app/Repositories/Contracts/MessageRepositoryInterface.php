<?php

namespace App\Repositories\Contracts;

interface MessageRepositoryInterface
{
    public function findById(int $id): ?object;

    public function send(int $fromId, int $toId, string $subject, string $message): object;

    public function receivedBy(int $userId, int $perPage = 15);

    public function sentBy(int $userId, int $perPage = 15);

    public function markAsRead(int $userId): bool;

    public function unreadCount(int $userId): int;
}
