<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // 1. Kotak Masuk (Pesan yang DITERIMA oleh user yang sedang login)
    public function index()
    {
        $messages = Message::where('receiver_id', Auth::id())
                           ->with('sender')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('messages.index', compact('messages'));
    }

    // 2. Kotak Keluar (Pesan yang DIKIRIM oleh user yang sedang login)
    public function outbox()
    {
        $messages = Message::where('sender_id', Auth::id())
                           ->with('receiver')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('messages.outbox', compact('messages'));
    }

    // 3. Menampilkan detail pesan
    public function show(User $user)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($query) use ($authId, $user) {
            $query->where('sender_id', $authId)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($authId, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        $messageItem = $messages->last(); 

        Message::where('sender_id', $user->id)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages', 'messageItem'));
    }

    // 4. Menyimpan pesan baru / balasan
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        // Setelah kirim, arahkan langsung ke Kotak Keluar agar kelihatan hasilnya!
        return redirect()->route('messages.outbox');
    }
}