<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    public function getInbox(int $userId, int $perPage = 20, string $sort = 'terbaru', string $status = 'all'): LengthAwarePaginator
    {
        $query = DB::table('jcow_messages')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages.from_id')
            ->where('jcow_messages.to_id', $userId)
            ->where('jcow_messages.from_id', '>', 0);

        // Filter by read status
        if ($status === 'unread') {
            $query->whereRaw('jcow_messages.hasread = 0');
        } elseif ($status === 'read') {
            $query->whereRaw('jcow_messages.hasread = 1');
        }

        // Sort
        $query = match ($sort) {
            'terlama' => $query->orderBy('jcow_messages.created', 'asc'),
            'abjad_az' => $query->orderBy('jcow_accounts.fullname', 'asc'),
            'abjad_za' => $query->orderBy('jcow_accounts.fullname', 'desc'),
            default => $query->orderBy('jcow_messages.created', 'desc'),
        };

        return $query->select(
                'jcow_messages.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->paginate($perPage);
    }

    public function getOutbox(int $userId, int $perPage = 20, string $sort = 'terbaru'): LengthAwarePaginator
    {
        $query = DB::table('jcow_messages_sent')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages_sent.to_id')
            ->where('jcow_messages_sent.from_id', $userId);

        // Sort
        $query = match ($sort) {
            'terlama' => $query->orderBy('jcow_messages_sent.created', 'asc'),
            'abjad_az' => $query->orderBy('jcow_accounts.fullname', 'asc'),
            'abjad_za' => $query->orderBy('jcow_accounts.fullname', 'desc'),
            default => $query->orderBy('jcow_messages_sent.created', 'desc'),
        };

        return $query->select(
                'jcow_messages_sent.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->paginate($perPage);
    }

    public function getConversation(int $userId, int $otherId): Collection
    {
        return DB::table('jcow_messages')
            ->join('jcow_accounts as sender', 'sender.id', '=', 'jcow_messages.from_id')
            ->join('jcow_accounts as receiver', 'receiver.id', '=', 'jcow_messages.to_id')
            ->where(function ($query) use ($userId, $otherId) {
                $query->where('jcow_messages.from_id', $userId)
                    ->where('jcow_messages.to_id', $otherId);
            })->orWhere(function ($query) use ($userId, $otherId) {
                $query->where('jcow_messages.from_id', $otherId)
                    ->where('jcow_messages.to_id', $userId);
            })
            ->select(
                'jcow_messages.*',
                'sender.fullname as sender_name',
                'sender.username as sender_username',
                'sender.avatar as sender_avatar',
                'receiver.fullname as receiver_name',
                'receiver.username as receiver_username'
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
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages_sent.to_id')
            ->where('jcow_messages_sent.id', $id)
            ->where('jcow_messages_sent.from_id', $userId)
            ->select(
                'jcow_messages_sent.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->first();
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return DB::table('jcow_messages')
            ->where('id', $id)
            ->where('to_id', $userId)
            ->update(['hasread' => 1]) > 0;
    }

    public function markConversationAsRead(int $userId, int $otherId): void
    {
        DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', $otherId)
            ->whereRaw('hasread = 0')
            ->update(['hasread' => 1]);
    }

    public function searchInbox(int $userId, string $keyword): Collection
    {
        return DB::table('jcow_messages')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages.from_id')
            ->where('jcow_messages.to_id', $userId)
            ->where('jcow_messages.from_id', '>', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('jcow_accounts.fullname', 'like', "%{$keyword}%")
                    ->orWhere('jcow_accounts.username', 'like', "%{$keyword}%")
                    ->orWhere('jcow_messages.subject', 'like', "%{$keyword}%")
                    ->orWhere('jcow_messages.message', 'like', "%{$keyword}%");
            })
            ->select(
                'jcow_messages.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->orderBy('jcow_messages.created', 'desc')
            ->get();
    }

    public function searchOutbox(int $userId, string $keyword): Collection
    {
        return DB::table('jcow_messages_sent')
            ->join('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_messages_sent.to_id')
            ->where('jcow_messages_sent.from_id', $userId)
            ->where(function ($query) use ($keyword) {
                $query->where('jcow_accounts.fullname', 'like', "%{$keyword}%")
                    ->orWhere('jcow_accounts.username', 'like', "%{$keyword}%")
                    ->orWhere('jcow_messages_sent.subject', 'like', "%{$keyword}%")
                    ->orWhere('jcow_messages_sent.message', 'like', "%{$keyword}%");
            })
            ->select(
                'jcow_messages_sent.*',
                'jcow_accounts.fullname',
                'jcow_accounts.username',
                'jcow_accounts.avatar'
            )
            ->orderBy('jcow_messages_sent.created', 'desc')
            ->get();
    }

    public function countUnread(int $userId): int
    {
        return (int) DB::table('jcow_messages')
            ->where('to_id', $userId)
            ->where('from_id', '>', 0)
            ->whereRaw('hasread = 0')
            ->count();
    }

    public function deleteById(int $id, int $userId, string $type = 'inbox'): bool
    {
        $table = $type === 'outbox' ? 'jcow_messages_sent' : 'jcow_messages';
        $column = $type === 'outbox' ? 'from_id' : 'to_id';

        return DB::table($table)
            ->where('id', $id)
            ->where($column, $userId)
            ->delete() > 0;
    }

    public function bulkDelete(array $ids, int $userId, string $type = 'inbox'): int
    {
        $table = $type === 'outbox' ? 'jcow_messages_sent' : 'jcow_messages';
        $column = $type === 'outbox' ? 'from_id' : 'to_id';

        return DB::table($table)
            ->whereIn('id', $ids)
            ->where($column, $userId)
            ->delete();
    }
}
