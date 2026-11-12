<?php

namespace App\Events;

use App\Models\Group;
use App\Models\Stream;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class StreamCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Stream $stream,
        public ?string $renderedHtml = null
    ) {}

    /**
     * Tentukan channel broadcast berdasarkan ruang lingkup privasi postingan.
     * - hidden (hide=1) -> tidak di-broadcast
     * - private -> hanya ke PrivateChannel('user.{authorId}')
     * - private_group -> hanya ke PrivateChannel('group.{groupId}') & PrivateChannel('user.{memberId}')
     * - friends -> hanya ke PrivateChannel('user.{friendId}') milik teman yang tidak terblokir
     * - public -> ke Channel('public-feed')
     */
    public function broadcastOn(): array
    {
        if ((int) ($this->stream->hide ?? 0) === 1) {
            return [];
        }

        $authorId = (int) $this->stream->uid;
        $blockedIds = $this->getBlockedUserIds($authorId);

        // 1. Cek jika postingan berada di grup privat
        if ($this->stream->app === 'group') {
            $groupId = (int) ($this->stream->wall_id ?: $this->stream->aid);
            $group = $groupId > 0 ? Group::withoutGlobalScopes()->find($groupId) : null;

            if ($group && $group->type === 'private_group') {
                $memberIds = DB::table('jcow_page_users')
                    ->where('pid', $group->id)
                    ->pluck('uid')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $recipientIds = array_values(array_diff(
                    array_unique(array_merge([$authorId, (int) $group->uid], $memberIds)),
                    $blockedIds
                ));

                $channels = [new PrivateChannel('group.' . $group->id)];
                foreach ($recipientIds as $uid) {
                    if ($uid > 0) {
                        $channels[] = new PrivateChannel('user.' . $uid);
                    }
                }

                return $channels;
            }
        }

        $privacy = $this->stream->privacy ?: 'public';

        // 2. Privasi: Hanya Saya (private)
        if ($privacy === 'private') {
            return $authorId > 0 ? [new PrivateChannel('user.' . $authorId)] : [];
        }

        // 3. Privasi: Hanya Teman (friends)
        if ($privacy === 'friends') {
            $friendIds = DB::table('jcow_friends')
                ->where('uid', $authorId)
                ->pluck('fid')
                ->merge(
                    DB::table('jcow_friends')
                        ->where('fid', $authorId)
                        ->pluck('uid')
                )
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            $recipientIds = array_values(array_diff(
                array_unique(array_merge([$authorId], $friendIds)),
                $blockedIds
            ));

            return array_values(array_filter(array_map(
                fn (int $uid) => $uid > 0 ? new PrivateChannel('user.' . $uid) : null,
                $recipientIds
            )));
        }

        // 4. Privasi: Publik (public)
        return [new Channel('public-feed')];
    }

    public function broadcastAs(): string
    {
        return 'stream.created';
    }

    public function broadcastWith(): array
    {
        $stream = $this->stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);

        // Render dengan forBroadcast=true agar tombol Edit/Hapus dan token pemilik tidak bocor ke user lain
        $html = view('components.single-stream', [
            'stream' => $stream,
            'forBroadcast' => true,
        ])->render();

        $blockedIds = $this->getBlockedUserIds((int) $this->stream->uid);

        return [
            'stream_id'        => $this->stream->id,
            'user_id'          => (int) $this->stream->uid,
            'wall_id'          => (int) $this->stream->wall_id,
            'type'             => $this->stream->type,
            'app'              => $this->stream->app ?? 'feed',
            'privacy'          => $this->stream->privacy ?: 'public',
            'message'          => $this->stream->message,
            'username'         => $stream->user->username ?? 'user',
            'fullname'         => $stream->user->fullname ?? 'User',
            'avatar_url'       => $stream->user->avatar_url ?? '',
            'attachment'       => $this->stream->attachment,
            'html'             => $html,
            'created'          => $this->stream->created,
            'blocked_user_ids' => $blockedIds,
        ];
    }

    /**
     * Ambil daftar ID user yang diblokir atau memblokir pembuat postingan.
     */
    private function getBlockedUserIds(int $authorId): array
    {
        if ($authorId <= 0) {
            return [];
        }

        try {
            $blockedByAuthor = DB::table('jcow_blacks')
                ->where('uid', $authorId)
                ->pluck('bid')
                ->all();

            $blockingAuthor = DB::table('jcow_blacks')
                ->where('bid', $authorId)
                ->pluck('uid')
                ->all();

            return array_values(array_unique(array_map(
                'intval',
                array_merge($blockedByAuthor, $blockingAuthor)
            )));
        } catch (\Throwable $e) {
            return [];
        }
    }
}
