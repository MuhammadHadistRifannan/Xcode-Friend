<?php

namespace App\Repositories\Eloquent;

use App\Models\FriendRequest;
use App\Models\Account;
use App\Repositories\Contracts\FriendshipRepositoryInterface;
use Illuminate\Support\Facades\DB;

class FriendshipRepository implements FriendshipRepositoryInterface
{
    public function sendRequest(int $senderId, int $receiverId, string $message = ''): FriendRequest
    {
        return FriendRequest::create([
            'uid' => $senderId,
            'fid' => $receiverId,
            'msg' => $message,
        ]);
    }

    public function acceptRequest(int $requestId): bool
    {
        $request = FriendRequest::find($requestId);
        if (!$request) {
            return false;
        }

        DB::transaction(function () use ($request) {
            $request->update(['accepted_at' => now()]);

            Account::find($request->uid)->friends()->attach($request->fid, ['created_at' => now()]);
            Account::find($request->fid)->friends()->attach($request->uid, ['created_at' => now()]);
        });

        return true;
    }

    public function rejectRequest(int $requestId): bool
    {
        return FriendRequest::where('id', $requestId)->delete() > 0;
    }

    public function cancelRequest(int $requestId): bool
    {
        return FriendRequest::where('id', $requestId)->delete() > 0;
    }

    public function unfriend(int $userId1, int $userId2): bool
    {
        return DB::table('jcow_friends')
            ->whereIn('user_id', [$userId1, $userId2])
            ->whereIn('friend_id', [$userId1, $userId2])
            ->delete() > 0;
    }

    public function friendsOf(int $userId, int $perPage = 15)
    {
        return Account::find($userId)
            ->friends()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function pendingRequests(int $userId, int $perPage = 15)
    {
        return FriendRequest::where('fid', $userId)
            ->whereNull('accepted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function sentRequests(int $userId, int $perPage = 15)
    {
        return FriendRequest::where('uid', $userId)
            ->whereNull('accepted_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
