<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Services\FriendService;
use App\Http\Requests\SendMessageRequest;
use App\Http\Traits\NoCache;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\BlockRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use NoCache;

    public function __construct(
        private MessageService $messageService,
        private FriendService $friendService,
        private AccountRepositoryInterface $accountRepo,
        private BlockRepositoryInterface $blockRepo
    ) {}

    public function index(Request $request): mixed
    {
        try {
            $userId = Auth::id();
            $search = trim($request->input('search') ?? '');

            $friends = $this->friendService->getFriends($userId);

            $friendIds = $friends->pluck('id')->toArray();
            $lastMessages = $this->messageService->getLastMessagesForFriends($userId, $friendIds);
            $unreadCounts = $this->messageService->countUnreadBetweenMultiple($userId, $friendIds);

            $conversations = [];
            foreach ($friends as $friend) {
                if ($search && stripos($friend->fullname, $search) === false && stripos($friend->username, $search) === false) {
                    continue;
                }

                $conversations[] = [
                    'user' => $friend,
                    'lastMessage' => $lastMessages[$friend->id] ?? null,
                    'unreadCount' => $unreadCounts[$friend->id] ?? 0,
                ];
            }

            usort($conversations, function ($a, $b) {
                $timeA = $a['lastMessage'] ? $a['lastMessage']->created : 0;
                $timeB = $b['lastMessage'] ? $b['lastMessage']->created : 0;
                return $timeB - $timeA;
            });

            return response()->view('messages.index', compact('conversations'))
                ->header('Cache-Control', 'private, max-age=10');
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Gagal memuat daftar pesan.')
            );
        }
    }

    public function store(SendMessageRequest $request): mixed
    {
        try {
            $userId = Auth::id();
            $recipientId = $request->recipient_id;

            $blocked = $this->blockRepo->isBlockedByRecipient($recipientId, $userId);

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
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->back()->with('error', 'Gagal mengirim pesan. Coba lagi.')
            );
        }
    }

    public function conversation(int $userId): mixed
    {
        try {
            $currentUserId = Auth::id();

            if ($currentUserId === $userId) {
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Tidak bisa membuka percakapan dengan diri sendiri.')
                );
            }

            $otherUser = $this->accountRepo->findById($userId);

            if (!$otherUser) {
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Pengguna tidak ditemukan.')
                );
            }

            $areBlocked = $this->friendService->areBlocked($currentUserId, $userId);
            if ($areBlocked) {
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Percakapan tidak tersedia.')
                );
            }

            $messages = $this->messageService->getConversation($currentUserId, $userId);

            $this->messageService->markConversationAsRead($currentUserId, $userId);

            return response()->view('messages.conversation', compact('messages', 'otherUser'))
                ->header('Cache-Control', 'private, max-age=30');
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Gagal memuat percakapan.')
            );
        }
    }

    public function poll(int $userId): mixed
    {
        try {
            $currentUserId = Auth::id();

            $this->messageService->markConversationAsRead($currentUserId, $userId);

            $messages = $this->messageService->getConversation($currentUserId, $userId);

            $html = '';
            foreach ($messages as $msg) {
                $isMine = $msg->from_id == $currentUserId;
                $time = \Carbon\Carbon::createFromTimestamp($msg->created)->format('H:i');
                $readCheck = ($isMine && $msg->hasread) ? '<span class="text-[10px] text-[#b71c1c]">&#10003;&#10003;</span>' : '';
                $replyBlock = '';
                if ($msg->reply_to && $msg->replied_message) {
                    $replySenderClass = $isMine ? 'text-white/90' : 'text-[#b71c1c]';
                    $replyBg = $isMine ? 'bg-white/15' : 'bg-gray-50';
                    $replyBorder = $isMine ? 'border-white/40' : 'border-[#b71c1c]';
                    $replyTextClass = $isMine ? 'text-white/70' : 'text-gray-500';
                    $replyBlock = '<div class="mb-2 ' . $replyBg . ' rounded-lg px-3 py-2 border-l-[3px] ' . $replyBorder . '">'
                        . '<p class="text-[10px] font-bold ' . $replySenderClass . '">' . e($msg->replied_sender_name) . '</p>'
                        . '<p class="text-[10px] ' . $replyTextClass . ' truncate">' . e(\Illuminate\Support\Str::limit($msg->replied_message, 80)) . '</p>'
                        . '</div>';
                }
                $bubbleClass = $isMine ? 'bg-[#b71c1c] text-white' : 'bg-white text-gray-900';
                $justify = $isMine ? 'justify-end' : 'justify-start';
                $timeAlign = $isMine ? 'justify-end' : 'justify-start';

                $html .= '<div class="flex ' . $justify . ' mb-2">'
                    . '<div class="max-w-[70%]">'
                    . '<div class="chat-bubble ' . $bubbleClass . ' rounded-[14px] px-4 py-3 shadow-sm cursor-pointer select-none"'
                    . ' data-id="' . $msg->id . '"'
                    . ' data-from="' . $msg->from_id . '"'
                    . ' data-message="' . e($msg->message) . '"'
                    . ' onclick="showContextMenu(event, this)">'
                    . $replyBlock
                    . '<p class="text-sm whitespace-pre-wrap">' . e($msg->message) . '</p>'
                    . '</div>'
                    . '<div class="flex items-center gap-2 mt-1 ' . $timeAlign . '">'
                    . '<p class="text-[10px] text-gray-400">' . $time . '</p>'
                    . $readCheck
                    . '</div>'
                    . '</div>'
                    . '</div>';
            }

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['html' => '']);
        }
    }

    public function destroy(int $id): mixed
    {
        try {
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
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->back()->with('error', 'Gagal menghapus pesan.')
            );
        }
    }

    public function deleteForEveryone(int $id): mixed
    {
        try {
            $userId = Auth::id();

            $message = $this->messageService->getById($id, $userId);

            if (!$message) {
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
                );
            }

            if ($message->from_id !== $userId) {
                return $this->noCache(
                    back()->with('error', 'Anda hanya bisa menghapus pesan yang dikirim.')
                );
            }

            $this->messageService->deleteForEveryone($id, $userId);

            return $this->noCache(
                back()->with('success', 'Pesan berhasil dihapus untuk semua.')
            );
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->back()->with('error', 'Gagal menghapus pesan.')
            );
        }
    }

    public function bulkDelete(Request $request): mixed
    {
        try {
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
        } catch (\Exception $e) {
            return $this->noCache(
                redirect()->back()->with('error', 'Gagal menghapus percakapan.')
            );
        }
    }
}
