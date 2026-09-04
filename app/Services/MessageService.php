<?php

namespace App\Services;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MessageService
{
    public function __construct(
        private MessageRepositoryInterface $messageRepo,
        private NotificationRepositoryInterface $notifRepo
    ) {}

    public function getInbox(int $userId, int $perPage = 20, string $sort = 'terbaru', string $status = 'all')
    {
        return $this->messageRepo->getInbox($userId, $perPage, $sort, $status);
    }

    public function getOutbox(int $userId, int $perPage = 20, string $sort = 'terbaru')
    {
        return $this->messageRepo->getOutbox($userId, $perPage, $sort);
    }

    public function getConversation(int $userId, int $otherId)
    {
        return $this->messageRepo->getConversation($userId, $otherId);
    }

    public function getById(int $id, int $userId)
    {
        return $this->messageRepo->getById($id, $userId);
    }

    public function send(int $senderId, int $recipientId, ?string $subject, string $message, ?int $replyTo = null): object
    {
        return DB::transaction(function () use ($senderId, $recipientId, $subject, $message, $replyTo) {
            $now = time();

            $messageId = DB::table('jcow_messages')->insertGetId([
                'from_id' => $senderId,
                'to_id' => $recipientId,
                'subject' => $subject ?? '',
                'message' => $message,
                'created' => $now,
                'hasread' => 0,
                'reply_to' => $replyTo,
            ]);

            DB::table('jcow_messages_sent')->insert([
                'from_id' => $senderId,
                'to_id' => $recipientId,
                'subject' => $subject ?? '',
                'message' => $message,
                'created' => $now,
                'hasread' => 0,
                'reply_to' => $replyTo,
            ]);

            $sender = DB::table('jcow_accounts')->where('id', $senderId)->first();
            $this->notifRepo->create(
                $recipientId,
                'new_message',
                [
                    'user_id' => $senderId,
                    'user_name' => $sender->fullname ?? 'User',
                ]
            );

            return (object) [
                'id' => $messageId,
                'from_id' => $senderId,
                'to_id' => $recipientId,
                'subject' => $subject ?? '',
                'message' => $message,
                'created' => $now,
                'hasread' => 0,
                'reply_to' => $replyTo,
            ];
        });
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return $this->messageRepo->markAsRead($id, $userId);
    }

    public function markConversationAsRead(int $userId, int $otherId): void
    {
        $this->messageRepo->markConversationAsRead($userId, $otherId);
    }

    public function delete(int $id, int $userId, string $type = 'inbox'): bool
    {
        return $this->messageRepo->deleteById($id, $userId, $type);
    }

    public function bulkDelete(array $ids, int $userId, string $type = 'inbox'): int
    {
        return $this->messageRepo->bulkDelete($ids, $userId, $type);
    }

    public function countUnread(int $userId): int
    {
        return $this->messageRepo->countUnread($userId);
    }

    public function search(int $userId, string $keyword, string $type = 'inbox')
    {
        if ($type === 'outbox') {
            return $this->messageRepo->searchOutbox($userId, $keyword);
        }
        return $this->messageRepo->searchInbox($userId, $keyword);
    }

    public function getLastMessage(int $userId, int $otherId): ?object
    {
        return $this->messageRepo->getLastMessage($userId, $otherId);
    }

    public function countUnreadBetween(int $userId, int $otherId): int
    {
        return $this->messageRepo->countUnreadBetween($userId, $otherId);
    }

    public function deleteForSelf(int $messageId, int $userId): bool
    {
        return $this->messageRepo->deleteForSelf($messageId, $userId);
    }

    public function deleteForEveryone(int $messageId): bool
    {
        return $this->messageRepo->deleteForEveryone($messageId);
    }

    public function deleteConversation(int $userId, int $otherId): bool
    {
        return $this->messageRepo->deleteConversation($userId, $otherId);
    }
}
