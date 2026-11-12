<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $messageId,
        public int $senderId,
        public int $recipientId,
        public string $scope = 'everyone',
    ) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('user.' . $this->senderId),
        ];

        if ($this->scope === 'everyone' && $this->recipientId !== $this->senderId) {
            $channels[] = new PrivateChannel('user.' . $this->recipientId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'messageId' => $this->messageId,
            'senderId' => $this->senderId,
            'recipientId' => $this->recipientId,
            'scope' => $this->scope,
        ];
    }
}
