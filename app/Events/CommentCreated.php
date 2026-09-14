<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Comment $comment,
        public int $streamId,
        public int $commentsCount,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('public-feed')];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    public function broadcastWith(): array
    {
        $comment = $this->comment->loadMissing('user');
        $user = $comment->user;

        return [
            'stream_id' => $this->streamId,
            'comments_count' => $this->commentsCount,
            'comment' => [
                'id' => $this->comment->id,
                'message' => $this->comment->message,
                'created_human' => 'Baru saja',
                'user' => [
                    'id' => $user->id ?? 0,
                    'fullname' => $user->fullname ?? 'Unknown',
                    'username' => $user->username ?? 'user',
                    'avatar' => $user->avatar_url ?? asset('assets/img/default.png'),
                ],
            ],
        ];
    }
}
