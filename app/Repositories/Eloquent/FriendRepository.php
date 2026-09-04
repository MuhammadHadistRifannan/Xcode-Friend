<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\FriendRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FriendRepository implements FriendRepositoryInterface
{
    public function getFriends(int $userId): Collection
    {
        return DB::table('jcow_friends')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_friends.fid')
            ->where('jcow_friends.uid', $userId)
            ->select('jcow_accounts.id', 'jcow_accounts.fullname', 'jcow_accounts.username', 'jcow_accounts.avatar', 'jcow_friends.created')
            ->orderBy('jcow_friends.created', 'desc')
            ->get();
    }

    public function getIncomingRequests(int $userId): Collection
    {
        return DB::table('jcow_friend_reqs')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_friend_reqs.uid')
            ->where('jcow_friend_reqs.fid', $userId)
            ->select(
                'jcow_friend_reqs.uid',
                'jcow_friend_reqs.fid',
                'jcow_friend_reqs.created',
                'jcow_friend_reqs.msg',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->orderBy('jcow_friend_reqs.created', 'desc')
            ->get();
    }

    public function getOutgoingRequests(int $userId): Collection
    {
        return DB::table('jcow_friend_reqs')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_friend_reqs.fid')
            ->where('jcow_friend_reqs.uid', $userId)
            ->select(
                'jcow_friend_reqs.uid',
                'jcow_friend_reqs.fid',
                'jcow_friend_reqs.created',
                'jcow_friend_reqs.msg',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->orderBy('jcow_friend_reqs.created', 'desc')
            ->get();
    }

    public function areFriends(int $userId, int $otherId): bool
    {
        return DB::table('jcow_friends')
            ->where('uid', $userId)
            ->where('fid', $otherId)
            ->exists();
    }

    public function hasPendingRequest(int $fromId, int $toId): bool
    {
        return DB::table('jcow_friend_reqs')
            ->where('uid', $fromId)
            ->where('fid', $toId)
            ->exists();
    }

    public function getSuggestions(int $userId, int $limit = 10): Collection
    {
        // Ambil ID yang sudah berteman
        $friendIds = DB::table('jcow_friends')
            ->where('uid', $userId)
            ->pluck('fid')
            ->toArray();

        // Ambil ID yang sudah ada di pending request
        $pendingIds = DB::table('jcow_friend_reqs')
            ->where('uid', $userId)
            ->orWhere('fid', $userId)
            ->pluck('uid')
            ->merge(DB::table('jcow_friend_reqs')
                ->where('uid', $userId)
                ->orWhere('fid', $userId)
                ->pluck('fid'))
            ->toArray();

        $excludeIds = array_merge($friendIds, $pendingIds, [$userId]);

        return \App\Models\User::query()
            ->whereNotIn('id', $excludeIds)
            ->where('disabled', 0)
            ->select('id', 'fullname', 'username', 'avatar')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function sendRequest(int $fromId, int $toId, ?string $message = null): void
    {
        $exists = DB::table('jcow_friend_reqs')
            ->where('uid', $fromId)
            ->where('fid', $toId)
            ->exists();

        if ($exists) {
            DB::table('jcow_friend_reqs')
                ->where('uid', $fromId)
                ->where('fid', $toId)
                ->update([
                    'created' => time(),
                    'msg' => $message ?? '',
                ]);
        } else {
            DB::table('jcow_friend_reqs')->insert([
                'uid' => $fromId,
                'fid' => $toId,
                'created' => time(),
                'msg' => $message ?? '',
            ]);
        }
    }

    public function acceptRequest(int $requesterId, int $userId): void
    {
        $now = time();

        // Insert bidirectional friendship
        DB::table('jcow_friends')->insert([
            ['uid' => $requesterId, 'fid' => $userId, 'created' => $now],
            ['uid' => $userId, 'fid' => $requesterId, 'created' => $now],
        ]);

        // Delete request (both directions)
        DB::table('jcow_friend_reqs')
            ->where('uid', $requesterId)
            ->where('fid', $userId)
            ->delete();

        DB::table('jcow_friend_reqs')
            ->where('uid', $userId)
            ->where('fid', $requesterId)
            ->delete();
    }

    public function rejectRequest(int $requesterId, int $userId): void
    {
        DB::table('jcow_friend_reqs')
            ->where('uid', $requesterId)
            ->where('fid', $userId)
            ->delete();

        DB::table('jcow_friend_reqs')
            ->where('uid', $userId)
            ->where('fid', $requesterId)
            ->delete();
    }

    public function unfriend(int $friendId, int $userId): void
    {
        DB::table('jcow_friends')
            ->where('uid', $userId)
            ->where('fid', $friendId)
            ->delete();

        DB::table('jcow_friends')
            ->where('uid', $friendId)
            ->where('fid', $userId)
            ->delete();
    }

    public function areBlocked(int $userId, int $otherId): bool
    {
        return DB::table('jcow_blacks')
            ->where('uid', $otherId)
            ->where('bid', $userId)
            ->exists();
    }

    public function follow(int $userId, int $targetId): void
    {
        $exists = DB::table('jcow_followers')
            ->where('uid', $targetId)
            ->where('fid', $userId)
            ->exists();

        if (!$exists) {
            DB::table('jcow_followers')->insert([
                'uid' => $targetId,
                'fid' => $userId,
            ]);
        }
    }

    public function unfollow(int $userId, int $targetId): void
    {
        DB::table('jcow_followers')
            ->where('uid', $targetId)
            ->where('fid', $userId)
            ->delete();
    }

    public function isFollowing(int $userId, int $targetId): bool
    {
        return DB::table('jcow_followers')
            ->where('uid', $targetId)
            ->where('fid', $userId)
            ->exists();
    }

    public function block(int $userId, int $targetId): void
    {
        $exists = DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->where('bid', $targetId)
            ->exists();

        if (!$exists) {
            $target = DB::table('jcow_accounts')->where('id', $targetId)->first();
            DB::table('jcow_blacks')->insert([
                'uid' => $userId,
                'bid' => $targetId,
                'bname' => $target->username ?? '',
            ]);
        }
    }

    public function unblock(int $userId, int $targetId): void
    {
        DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->where('bid', $targetId)
            ->delete();
    }

    public function isBlocked(int $userId, int $targetId): bool
    {
        return DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->where('bid', $targetId)
            ->exists();
    }

    public function getFollowerCount(int $userId): int
    {
        return (int) DB::table('jcow_followers')
            ->where('uid', $userId)
            ->count();
    }
}
