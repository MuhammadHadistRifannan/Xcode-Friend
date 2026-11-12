<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Services\FriendService;
use App\Services\UnreadCounter;
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
        private BlockRepositoryInterface $blockRepo,
        private UnreadCounter $unreadCounter
    ) {}

    public function index(Request $request): mixed
    {
        try {
            $userId = Auth::id();
            $search = trim($request->input('search') ?? '');

            $friends = $this->friendService->getFriends($userId);
            $friendIds = $friends->pluck('id')->toArray();
            
            // Get IDs of everyone we have exchanged messages with
            $chattedUserIds = $this->messageService->getActiveConversationUserIds($userId);
            
            // Combine both lists (friends + non-friends we chatted with)
            $allParticipantIds = array_unique(array_merge($friendIds, $chattedUserIds));
            // Remove self just in case
            $allParticipantIds = array_filter($allParticipantIds, fn($id) => $id != $userId);
            
            // Fetch the user models for non-friends
            $nonFriendIds = array_diff($allParticipantIds, $friendIds);
            $nonFriends = count($nonFriendIds) > 0 ? \App\Models\User::whereIn('id', $nonFriendIds)->get() : collect([]);
            
            $allParticipants = $friends->concat($nonFriends);

            $lastMessages = $this->messageService->getLastMessagesForFriends($userId, $allParticipantIds);
            $unreadCounts = $this->messageService->countUnreadBetweenMultiple($userId, $allParticipantIds);

            $conversations = [];
            foreach ($allParticipants as $participant) {
                if ($search && stripos($participant->fullname, $search) === false && stripos($participant->username, $search) === false) {
                    continue;
                }
                
                $lastMsg = $lastMessages[$participant->id] ?? null;
                
                // If they are not a friend, they must have at least one message to be shown
                if (!in_array($participant->id, $friendIds) && !$lastMsg) {
                    continue;
                }

                $conversations[] = [
                    'user' => $participant,
                    'lastMessage' => $lastMsg,
                    'unreadCount' => $unreadCounts[$participant->id] ?? 0,
                ];
            }

            usort($conversations, function ($a, $b) {
                $timeA = $a['lastMessage'] ? $a['lastMessage']->created : 0;
                $timeB = $b['lastMessage'] ? $b['lastMessage']->created : 0;
                return $timeB - $timeA;
            });

            if ($request->ajax()) {
                $preview = array_slice(array_map(function ($c) {
                    return [
                        'user' => [
                            'id' => $c['user']->id,
                            'name' => $c['user']->fullname ?? $c['user']->username,
                            'username' => $c['user']->username,
                            'avatar_url' => \App\Models\User::avatarUrl($c['user']->avatar ?? null),
                            'initial' => strtoupper(substr($c['user']->fullname ?? $c['user']->username ?? 'U', 0, 1)),
                        ],
                        'last_message' => $c['lastMessage'] ? [
                            'message' => !empty($c['lastMessage']->message) ? $c['lastMessage']->message : (!empty($c['lastMessage']->attachment) ? '📷 Foto' : ''),
                            'time' => \Carbon\Carbon::createFromTimestamp($c['lastMessage']->created)->diffForHumans(),
                            'is_mine' => $c['lastMessage']->from_id == auth()->id(),
                        ] : null,
                        'unread' => (int) $c['unreadCount'],
                        'url' => route('messages.conversation', $c['user']->id),
                    ];
                }, $conversations), 0, 8);

                return response()->json([
                    'conversations' => $preview,
                    'total_unread' => (int) $this->unreadCounter->messageCount($userId),
                ]);
            }

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

            $blocked = $this->friendService->areBlocked($userId, $recipientId);

            if ($blocked) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Pengguna ini telah memblokir Anda.'], 403);
                }
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Pengguna ini telah memblokir Anda.')
                );
            }

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat_attachments', 'public');
            }

            $result = $this->messageService->send(
                $userId,
                $recipientId,
                $request->subject,
                $request->message,
                $request->reply_to,
                $attachmentPath
            );

            if (isset($result->spam_detected) && $result->spam_detected) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Pesan Anda terdeteksi sebagai spam dan tidak terkirim.'], 422);
                }
                return $this->noCache(
                    redirect()->route('messages.conversation', $recipientId)->with('error', 'Pesan Anda terdeteksi sebagai spam dan tidak terkirim.')
                );
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => [
                        'id' => $result->id ?? 0,
                        'message' => $request->message ?? '',
                        'attachment' => $result->attachment ?? null,
                        'attachment_url' => $result->attachment_url ?? null,
                        'created' => time(),
                        'created_formatted' => date('H:i'),
                        'from_id' => $userId,
                        'to_id' => (int) $recipientId,
                        'reply_to' => $result->reply_to ?? null,
                        'replied_message' => $result->replied_message ?? null,
                        'replied_attachment' => $result->replied_attachment ?? null,
                        'replied_sender_name' => $result->replied_sender_name ?? null,
                    ]
                ]);
            }

            return $this->noCache(
                redirect()->route('messages.conversation', $recipientId)
            );
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal mengirim pesan: ' . $e->getMessage()], 500);
            }
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

            $this->messageService->markConversationAsRead($currentUserId, $userId);

            $messages = $this->messageService->getConversation($currentUserId, $userId);

            return response()->view('messages.conversation', compact('messages', 'otherUser'))
                ->header('Cache-Control', 'private, max-age=30');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('conversation() error: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Gagal memuat percakapan.')
            );
        }
    }

    public function markAsRead(int $userId): mixed
    {
        try {
            $currentUserId = Auth::id();
            $this->messageService->markConversationAsRead($currentUserId, $userId);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function updatePresence(Request $request): mixed
    {
        try {
            $userId = Auth::id();
            $status = $request->input('status', 'online');

            if ($status === 'offline') {
                $this->accountRepo->updateLastSeen($userId, 0);
            } else {
                $this->accountRepo->updateLastSeen($userId, time());
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function destroy(Request $request, int $id): mixed
    {
        try {
            $userId = Auth::id();

            $message = $this->messageService->getById($id, $userId);

            if (!$message) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Pesan tidak ditemukan.'], 404);
                }
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
                );
            }

            $this->messageService->deleteForSelf($id, $userId);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message_id' => $id]);
            }

            return $this->noCache(
                back()->with('success', 'Pesan berhasil dihapus.')
            );
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal menghapus pesan.'], 500);
            }
            return $this->noCache(
                redirect()->back()->with('error', 'Gagal menghapus pesan.')
            );
        }
    }

    public function unreadCount(): mixed
    {
        try {
            $userId = Auth::id();
            $count = $this->unreadCounter->messageCount($userId);

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }

    public function unreadCounts(Request $request): mixed
    {
        try {
            $userId = Auth::id();
            $ids = array_filter(array_map('intval', explode(',', $request->query('user_ids', ''))));

            if (empty($ids)) {
                return response()->json([]);
            }

            return response()->json($this->unreadCounter->messageCountByConversation($userId, $ids));
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function deleteForEveryone(Request $request, $id): mixed
    {
        try {
            $userId = Auth::id();

            $message = $this->messageService->getById($id, $userId);

            if (!$message) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Pesan tidak ditemukan.'], 404);
                }
                return $this->noCache(
                    redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
                );
            }

            if ((int) $message->from_id !== (int) $userId) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Anda hanya bisa menghapus pesan yang dikirim.'], 403);
                }
                return $this->noCache(
                    back()->with('error', 'Anda hanya bisa menghapus pesan yang dikirim.')
                );
            }

            $this->messageService->deleteForEveryone((int) $id, $userId);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message_id' => (int) $id]);
            }

            return $this->noCache(
                back()->with('success', 'Pesan berhasil dihapus untuk semua.')
            );
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal menghapus pesan.'], 500);
            }
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
