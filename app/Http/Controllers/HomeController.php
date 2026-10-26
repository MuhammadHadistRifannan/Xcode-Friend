<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stream;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Follower;
use App\Models\Page;
use App\Models\Group;
use App\Helpers\ModuleHelper;
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

        $publicStreams = Stream::with(['user', 'comments.user', 'targetPage', 'targetWallUser'])
                            ->where('app', '!=', 'page')
                            ->visibleTo(null)
                            ->orderBy('created', 'desc')
                            ->take(5)
                            ->get();

        return view('home.guest', compact('stats', 'recentLogins', 'publicStreams'));
    }

    /**
     * Menampilkan Beranda/Dashboard untuk User (Sudah Login)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Hitung Follower & Following
        $followingCount = $user->following()->count();
        $followerCount = $user->followers()->count();

        // Ambil Feed Berita (Seluruh unggahan dari semua pengguna secara global, kecuali halaman)
        $streams = Stream::with(['user', 'comments.user', 'targetPage', 'targetWallUser'])
                    ->where('app', '!=', 'page')
                    ->visibleTo($user)
                    ->orderBy('created', 'desc')
                    ->paginate(12);
                    
        if ($request->ajax() || $request->wantsJson()) {
            $html = '';
            foreach ($streams as $stream) {
                $html .= view('components.single-stream', ['stream' => $stream])->render();
            }

            return response()->json([
                'html' => $html,
                'hasMorePages' => $streams->hasMorePages(),
                'nextPageUrl' => $streams->nextPageUrl(),
                'currentPage' => $streams->currentPage()
            ]);
        }

        // Ambil Pages Populer
        $popularPages = collect();
        if (ModuleHelper::isActive('PAGES')) {
            $popularPages = Page::withCount('followers')
                ->orderByDesc('followers_count')
                ->orderByDesc('views')
                ->take(4)
                ->get();
        }

        // Ambil Grup Populer
        $popularGroups = collect();
        if (ModuleHelper::isActive('GROUPS')) {
            $popularGroups = Group::withCount('members')
                ->orderByDesc('members_count')
                ->orderByDesc('views')
                ->take(4)
                ->get();
        }

        // Ambil Member yang sedang online
        $threshold = time() - 120; // 2 menit terakhir
        $onlineQuery = User::where('disabled', 0)
            ->where('last_seen', '>=', $threshold);

        if ($user) {
            $onlineQuery->whereNotIn('id', function($sub) use ($user) {
                $sub->select('bid')->from('jcow_blacks')->where('uid', $user->id);
            })->whereNotIn('id', function($sub) use ($user) {
                $sub->select('uid')->from('jcow_blacks')->where('bid', $user->id);
            });
        }

        $onlineCount = (clone $onlineQuery)->count();
        $onlineMembers = $onlineQuery
            ->orderByDesc('last_seen')
            ->take(100)
            ->get();

        return view('home.beranda', compact(
            'user',
            'followingCount',
            'followerCount',
            'streams',
            'popularPages',
            'popularGroups',
            'onlineMembers',
            'onlineCount'
        ));
    }
}
