<?php

namespace App\Http\Controllers;

use App\Services\FriendService;
use App\Http\Requests\SendFriendRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function __construct(
        private FriendService $friendService
    ) {}

    public function index()
    {
        $userId = Auth::id();
        $friends = $this->friendService->getFriends($userId);
        $suggestions = $this->friendService->getSuggestions($userId, 10);

        return view('friends.index', compact('friends', 'suggestions'));
    }

    public function requests()
    {
        $userId = Auth::id();
        $incoming = $this->friendService->getIncomingRequests($userId);
        $outgoing = $this->friendService->getOutgoingRequests($userId);

        return view('friends.requests', compact('incoming', 'outgoing'));
    }

    public function sendRequest(SendFriendRequestRequest $request)
    {
        try {
            $this->friendService->sendRequest(
                Auth::id(),
                $request->uid,
                $request->msg
            );

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function accept(int $userId)
    {
        try {
            $this->friendService->acceptRequest($userId, Auth::id());

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan diterima.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(int $userId)
    {
        try {
            $this->friendService->rejectRequest($userId, Auth::id());

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unfriend(int $userId)
    {
        try {
            $this->friendService->unfriend($userId, Auth::id());

            return redirect()->route('friends.index')->with('success', 'Pertemanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
