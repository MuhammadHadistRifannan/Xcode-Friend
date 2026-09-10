<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public object $message,
        public object $sender,
        public int $recipientId,
        public int $totalUnread,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->recipientId)];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'from_id' => $this->message->from_id,
                'to_id' => $this->message->to_id,
                'message' => $this->message->message,
                'created' => $this->message->created,
                'hasread' => $this->message->hasread,
            ],
            'sender' => [
                'id' => $this->sender->id,
                'username' => $this->sender->username,
                'fullname' => $this->sender->fullname,
                'avatar_url' => $this->sender->avatar_url ?? null,
            ],
            'totalUnread' => $this->totalUnread,
        ];
    }
}
