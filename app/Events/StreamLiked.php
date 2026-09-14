<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamLiked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $streamId,
        public int $likesCount,
        public int $userId,
        public string $status
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('public-feed')];
    }

    public function broadcastAs(): string
    {
        return 'stream.liked';
    }

    public function broadcastWith(): array
    {
        return [
            'stream_id'   => $this->streamId,
            'likes_count' => $this->likesCount,
            'user_id'     => $this->userId,
            'status'      => $this->status,
        ];
    }
}
