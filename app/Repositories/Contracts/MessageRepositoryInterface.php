<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface MessageRepositoryInterface
{
    public function getConversation(int $userId, int $otherId): Collection;

    public function getById(int $id, int $userId): ?object;

    public function markConversationAsRead(int $userId, int $otherId): void;

    public function countUnread(int $userId): int;

    public function getLastMessage(int $userId, int $otherId): ?object;

    public function countUnreadBetween(int $userId, int $otherId): int;

    public function getLastMessagesForFriends(int $userId, array $friendIds): array;

    public function countUnreadBetweenMultiple(int $userId, array $friendIds): array;

    public function deleteForSelf(int $messageId, int $userId): bool;

    public function deleteForEveryone(int $messageId, int $userId): bool;

    public function deleteConversation(int $userId, int $otherId): bool;
}
