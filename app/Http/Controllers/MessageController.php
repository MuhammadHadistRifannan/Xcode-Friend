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

    public function index(Request $request)
    {
        $userId = Auth::id();

        if ($keyword = $request->input('search')) {
            $messages = $this->messageService->search($userId, $keyword, 'inbox');
        } else {
            $messages = $this->messageService->getInbox($userId);
        }

        return view('messages.index', compact('messages'));
    }

    public function outbox(Request $request)
    {
        $userId = Auth::id();

        if ($keyword = $request->input('search')) {
            $messages = $this->messageService->search($userId, $keyword, 'outbox');
        } else {
            $messages = $this->messageService->getOutbox($userId);
        }

        return view('messages.outbox', compact('messages'));
    }

    public function create(Request $request)
    {
        $users = DB::table('jcow_accounts')
            ->where('id', '!=', Auth::id())
            ->where('disabled', 0)
            ->select('id', 'fullname', 'username', 'avatar')
            ->orderBy('fullname')
            ->get();

        $toId = $request->input('to');

        return view('messages.create', compact('users', 'toId'));
    }

    public function store(SendMessageRequest $request)
    {
        $this->messageService->send(
            Auth::id(),
            $request->recipient_id,
            $request->subject,
            $request->message
        );

        return redirect()->route('messages.outbox')->with('success', 'Pesan berhasil dikirim.');
    }

    public function show(int $id)
    {
        $userId = Auth::id();

        $message = $this->messageService->getById($id, $userId);

        if (!$message) {
            return redirect()->route('messages.index')->with('error', 'Pesan tidak ditemukan.');
        }

        $type = $message->to_id == $userId ? 'inbox' : 'outbox';

        if ($type === 'inbox') {
            $this->messageService->markAsRead($id, $userId);
            $otherId = $message->from_id;
        } else {
            $otherId = $message->to_id;
        }

        $otherUser = DB::table('jcow_accounts')->where('id', $otherId)->first();

        return view('messages.show', compact('message', 'otherUser', 'type'));
    }

    public function destroy(int $id)
    {
        $userId = Auth::id();

        $this->messageService->delete($id, $userId, 'inbox');

        return redirect()->route('messages.index')->with('success', 'Pesan berhasil dihapus.');
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

        return redirect()->route($route)->with('success', 'Pesan berhasil dihapus.');
    }
}
