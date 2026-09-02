<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface MessageRepositoryInterface
{
    public function getInbox(int $userId, int $perPage = 20, string $sort = 'terbaru', string $status = 'all'): LengthAwarePaginator;

    public function getOutbox(int $userId, int $perPage = 20, string $sort = 'terbaru'): LengthAwarePaginator;

    public function getConversation(int $userId, int $otherId): Collection;

    public function getById(int $id, int $userId): ?object;

    public function markAsRead(int $id, int $userId): bool;

    public function markConversationAsRead(int $userId, int $otherId): void;

    public function searchInbox(int $userId, string $keyword): Collection;

    public function searchOutbox(int $userId, string $keyword): Collection;

    public function countUnread(int $userId): int;

    public function deleteById(int $id, int $userId, string $type = 'inbox'): bool;

    public function bulkDelete(array $ids, int $userId, string $type = 'inbox'): int;
}
