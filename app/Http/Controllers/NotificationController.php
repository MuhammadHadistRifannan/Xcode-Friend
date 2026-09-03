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

    public function index()
    {
        $userId = Auth::id();
        $notifications = $this->notifService->getNotifications($userId);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(int $id)
    {
        $userId = Auth::id();
        $this->notifService->markAsRead($id, $userId);

        return redirect()->route('notifications.index');
    }

    public function markAllRead()
    {
        $userId = Auth::id();
        $this->notifService->markAllAsRead($userId);

        return redirect()->route('notifications.index')->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(int $id)
    {
        $userId = Auth::id();
        $this->notifService->delete($id, $userId);

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function unreadCount()
    {
        $userId = Auth::id();
        $count = $this->notifService->countUnread($userId);

        return response()->json(['count' => $count]);
    }
}
