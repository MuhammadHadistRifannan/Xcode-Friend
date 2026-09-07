<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Http\Traits\NoCache;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use NoCache;

    public function __construct(
        private NotificationService $notifService,
        private AccountRepositoryInterface $accountRepo
    ) {}

    public function index(): mixed
    {
        try {
            $userId = Auth::id();
            $notifications = $this->notifService->getNotifications($userId);

            return $this->noCache(
                response()->view('notifications.index', compact('notifications'))
            );
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('notifications.index')->with('error', 'Gagal memuat notifikasi.')
            );
        }
    }

    public function markRead(int $id): mixed
    {
        try {
            $userId = Auth::id();
            $notification = $this->notifService->getById($id, $userId);

            if ($notification) {
                $this->notifService->markAsRead($id, $userId);

                $username = $this->extractUsername($notification->message);
                $targetUser = $username ? $this->accountRepo->findByUsername($username) : null;

                $redirect = match ($notification->subject) {
                    'new_message' => $targetUser ? route('messages.conversation', $targetUser->id) : route('messages.index'),
                    'friend_request' => route('friends.requests'),
                    'friend_accepted' => route('friends.index'),
                    'comment', 'like' => route('beranda'),
                    default => route('notifications.index'),
                };

                return $this->noCache(redirect($redirect));
            }

            return $this->noCache(redirect()->route('notifications.index'));
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('notifications.index')->with('error', 'Gagal memproses notifikasi.')
            );
        }
    }

    private function extractUsername(string $message): ?string
    {
        if (preg_match('/href="\/@([^"]+)"/', $message, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function markAllRead(): mixed
    {
        try {
            $userId = Auth::id();
            $this->notifService->markAllAsRead($userId);

            return $this->noCache(
                redirect()->route('notifications.index')->with('success', 'Semua notifikasi ditandai sudah dibaca.')
            );
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('notifications.index')->with('error', 'Gagal menandai notifikasi.')
            );
        }
    }

    public function destroy(int $id): mixed
    {
        try {
            $userId = Auth::id();
            $this->notifService->delete($id, $userId);

            return $this->noCache(
                redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.')
            );
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('notifications.index')->with('error', 'Gagal menghapus notifikasi.')
            );
        }
    }

    public function unreadCount(): mixed
    {
        try {
            $userId = Auth::id();
            $count = $this->notifService->countUnread($userId);

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }
}
