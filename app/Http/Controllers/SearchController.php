<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
     * Membangun query untuk pencarian postingan (feed, grup, halaman) dengan memperhatikan privasi grup.
     */
    protected function buildStreamsQuery(string $q, $user = null)
    {
        return Stream::with(['user', 'targetPage', 'targetGroup'])
            ->whereNotNull('message')
            ->where('message', '!=', '')
            ->where('message', 'like', "%{$q}%")
            ->visibleTo($user)
            ->where(function ($query) use ($user) {
                // 1. Postingan feed biasa
                $query->where('app', 'feed')
                    // 2. Postingan grup (dengan pengecekan privasi grup)
                    ->orWhere(function ($gq) use ($user) {
                        $gq->where('app', 'group')
                           ->whereExists(function ($sub) use ($user) {
                               $sub->select(DB::raw(1))
                                   ->from('jcow_pages')
                                   ->where(function ($w) {
                                       $w->whereColumn('jcow_pages.id', 'jcow_streams.wall_id')
                                         ->orWhereColumn('jcow_pages.id', 'jcow_streams.aid');
                                   })
                                   ->where(function ($typeQuery) use ($user) {
                                       // Grup publik bisa dilihat siapapun
                                       $typeQuery->where('jcow_pages.type', 'group');

                                       // Grup privat hanya bisa dilihat jika user login & merupakan member/creator/admin
                                       if ($user) {
                                           $typeQuery->orWhere(function ($priv) use ($user) {
                                               $priv->where('jcow_pages.type', 'private_group')
                                                    ->where(function ($memberQuery) use ($user) {
                                                        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                                                            $memberQuery->whereRaw('1=1');
                                                        } else {
                                                            $memberQuery->where('jcow_pages.uid', $user->id)
                                                                ->orWhereExists(function ($mq) use ($user) {
                                                                    $mq->select(DB::raw(1))
                                                                       ->from('jcow_page_users')
                                                                       ->whereColumn('jcow_page_users.pid', 'jcow_pages.id')
                                                                       ->where('jcow_page_users.uid', $user->id);
                                                                });
                                                        }
                                                    });
                                           });
                                       }
                                   });
                           });
                    })
                    // 3. Postingan halaman
                    ->orWhere(function ($pq) {
                        $pq->where('app', 'page')
                           ->whereExists(function ($sub) {
                               $sub->select(DB::raw(1))
                                   ->from('jcow_pages')
                                   ->where(function ($w) {
                                       $w->whereColumn('jcow_pages.id', 'jcow_streams.wall_id')
                                         ->orWhereColumn('jcow_pages.id', 'jcow_streams.aid');
                                   });
                           });
                    });
            });
    }

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
                $usersQuery = User::where(function ($query) use ($q) {
                    $query->where('username', 'like', "%{$q}%")
                          ->orWhere('fullname',  'like', "%{$q}%");
                });

                if (auth()->check()) {
                    $currId = auth()->id();
                    $usersQuery->whereNotIn('id', function($sub) use ($currId) {
                        $sub->select('bid')->from('jcow_blacks')->where('uid', $currId);
                    })->whereNotIn('id', function($sub) use ($currId) {
                        $sub->select('uid')->from('jcow_blacks')->where('bid', $currId);
                    });
                }

                $users = $usersQuery->limit($tab === 'semua' ? 8 : 40)->get();
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
                $streams = $this->buildStreamsQuery($q, auth()->user())
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
                                })
                                ->when(auth()->check(), function($uq) {
                                    $currId = auth()->id();
                                    $uq->whereNotIn('id', function($sub) use ($currId) {
                                        $sub->select('bid')->from('jcow_blacks')->where('uid', $currId);
                                    })->whereNotIn('id', function($sub) use ($currId) {
                                        $sub->select('uid')->from('jcow_blacks')->where('bid', $currId);
                                    });
                                })
                                ->count(),
                'grup'      => Group::where(function ($q2) use ($q) {
                                    $q2->where('name','like',"%{$q}%")->orWhere('description','like',"%{$q}%");
                                })->count(),
                'halaman'   => Page::where(function ($q2) use ($q) {
                                    $q2->where('name','like',"%{$q}%")->orWhere('description','like',"%{$q}%");
                                })->count(),
                'postingan' => $this->buildStreamsQuery($q, auth()->user())->count(),
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
            return response()->json(['users' => [], 'groups' => [], 'pages' => [], 'posts' => []]);
        }

        $usersQuery = User::where(function ($query) use ($q) {
                $query->where('username', 'like', "%{$q}%")
                      ->orWhere('fullname', 'like', "%{$q}%");
            });

        if (auth()->check()) {
            $currId = auth()->id();
            $usersQuery->whereNotIn('id', function($sub) use ($currId) {
                $sub->select('bid')->from('jcow_blacks')->where('uid', $currId);
            })->whereNotIn('id', function($sub) use ($currId) {
                $sub->select('uid')->from('jcow_blacks')->where('bid', $currId);
            });
        }

        $users = $usersQuery
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

        $posts = $this->buildStreamsQuery($q, auth()->user())
            ->orderBy('created', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($s) {
                $authorName = optional($s->user)->fullname ?: optional($s->user)->username ?? 'Pengguna';
                $groupName = null;
                $url = route('search.index', ['q' => $s->message, 'tab' => 'postingan']);

                if ($s->app === 'group') {
                    $group = $s->targetPage ?? $s->targetGroup;
                    $groupId = $group ? $group->id : $s->wall_id;
                    $groupName = $group ? $group->name : 'Grup';
                    $url = url('/groups/' . $groupId . '#stream-card-' . $s->id);
                } elseif ($s->app === 'page') {
                    $page = $s->targetPage;
                    $pageId = $page ? $page->id : $s->wall_id;
                    $url = url('/pages/' . $pageId . '#stream-card-' . $s->id);
                } elseif ($s->user) {
                    $url = url('/@' . $s->user->username . '#stream-card-' . $s->id);
                }

                return [
                    'id'         => $s->id,
                    'name'       => Str::limit($s->message, 50),
                    'author'     => $authorName,
                    'group_name' => $groupName,
                    'app'        => $s->app,
                    'avatar'     => optional($s->user)->avatar_url,
                    'url'        => $url,
                    'type'       => 'post',
                ];
            });

        return response()->json([
            'users'  => $users,
            'groups' => $groups,
            'pages'  => $pages,
            'posts'  => $posts,
        ]);
    }
}

