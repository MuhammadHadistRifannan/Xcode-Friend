<?php

namespace App\Events;

use App\Models\Stream;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Stream $stream,
        public ?string $renderedHtml = null
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('public-feed')];
    }

    public function broadcastAs(): string
    {
        return 'stream.created';
    }

    public function broadcastWith(): array
    {
        $stream = $this->stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);
        $html = $this->renderedHtml ?: view('components.single-stream', ['stream' => $stream])->render();

        return [
            'stream_id' => $this->stream->id,
            'user_id'   => $this->stream->uid,
            'wall_id'   => $this->stream->wall_id,
            'type'      => $this->stream->type,
            'message'   => $this->stream->message,
            'username'  => $stream->user->username ?? 'user',
            'fullname'  => $stream->user->fullname ?? 'User',
            'avatar_url'=> $stream->user->avatar_url ?? '',
            'html'      => $html,
            'created'   => $this->stream->created,
        ];
    }
}
