<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{
    public function findById(int $id): ?Message
    {
        return Message::find($id);
    }

    public function send(int $fromId, int $toId, string $subject, string $message): Message
    {
        return Message::create([
            'from_id' => $fromId,
            'to_id' => $toId,
            'subject' => $subject,
            'message' => $message,
        ]);
    }

    public function receivedBy(int $userId, int $perPage = 15)
    {
        return Message::where('to_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function sentBy(int $userId, int $perPage = 15)
    {
        return Message::where('from_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function markAsRead(int $userId): bool
    {
        return Message::where('to_id', $userId)
            ->where('hasread', 0)
            ->update(['hasread' => 1]) > 0;
    }

    public function unreadCount(int $userId): int
    {
        return Message::where('to_id', $userId)
            ->where('hasread', 0)
            ->count();
    }
}
