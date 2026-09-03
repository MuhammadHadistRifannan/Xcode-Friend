<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getNotifications(int $userId, int $perPage = 20): Collection
    {
        return DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->orderBy('created', 'desc')
            ->limit($perPage)
            ->get();
    }

    public function getById(int $id, int $userId): ?object
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->first();
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->where('hasread', 0)
            ->update(['hasread' => 1]) > 0;
    }

    public function markAllAsRead(int $userId): void
    {
        DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->where('hasread', 0)
            ->update(['hasread' => 1]);
    }

    public function countUnread(int $userId): int
    {
        return (int) DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->where('hasread', 0)
            ->count();
    }

    public function delete(int $id, int $userId): bool
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->delete() > 0;
    }

    public function create(int $userId, string $type, array $data = []): void
    {
        $message = $this->buildMessage($type, $data);

        DB::table('jcow_messages')->insert([
            'from_id' => 0,
            'to_id' => $userId,
            'subject' => $type,
            'message' => $message,
            'created' => time(),
            'hasread' => 0,
        ]);
    }

    private function buildMessage(string $type, array $data): string
    {
        $userName = $data['user_name'] ?? 'System';
        $userId = $data['user_id'] ?? 0;

        return match ($type) {
            'friend_request' => "<a href=\"/user/{$userId}\">{$userName}</a> mengirim permintaan pertemanan.",
            'friend_accepted' => "<a href=\"/user/{$userId}\">{$userName}</a> menerima permintaan pertemanan Anda.",
            'new_message' => "Anda memiliki pesan baru dari <a href=\"/user/{$userId}\">{$userName}</a>.",
            'comment' => "<a href=\"/user/{$userId}\">{$userName}</a> mengomentari postingan Anda.",
            'like' => "<a href=\"/user/{$userId}\">{$userName}</a> menyukai postingan Anda.",
            default => $data['message'] ?? 'Notifikasi baru.',
        };
    }
}
