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

    public function getInbox(int $userId, int $perPage = 20)
    {
        return $this->messageRepo->getInbox($userId, $perPage);
    }

    public function getOutbox(int $userId, int $perPage = 20)
    {
        return $this->messageRepo->getOutbox($userId, $perPage);
    }

    public function getConversation(int $userId, int $otherId)
    {
        return $this->messageRepo->getConversation($userId, $otherId);
    }

    public function getById(int $id, int $userId)
    {
        return $this->messageRepo->getById($id, $userId);
    }

    public function send(int $senderId, int $recipientId, ?string $subject, string $message): object
    {
        $now = time();

        // Insert ke jcow_messages (inbox penerima)
        $messageId = DB::table('jcow_messages')->insertGetId([
            'from_id' => $senderId,
            'to_id' => $recipientId,
            'subject' => $subject ?? '',
            'message' => $message,
            'created' => $now,
            'hasread' => 0,
        ]);

        // Insert ke jcow_messages_sent (outbox pengirim)
        DB::table('jcow_messages_sent')->insert([
            'from_id' => $senderId,
            'to_id' => $recipientId,
            'subject' => $subject ?? '',
            'message' => $message,
            'created' => $now,
            'hasread' => 0,
        ]);

        // Kirim notifikasi ke penerima
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
        ];
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
}
