<?php

namespace App\Http\Controllers;

use App\Services\FriendService;
use App\Http\Requests\SendFriendRequestRequest;
use App\Exceptions\FriendException;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function __construct(
        private FriendService $friendService
    ) {}

    public function index(): mixed
    {
        $userId = Auth::id();
        $friends = $this->friendService->getFriends($userId);
        $suggestions = $this->friendService->getSuggestions($userId, 10);

        return view('friends.index', compact('friends', 'suggestions'));
    }

    public function requests(): mixed
    {
        $userId = Auth::id();
        $incoming = $this->friendService->getIncomingRequests($userId);
        $outgoing = $this->friendService->getOutgoingRequests($userId);

        return view('friends.requests', compact('incoming', 'outgoing'));
    }

    public function sendRequest(SendFriendRequestRequest $request): mixed
    {
        try {
            $this->friendService->sendRequest(
                Auth::id(),
                $request->uid,
                $request->msg
            );

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan berhasil dikirim.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function accept(int $userId): mixed
    {
        try {
            $this->friendService->acceptRequest($userId, Auth::id());

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan diterima.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject(int $userId): mixed
    {
        try {
            $this->friendService->rejectRequest($userId, Auth::id());

            return redirect()->route('friends.requests')->with('success', 'Permintaan pertemanan ditolak.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancelRequest(int $userId): mixed
    {
        try {
            $this->friendService->cancelRequest(Auth::id(), $userId);

            return back()->with('success', 'Permintaan pertemanan dibatalkan.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unfriend(int $userId): mixed
    {
        try {
            $this->friendService->unfriend($userId, Auth::id());

            return back()->with('success', 'Pertemanan berhasil dihapus.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function follow(int $userId): mixed
    {
        try {
            $this->friendService->follow(Auth::id(), $userId);
            return redirect()->back()->with('success', 'Berhasil mengikuti user ini.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unfollow(int $userId): mixed
    {
        try {
            $this->friendService->unfollow(Auth::id(), $userId);
            return redirect()->back()->with('success', 'Berhenti mengikuti user ini.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function block(int $userId): mixed
    {
        try {
            $this->friendService->block(Auth::id(), $userId);
            return back()->with('success', 'User berhasil diblokir.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unblock(int $userId): mixed
    {
        try {
            $this->friendService->unblock(Auth::id(), $userId);
            return redirect()->back()->with('success', 'User berhasil dibuka blokirannya.');
        } catch (FriendException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
