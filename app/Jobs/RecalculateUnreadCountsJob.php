<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RecalculateUnreadCountsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $this->recalculateNotifications();
        $this->recalculateMessages();
    }

    protected function recalculateNotifications(): void
    {
        $counts = DB::table('jcow_messages')
            ->where('from_id', 0)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->select('to_id', DB::raw('count(*) as total'))
            ->groupBy('to_id')
            ->pluck('total', 'to_id')
            ->toArray();

        foreach ($counts as $userId => $count) {
            Cache::put('unread:notif:' . $userId, (int) $count, now()->addMinutes(2));
        }
    }

    protected function recalculateMessages(): void
    {
        $rows = DB::table('jcow_messages')
            ->where('from_id', '!=', 0)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->select('to_id', 'from_id', DB::raw('count(*) as total'))
            ->groupBy('to_id', 'from_id')
            ->get();

        $perUser = [];
        foreach ($rows as $row) {
            $perUser[(int) $row->to_id][(int) $row->from_id] = (int) $row->total;
        }

        foreach ($perUser as $userId => $byFrom) {
            $total = array_sum($byFrom);
            Cache::put('unread:msg:' . $userId, ['total' => $total, 'by_conversation' => $byFrom], now()->addMinutes(2));
        }
    }
}
