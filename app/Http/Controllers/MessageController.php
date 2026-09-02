<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
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
        $sort = $request->input('sort', 'terbaru');
        $status = $request->input('status', 'all');

        if ($keyword = $request->input('search')) {
            $messages = $this->messageService->search($userId, $keyword, 'inbox');
        } else {
            $messages = $this->messageService->getInbox($userId, 20, $sort, $status);
        }

        $filters = compact('sort', 'status');

        return $this->noCache(
            response()->view('messages.index', compact('messages', 'filters'))
        );
    }

    public function outbox(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort', 'terbaru');

        if ($keyword = $request->input('search')) {
            $messages = $this->messageService->search($userId, $keyword, 'outbox');
        } else {
            $messages = $this->messageService->getOutbox($userId, 20, $sort);
        }

        $filters = compact('sort');

        return $this->noCache(
            response()->view('messages.outbox', compact('messages', 'filters'))
        );
    }

    public function create(Request $request)
    {
        $userId = Auth::id();
        $toId = $request->input('to');
        $blocked = false;

        if ($toId) {
            $blocked = DB::table('jcow_blacks')
                ->where('uid', $toId)
                ->where('bid', $userId)
                ->exists();
        }

        $users = DB::table('jcow_accounts')
            ->where('id', '!=', $userId)
            ->where('disabled', 0)
            ->select('id', 'fullname', 'username', 'avatar')
            ->orderBy('fullname')
            ->get();

        return $this->noCache(
            response()->view('messages.create', compact('users', 'toId', 'blocked'))
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
                redirect()->route('messages.create')->with('error', 'Pengguna ini telah memblokir Anda.')
            );
        }

        $this->messageService->send(
            $userId,
            $recipientId,
            $request->subject,
            $request->message
        );

        return $this->noCache(
            redirect()->route('messages.outbox')->with('success', 'Pesan berhasil dikirim.')
        );
    }

    public function show(int $id)
    {
        $userId = Auth::id();

        $message = $this->messageService->getById($id, $userId);

        if (!$message) {
            return $this->noCache(
                redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.')
            );
        }

        $type = $message->to_id == $userId ? 'inbox' : 'outbox';

        if ($type === 'inbox') {
            $this->messageService->markAsRead($id, $userId);
            $otherId = $message->from_id;
        } else {
            $otherId = $message->to_id;
        }

        $otherUser = DB::table('jcow_accounts')->where('id', $otherId)->first();

        return $this->noCache(
            response()->view('messages.show', compact('message', 'otherUser', 'type'))
        );
    }

    public function destroy(int $id)
    {
        $userId = Auth::id();

        $this->messageService->delete($id, $userId, 'inbox');

        return $this->noCache(
            redirect()->route('messages.index')->with('success', 'Pesan berhasil dihapus.')
        );
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'type' => 'required|in:inbox,outbox',
        ]);

        $userId = Auth::id();
        $this->messageService->bulkDelete($request->ids, $userId, $request->type);

        $route = $request->type === 'outbox' ? 'messages.outbox' : 'messages.index';

        return $this->noCache(
            redirect()->route($route)->with('success', 'Pesan berhasil dihapus.')
        );
    }
}
