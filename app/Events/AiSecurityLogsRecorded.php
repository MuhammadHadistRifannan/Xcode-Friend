<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Notifikasi real-time ke panel admin bahwa webhook AI baru mencatat log.
 * Hanya ringkasan (payload kecil); halaman admin mengambil ulang datanya sendiri.
 * Private channel admin-only: tidak ada broadcast publik (sesuai briefing).
 */
class AiSecurityLogsRecorded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public int $count,
        public string $topSeverity,
        public array $attackTypes,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.security')];
    }

    public function broadcastAs(): string
    {
        return 'ai-security.recorded';
    }

    public function broadcastWith(): array
    {
        return [
            'count' => $this->count,
            'top_severity' => $this->topSeverity,
            'attack_types' => array_slice($this->attackTypes, 0, 5),
        ];
    }
}
