<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $readerId,
        public int $senderId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->senderId),
            new PrivateChannel('user.' . $this->readerId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }

    public function broadcastWith(): array
    {
        $unread = 0;
        try {
            $unread = app(\App\Repositories\Contracts\MessageRepositoryInterface::class)->countUnread($this->readerId);
        } catch (\Throwable $e) {}

        return [
            'readerId' => $this->readerId,
            'senderId' => $this->senderId,
            'readerUnreadCount' => $unread,
        ];
    }
}
