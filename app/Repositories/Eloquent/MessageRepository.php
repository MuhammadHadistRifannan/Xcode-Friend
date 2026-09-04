<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    public function getConversation(int $userId, int $otherId): Collection
    {
        return DB::table('jcow_messages')
            ->join('jcow_accounts as sender', 'sender.id', '=', 'jcow_messages.from_id')
            ->join('jcow_accounts as receiver', 'receiver.id', '=', 'jcow_messages.to_id')
            ->leftJoin('jcow_messages as replied', 'replied.id', '=', 'jcow_messages.reply_to')
            ->leftJoin('jcow_accounts as replied_sender', 'replied_sender.id', '=', 'replied.from_id')
            ->leftJoin('jcow_messages_hidden as hidden', function ($join) use ($userId) {
                $join->on('hidden.message_id', '=', 'jcow_messages.id')
                    ->where('hidden.user_id', '=', $userId);
            })
            ->whereNull('jcow_messages.deleted_at')
            ->whereNull('hidden.id')
            ->where(function ($query) use ($userId, $otherId) {
                $query->where(function ($q) use ($userId, $otherId) {
                    $q->where('jcow_messages.from_id', $userId)
                        ->where('jcow_messages.to_id', $otherId);
                })->orWhere(function ($q) use ($userId, $otherId) {
                    $q->where('jcow_messages.from_id', $otherId)
                        ->where('jcow_messages.to_id', $userId);
                });
            })
            ->select(
                'jcow_messages.*',
                'sender.fullname as sender_name',
                'sender.username as sender_username',
                'sender.avatar as sender_avatar',
                'receiver.fullname as receiver_name',
                'receiver.username as receiver_username',
                'replied.message as replied_message',
                'replied_sender.fullname as replied_sender_name'
            )
            ->orderBy('jcow_messages.created', 'asc')
            ->get();
    }

    public function getById(int $id, int $userId): ?object
    {
        $message = DB::table('jcow_messages')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages.from_id')
            ->where('jcow_messages.id', $id)
            ->where('jcow_messages.to_id', $userId)
            ->whereNull('jcow_messages.deleted_at')
            ->select(
                'jcow_messages.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->first();

        if ($message) {
            return $message;
        }

        return DB::table('jcow_messages_sent')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages_sent.from_id')
            ->where('jcow_messages_sent.source_id', $id)
            ->where('jcow_messages_sent.from_id', $userId)
            ->whereNull('jcow_messages_sent.deleted_at')
            ->select(
                'jcow_messages_sent.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->first();
    }

    public function markConversationAsRead(int $userId, int $otherId): void
    {
        DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', $otherId)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->update(['hasread' => 1]);
    }

    public function countUnread(int $userId): int
    {
        return (int) DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', '>', 0)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->count();
    }

    public function getLastMessage(int $userId, int $otherId): ?object
    {
        return DB::table('jcow_messages')
            ->where(function ($q) use ($userId, $otherId) {
                $q->where('from_id', $userId)->where('to_id', $otherId)
                  ->orWhere('from_id', $otherId)->where('to_id', $userId);
            })
            ->whereNull('deleted_at')
            ->orderBy('created', 'desc')
            ->first();
    }

    public function countUnreadBetween(int $userId, int $otherId): int
    {
        return (int) DB::table('jcow_messages')
            ->where('from_id', $otherId)
            ->where('to_id', $userId)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->count();
    }

    public function getLastMessagesForFriends(int $userId, array $friendIds): array
    {
        if (empty($friendIds)) {
            return [];
        }

        $results = DB::table('jcow_messages')
            ->whereNull('deleted_at')
            ->where(function ($q) use ($userId, $friendIds) {
                $q->where(function ($q2) use ($userId, $friendIds) {
                    $q2->where('from_id', $userId)->whereIn('to_id', $friendIds);
                })->orWhere(function ($q2) use ($userId, $friendIds) {
                    $q2->whereIn('from_id', $friendIds)->where('to_id', $userId);
                });
            })
            ->orderBy('created', 'desc')
            ->get()
            ->groupBy(function ($msg) use ($userId) {
                return $msg->from_id == $userId ? $msg->to_id : $msg->from_id;
            });

        $lastMessages = [];
        foreach ($results as $friendId => $messages) {
            $lastMessages[$friendId] = $messages->first();
        }

        return $lastMessages;
    }

    public function countUnreadBetweenMultiple(int $userId, array $friendIds): array
    {
        if (empty($friendIds)) {
            return [];
        }

        $counts = DB::table('jcow_messages')
            ->whereIn('from_id', $friendIds)
            ->where('to_id', $userId)
            ->whereRaw('hasread = 0')
            ->whereNull('deleted_at')
            ->select('from_id', DB::raw('count(*) as unread_count'))
            ->groupBy('from_id')
            ->pluck('unread_count', 'from_id')
            ->toArray();

        $result = [];
        foreach ($friendIds as $friendId) {
            $result[$friendId] = $counts[$friendId] ?? 0;
        }

        return $result;
    }

    public function deleteForSelf(int $messageId, int $userId): bool
    {
        DB::table('jcow_messages_hidden')->updateOrInsert(
            ['message_id' => $messageId, 'user_id' => $userId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return true;
    }

    public function deleteForEveryone(int $messageId, int $userId): bool
    {
        DB::table('jcow_messages')
            ->where('id', $messageId)
            ->where('from_id', $userId)
            ->update(['deleted_at' => now()]);

        DB::table('jcow_messages_sent')
            ->where('source_id', $messageId)
            ->where('from_id', $userId)
            ->update(['deleted_at' => now()]);

        return true;
    }

    public function deleteConversation(int $userId, int $otherId): bool
    {
        $messageIds = DB::table('jcow_messages')
            ->where(function ($q) use ($userId, $otherId) {
                $q->where('from_id', $userId)->where('to_id', $otherId)
                  ->orWhere('from_id', $otherId)->where('to_id', $userId);
            })
            ->pluck('id');

        if ($messageIds->isNotEmpty()) {
            $now = now();
            $inserts = [];
            foreach ($messageIds as $msgId) {
                $inserts[] = ['message_id' => $msgId, 'user_id' => $userId, 'created_at' => $now, 'updated_at' => $now];
            }
            DB::table('jcow_messages_hidden')->insert($inserts);
        }

        return true;
    }
}
