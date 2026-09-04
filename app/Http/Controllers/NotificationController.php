<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notifService
    ) {}

    private function noCache($response)
    {
        return $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function index()
    {
        $userId = Auth::id();
        $notifications = $this->notifService->getNotifications($userId);

        return $this->noCache(
            response()->view('notifications.index', compact('notifications'))
        );
    }

    public function markRead(int $id)
    {
        $userId = Auth::id();
        $notification = $this->notifService->getById($id, $userId);

        if ($notification) {
            $this->notifService->markAsRead($id, $userId);

            $redirectMap = [
                'friend_request' => 'friends.requests',
                'friend_accepted' => 'friends.index',
                'new_message' => 'messages.index',
                'comment' => 'beranda',
                'like' => 'beranda',
            ];

            $route = $redirectMap[$notification->subject] ?? 'notifications.index';

            return $this->noCache(redirect()->route($route));
        }

        return $this->noCache(redirect()->route('notifications.index'));
    }

    public function markAllRead()
    {
        $userId = Auth::id();
        $this->notifService->markAllAsRead($userId);

        return $this->noCache(
            redirect()->route('notifications.index')->with('success', 'Semua notifikasi ditandai sudah dibaca.')
        );
    }

    public function destroy(int $id)
    {
        $userId = Auth::id();
        $this->notifService->delete($id, $userId);

        return $this->noCache(
            redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.')
        );
    }

    public function unreadCount()
    {
        $userId = Auth::id();
        $count = $this->notifService->countUnread($userId);

        return response()->json(['count' => $count]);
    }
}
