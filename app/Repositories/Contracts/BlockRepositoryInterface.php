<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface BlockRepositoryInterface
{
    public function areBlocked(int $userId, int $otherId): bool;

    public function isBlocked(int $userId, int $targetId): bool;

    public function block(int $userId, int $targetId, string $targetName): void;

    public function unblock(int $userId, int $targetId): void;

    public function getBlockedIds(int $userId): Collection;

    public function getBlockerIds(int $userId): Collection;

    public function isBlockedByRecipient(int $recipientId, int $senderId): bool;
}
