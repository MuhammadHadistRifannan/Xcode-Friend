<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stream;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Follower;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan Beranda untuk Guest (Belum Login)
     */
    public function guest()
    {
        // Jika user ternyata sudah login, langsung lempar ke Dashboard
        if (auth()->check()) {
            return redirect('/beranda');
        }

        // Ambil Data Statistik Jaringan
        $stats = [
            'activities' => Stream::count(),
            'members' => User::count(),
            'friendships' => Friend::count(),
            'comments' => Comment::count(),
        ];

        // Ambil 4 user terakhir yang login (punya avatar)
        $recentLogins = User::where('avatar', '!=', '')
                            ->orderBy('lastlogin', 'desc')
                            ->take(4)
                            ->get();

        // Ambil Feed Komunitas (Global) - Tidak menampilkan unggahan halaman
        $publicStreams = Stream::with(['user', 'comments.user', 'targetPage', 'targetWallUser'])
                            ->where('app', '!=', 'page')
                            ->orderBy('created', 'desc')
                            ->take(5)
                            ->get();

        return view('home.guest', compact('stats', 'recentLogins', 'publicStreams'));
    }

    /**
     * Menampilkan Beranda/Dashboard untuk User (Sudah Login)
     */
    public function index()
    {
        $user = auth()->user();

        // Hitung Follower & Following
        $followingCount = $user->following()->count();
        $followerCount = $user->followers()->count();

        // Ambil Feed Berita (Seluruh unggahan dari semua pengguna secara global, kecuali halaman)
        $streams = Stream::with(['user', 'comments.user', 'targetPage', 'targetWallUser'])
                    ->where('app', '!=', 'page')
                    ->orderBy('created', 'desc')
                    ->paginate(12);

        return view('home.beranda', compact('user', 'followingCount', 'followerCount', 'streams'));
    }
}
