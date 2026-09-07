<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BlockRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlockRepository implements BlockRepositoryInterface
{
    public function areBlocked(int $userId, int $otherId): bool
    {
        return DB::table('jcow_blacks')
            ->where(function ($query) use ($userId, $otherId) {
                $query->where('uid', $userId)->where('bid', $otherId)
                    ->orWhere('uid', $otherId)->where('bid', $userId);
            })
            ->exists();
    }

    public function isBlocked(int $userId, int $targetId): bool
    {
        return DB::table('jcow_blacks')
            ->where(function ($query) use ($userId, $targetId) {
                $query->where('uid', $userId)->where('bid', $targetId)
                    ->orWhere('uid', $targetId)->where('bid', $userId);
            })
            ->exists();
    }

    public function block(int $userId, int $targetId, string $targetName): void
    {
        $exists = DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->where('bid', $targetId)
            ->exists();

        if (!$exists) {
            DB::table('jcow_blacks')->insert([
                'uid' => $userId,
                'bid' => $targetId,
                'bname' => $targetName,
            ]);

            DB::table('jcow_friends')
                ->where(function ($q) use ($userId, $targetId) {
                    $q->where('uid', $userId)->where('fid', $targetId)
                        ->orWhere('uid', $targetId)->where('fid', $userId);
                })
                ->delete();

            DB::table('jcow_followers')
                ->where(function ($q) use ($userId, $targetId) {
                    $q->where('uid', $userId)->where('fid', $targetId)
                        ->orWhere('uid', $targetId)->where('fid', $userId);
                })
                ->delete();

            DB::table('jcow_friend_reqs')
                ->where(function ($q) use ($userId, $targetId) {
                    $q->where('uid', $userId)->where('fid', $targetId)
                        ->orWhere('uid', $targetId)->where('fid', $userId);
                })
                ->delete();
        }
    }

    public function unblock(int $userId, int $targetId): void
    {
        DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->where('bid', $targetId)
            ->delete();
    }

    public function getBlockedIds(int $userId): Collection
    {
        return DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->pluck('bid');
    }

    public function getBlockerIds(int $userId): Collection
    {
        return DB::table('jcow_blacks')
            ->where('bid', $userId)
            ->pluck('uid');
    }

    public function isBlockedByRecipient(int $recipientId, int $senderId): bool
    {
        return DB::table('jcow_blacks')
            ->where('uid', $recipientId)
            ->where('bid', $senderId)
            ->exists();
    }
}
