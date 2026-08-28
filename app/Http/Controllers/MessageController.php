<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan facade DB

class MessageController extends Controller
{
    // 1. Kotak Masuk (Pesan yang DITERIMA)
    public function index()
    {
        $messages = Message::where('to_id', Auth::id())
                           ->with('sender')
                           ->orderBy('created', 'desc')
                           ->get();
        
        return view('messages.index', compact('messages'));
    }

    // 2. Kotak Keluar (Pesan yang DIKIRIM)
    public function outbox()
    {
        $messages = Message::where('from_id', Auth::id())
                           ->with('receiver')
                           ->orderBy('created', 'desc')
                           ->get();

        return view('messages.outbox', compact('messages'));
    }

    // 3. Menampilkan detail pesan
    public function show(User $user)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($query) use ($authId, $user) {
            $query->where('from_id', $authId)->where('to_id', $user->id);
        })->orWhere(function ($query) use ($authId, $user) {
            $query->where('from_id', $user->id)->where('to_id', $authId);
        })->orderBy('created', 'asc')->get();

        $messageItem = $messages->last(); 

        // Update status baca
        Message::where('from_id', $user->id)
                ->where('to_id', $authId)
                ->where('hasread', 0)
                ->update(['hasread' => 1]);

        return view('messages.show', compact('user', 'messages', 'messageItem'));
    }

    // 4. Menyimpan pesan baru / balasan
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $now = time(); // Menggunakan Unix Timestamp untuk kompatibilitas legacy

        // Simpan ke jcow_messages (diwakili model Message)
        Message::create([
            'from_id' => Auth::id(),
            'to_id' => $user->id,
            'message' => $request->message,
            'subject' => 'Balasan', // Default statis
            'created' => $now,
            'hasread' => 0,
        ]);

        // Simpan duplikat ke jcow_messages_sent agar sinkron dengan sistem lama
        DB::table('jcow_messages_sent')->insert([
            'from_id' => Auth::id(),
            'to_id' => $user->id,
            'message' => $request->message,
            'subject' => 'Balasan',
            'created' => $now,
            'hasread' => 0,
        ]);

        return redirect()->route('messages.outbox');
    }

    // 5. Menghapus seluruh riwayat pesan dengan user tertentu
    public function destroy(User $user)
    {
        $authId = Auth::id();

        // Hapus dari jcow_messages
        Message::where(function ($query) use ($authId, $user) {
            $query->where('from_id', $authId)->where('to_id', $user->id);
        })->orWhere(function ($query) use ($authId, $user) {
            $query->where('from_id', $user->id)->where('to_id', $authId);
        })->delete();

        // Hapus dari jcow_messages_sent
        DB::table('jcow_messages_sent')->where(function ($query) use ($authId, $user) {
            $query->where('from_id', $authId)->where('to_id', $user->id);
        })->orWhere(function ($query) use ($authId, $user) {
            $query->where('from_id', $user->id)->where('to_id', $authId);
        })->delete();

        return redirect()->route('messages.index');
    }
}