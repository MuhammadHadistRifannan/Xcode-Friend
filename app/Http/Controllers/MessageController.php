<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Services\FriendService;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService,
        private FriendService $friendService
    ) {}

    private function noCache($response)
    {
        return $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        $friends = $this->friendService->getFriends($userId);

        $conversations = [];
        foreach ($friends as $friend) {
            if ($search && stripos($friend->fullname, $search) === false && stripos($friend->username, $search) === false) {
                continue;
            }

            $lastMessage = $this->messageService->getLastMessage($userId, $friend->id);
            $unreadCount = $this->messageService->countUnreadBetween($userId, $friend->id);

            $conversations[] = [
                'user' => $friend,
                'lastMessage' => $lastMessage,
                'unreadCount' => $unreadCount,
            ];
        }

        usort($conversations, function ($a, $b) {
            $timeA = $a['lastMessage'] ? $a['lastMessage']->created : 0;
            $timeB = $b['lastMessage'] ? $b['lastMessage']->created : 0;
            return $timeB - $timeA;
        });

        return $this->noCache(
            response()->view('messages.index', compact('conversations'))
        );
    }

    public function store(SendMessageRequest $request)
    {
        $userId = Auth::id();
        $recipientId = $request->recipient_id;

        $blocked = DB::table('jcow_blacks')
            ->where('uid', $recipientId)
            ->where('bid', $userId)
            ->exists();

        if ($blocked) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Pengguna ini telah memblokir Anda.')
            );
        }

        $this->messageService->send(
            $userId,
            $recipientId,
            $request->subject,
            $request->message,
            $request->reply_to
        );

        return $this->noCache(
            redirect()->route('messages.conversation', $recipientId)
        );
    }

    public function conversation(int $userId)
    {
        $currentUserId = Auth::id();

        $otherUser = DB::table('jcow_accounts')->where('id', $userId)->first();

        if (!$otherUser) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'User tidak ditemukan.')
            );
        }

        $messages = $this->messageService->getConversation($currentUserId, $userId);

        $this->messageService->markConversationAsRead($currentUserId, $userId);

        return $this->noCache(
            response()->view('messages.conversation', compact('messages', 'otherUser'))
        );
    }

    public function destroy(int $id)
    {
        $userId = Auth::id();

        $message = $this->messageService->getById($id, $userId);

        if (!$message) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
            );
        }

        $this->messageService->deleteForSelf($id, $userId);

        return $this->noCache(
            back()->with('success', 'Pesan berhasil dihapus.')
        );
    }

    public function deleteForEveryone(int $id)
    {
        $userId = Auth::id();

        $message = $this->messageService->getById($id, $userId);

        if (!$message) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
            );
        }

        if ($message->from_id != $userId) {
            return $this->noCache(
                back()->with('error', 'Anda hanya bisa menghapus pesan yang dikirim.')
            );
        }

        $this->messageService->deleteForEveryone($id);

        return $this->noCache(
            back()->with('success', 'Pesan berhasil dihapus untuk semua.')
        );
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer',
        ]);

        $userId = Auth::id();

        foreach ($request->user_ids as $otherId) {
            $this->messageService->deleteConversation($userId, $otherId);
        }

        return $this->noCache(
            redirect()->route('messages.index')
                ->with('success', count($request->user_ids) . ' percakapan berhasil dihapus.')
        );
    }
}
