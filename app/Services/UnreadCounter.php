<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UnreadCounter
{
    private const TTL = 120;

    public function notificationCount(int $userId): int
    {
        return Cache::remember('unread:notif:' . $userId, self::TTL, function () use ($userId) {
            return (int) DB::table('jcow_messages')
                ->where('from_id', 0)
                ->where('to_id', $userId)
                ->whereRaw('hasread = 0')
                ->whereNull('deleted_at')
                ->count();
        });
    }

    public function messageCount(int $userId): int
    {
        $data = Cache::remember('unread:msg:' . $userId, self::TTL, function () use ($userId) {
            return $this->computeMessageData($userId);
        });

        return $data['total'] ?? 0;
    }

    public function messageCountByConversation(int $userId, array $friendIds): array
    {
        $data = Cache::remember('unread:msg:' . $userId, self::TTL, function () use ($userId) {
            return $this->computeMessageData($userId);
        });

        $byConversation = $data['by_conversation'] ?? [];
        $result = [];
        foreach ($friendIds as $friendId) {
            $result[$friendId] = $byConversation[$friendId] ?? 0;
        }

        return $result;
    }

    private function computeMessageData(int $userId): array
    {
        $rows = DB::table('jcow_messages')
            ->where('from_id', '!=', 0)
            ->where('to_id', $userId)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->select('from_id', DB::raw('count(*) as total'))
            ->groupBy('from_id')
            ->get();

        $byConversation = [];
        $total = 0;
        foreach ($rows as $row) {
            $byConversation[(int) $row->from_id] = (int) $row->total;
            $total += (int) $row->total;
        }

        return ['total' => $total, 'by_conversation' => $byConversation];
    }
}
