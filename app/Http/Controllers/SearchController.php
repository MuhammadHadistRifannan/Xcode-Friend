<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Page;
use App\Models\Stream;
use App\Models\Photo;
use App\Models\Album;

class SearchController extends Controller
{
    // Daftar tab filter yang valid
    const TABS = ['semua', 'anggota', 'grup', 'halaman', 'postingan', 'foto'];

    /**
     * Halaman hasil pencarian global dengan tab filter.
     */
    public function index(Request $request)
    {
        $q   = trim($request->input('q', ''));
        $tab = in_array($request->input('tab'), self::TABS)
               ? $request->input('tab')
               : 'semua';

        $users   = collect();
        $groups  = collect();
        $pages   = collect();
        $streams = collect();
        $photos  = collect();

        $counts = ['anggota' => 0, 'grup' => 0, 'halaman' => 0, 'postingan' => 0, 'foto' => 0];

        if (strlen($q) >= 2) {

            // ------ ANGGOTA ------
            if ($tab === 'semua' || $tab === 'anggota') {
                $users = User::where(function ($query) use ($q) {
                    $query->where('username', 'like', "%{$q}%")
                          ->orWhere('fullname',  'like', "%{$q}%");
                })->limit($tab === 'semua' ? 8 : 40)->get();
            }

            // ------ GRUP ------
            if ($tab === 'semua' || $tab === 'grup') {
                $groups = Group::where(function ($query) use ($q) {
                    $query->where('name',        'like', "%{$q}%")
                          ->orWhere('description','like', "%{$q}%");
                })->limit($tab === 'semua' ? 6 : 30)->get();
            }

            // ------ HALAMAN / PAGES ------
            if ($tab === 'semua' || $tab === 'halaman') {
                $pages = Page::where(function ($query) use ($q) {
                    $query->where('name',        'like', "%{$q}%")
                          ->orWhere('description','like', "%{$q}%");
                })->limit($tab === 'semua' ? 6 : 30)->get();
            }

            // ------ POSTINGAN ------
            if ($tab === 'semua' || $tab === 'postingan') {
                $streams = Stream::with('user')
                    ->whereNotNull('message')
                    ->where('message', '!=', '')
                    ->where('message', 'like', "%{$q}%")
                    ->where('app', 'feed')
                    ->visibleTo(auth()->user())
                    ->orderBy('created', 'desc')
                    ->limit($tab === 'semua' ? 8 : 30)
                    ->get();
            }

            // ------ FOTO ------
            if ($tab === 'semua' || $tab === 'foto') {
                $photos = Photo::where('des', 'like', "%{$q}%")
                    ->with('album')
                    ->limit($tab === 'semua' ? 12 : 40)
                    ->get();
            }

            // Hitung total untuk badge tiap tab (selalu hitung semua)
            $counts = [
                'anggota'   => User::where(function ($q2) use ($q) {
                                    $q2->where('username','like',"%{$q}%")->orWhere('fullname','like',"%{$q}%");
                                })->count(),
                'grup'      => Group::where(function ($q2) use ($q) {
                                    $q2->where('name','like',"%{$q}%")->orWhere('description','like',"%{$q}%");
                                })->count(),
                'halaman'   => Page::where(function ($q2) use ($q) {
                                    $q2->where('name','like',"%{$q}%")->orWhere('description','like',"%{$q}%");
                                })->count(),
                'postingan' => Stream::whereNotNull('message')->where('message','!=','')
                                ->where('message','like',"%{$q}%")->where('app','feed')->visibleTo(auth()->user())->count(),
                'foto'      => Photo::where('des','like',"%{$q}%")->count(),
            ];

            $counts['semua'] = array_sum($counts);
        }

        return view('search.index', compact('q', 'tab', 'users', 'groups', 'pages', 'streams', 'photos', 'counts'));
    }

    /**
     * Endpoint AJAX untuk autocomplete dropdown di navbar.
     */
    public function autocomplete(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['users' => [], 'groups' => [], 'pages' => []]);
        }

        $users = User::where(function ($query) use ($q) {
                $query->where('username', 'like', "%{$q}%")
                      ->orWhere('fullname', 'like', "%{$q}%");
            })
            ->select('id', 'username', 'fullname', 'avatar')
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->fullname ?: $u->username,
                'username' => $u->username,
                'avatar'   => $u->avatar_url,
                'url'      => route('profile.show', $u->username),
                'type'     => 'user',
            ]);

        $groups = Group::where('name', 'like', "%{$q}%")
            ->select('id', 'name', 'uri', 'logo')
            ->limit(3)
            ->get()
            ->map(fn($g) => [
                'id'   => $g->id,
                'name' => $g->name,
                'logo' => $g->logo_url,
                'url'  => route('groups.show', $g->id),
                'type' => 'group',
            ]);

        $pages = Page::where('name', 'like', "%{$q}%")
            ->select('id', 'name', 'uri', 'logo')
            ->limit(3)
            ->get()
            ->map(fn($p) => [
                'id'   => $p->id,
                'name' => $p->name,
                'logo' => $p->logo_url,
                'url'  => route('pages.show', $p->id),
                'type' => 'page',
            ]);

        return response()->json([
            'users'  => $users,
            'groups' => $groups,
            'pages'  => $pages,
        ]);
    }
}
