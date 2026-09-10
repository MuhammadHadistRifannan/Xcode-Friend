<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Events\NotificationCreated;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function __construct(
        private MessageRepositoryInterface $messageRepo,
        private NotificationRepositoryInterface $notifRepo,
        private AccountRepositoryInterface $accountRepo,
        private SpamService $spamService
    ) {}

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
        $isSpam = $this->spamService->recordThisPosting($senderId, $message);
        if ($isSpam) {
            return (object) [
                'id' => 0,
                'from_id' => $senderId,
                'to_id' => $recipientId,
                'subject' => $subject ?? '',
                'message' => '',
                'created' => time(),
                'hasread' => 0,
                'reply_to' => $replyTo,
                'spam_detected' => true,
            ];
        }

        $result = DB::transaction(function () use ($senderId, $recipientId, $subject, $message, $replyTo) {
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
                'source_id' => $messageId,
                'from_id' => $senderId,
                'to_id' => $recipientId,
                'subject' => $subject ?? '',
                'message' => $message,
                'created' => $now,
                'hasread' => 0,
                'reply_to' => $replyTo,
            ]);

            $sender = $this->accountRepo->findById($senderId);
            $this->notifRepo->create(
                $recipientId,
                'new_message',
                [
                    'user_id' => $senderId,
                    'user_name' => $sender->username ?? 'user',
                    'display_name' => $sender->fullname ?? 'User',
                ]
            );

            Cache::forget('unread:msg:' . $recipientId);
            Cache::forget('unread:notif:' . $recipientId);

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

        $sender = $this->accountRepo->findById($senderId);
        $recipientUnread = $this->messageRepo->countUnread($recipientId);
        $recipientNotifCount = $this->notifRepo->countUnread($recipientId);

        try {
            broadcast(new MessageSent($result, $sender, $recipientId, $recipientUnread))->toOthers();
            broadcast(new NotificationCreated((object)[
                'id' => 0,
                'subject' => 'new_message',
                'message' => "Pesan baru dari {$sender->fullname}",
                'created' => time(),
            ], $recipientId, $recipientNotifCount))->toOthers();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast MessageSent gagal: ' . $e->getMessage());
        }

        return $result;
    }

    public function markConversationAsRead(int $userId, int $otherId): void
    {
        $this->messageRepo->markConversationAsRead($userId, $otherId);
        Cache::forget('unread:msg:' . $userId);

        try {
            broadcast(new MessageRead($userId, $otherId))->toOthers();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast MessageRead gagal: ' . $e->getMessage());
        }
    }

    public function countUnread(int $userId): int
    {
        return $this->messageRepo->countUnread($userId);
    }

    public function getLastMessage(int $userId, int $otherId): ?object
    {
        return $this->messageRepo->getLastMessage($userId, $otherId);
    }

    public function countUnreadBetween(int $userId, int $otherId): int
    {
        return $this->messageRepo->countUnreadBetween($userId, $otherId);
    }

    public function getLastMessagesForFriends(int $userId, array $friendIds): array
    {
        return $this->messageRepo->getLastMessagesForFriends($userId, $friendIds);
    }

    public function countUnreadBetweenMultiple(int $userId, array $friendIds): array
    {
        return $this->messageRepo->countUnreadBetweenMultiple($userId, $friendIds);
    }

    public function deleteForSelf(int $messageId, int $userId): bool
    {
        return $this->messageRepo->deleteForSelf($messageId, $userId);
    }

    public function deleteForEveryone(int $messageId, int $userId): bool
    {
        return $this->messageRepo->deleteForEveryone($messageId, $userId);
    }

    public function deleteConversation(int $userId, int $otherId): bool
    {
        return $this->messageRepo->deleteConversation($userId, $otherId);
    }
}
