<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getNotifications(int $userId, ?int $perPage = null): Collection
    {
        $perPage = $perPage ?? config('pagination.notifications');

        return DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->whereNull('deleted_at')
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
            ->whereNull('deleted_at')
            ->first();
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->whereNull('deleted_at')
            ->update(['hasread' => 1]) > 0;
    }

    public function markAllAsRead(int $userId): void
    {
        DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->update(['hasread' => 1]);
    }

    public function countUnread(int $userId): int
    {
        return (int) DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->count();
    }

    public function delete(int $id, int $userId): bool
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->where('from_id', 0)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]) > 0;
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
        $userName = e($data['user_name'] ?? 'System');
        $userSlug = e($data['user_name'] ?? '');

        return match ($type) {
            'friend_request' => "<a href=\"/@" . $userSlug . "\">{$userName}</a> mengirim permintaan pertemanan.",
            'friend_accepted' => "<a href=\"/@" . $userSlug . "\">{$userName}</a> menerima permintaan pertemanan Anda.",
            'new_message' => "Anda memiliki pesan baru dari <a href=\"/@" . $userSlug . "\">{$userName}</a>.",
            'comment' => "<a href=\"/@" . $userSlug . "\">{$userName}</a> mengomentari postingan Anda.",
            'like' => "<a href=\"/@" . $userSlug . "\">{$userName}</a> menyukai postingan Anda.",
            default => e($data['message'] ?? 'Notifikasi baru.'),
        };
    }
}
