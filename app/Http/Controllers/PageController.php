<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    private function getPagesData()
    {
        return [
            ['id' => 1, 'title' => 'cihuyyyy test', 'likes' => 1, 'lastUpdate' => '3 hours ago', 'description' => 'Initial deployment', 'status' => 'verified'],
            ['id' => 2, 'title' => 'dummy page', 'likes' => 0, 'lastUpdate' => 'Yesterday', 'description' => 'Security patch applied', 'status' => 'normal'],
            ['id' => 3, 'title' => 'core processor lama', 'likes' => 0, 'lastUpdate' => '12 Oct 2024', 'description' => 'UI refinement', 'status' => 'normal'],
            ['id' => 4, 'title' => 'claim your crypto ..', 'likes' => 0, 'lastUpdate' => '3 hours ago', 'description' => 'API integration updated', 'status' => 'normal'],
            ['id' => 5, 'title' => 'seorang remaja sma yg sedang mencari pac..', 'likes' => 0, 'lastUpdate' => '1 hour ago', 'description' => 'Content moderation check', 'status' => 'normal'],
            ['id' => 6, 'title' => 'Revision 1', 'likes' => 1, 'lastUpdate' => 'Just now', 'description' => 'Revision 1 finalized', 'status' => 'active'],
            ['id' => 7, 'title' => 'Database migration', 'likes' => 0, 'lastUpdate' => '10 Oct 2024', 'description' => 'Database migration', 'status' => 'normal'],
            ['id' => 8, 'title' => 'System health check', 'likes' => 0, 'lastUpdate' => '2 days ago', 'description' => 'System health check', 'status' => 'normal'],
            ['id' => 9, 'title' => 'Cihuyyy cihuyyy', 'likes' => 0, 'lastUpdate' => '5 hours ago', 'description' => 'Spam filter update', 'status' => 'normal'],
            ['id' => 10, 'title' => 'http://www.jeremyscottadidasshop.com', 'likes' => 0, 'lastUpdate' => 'Yesterday', 'description' => 'Link verification complete', 'status' => 'normal']
        ];
    }

    public function index()
    {
        $pages = $this->getPagesData();
        return view('pages.index', compact('pages'));
    }

    public function show($id)
    {
        // Mock data for detail page
        $pageData = [
            'profile' => [
                'name' => 'cihuyyyy',
                'isVerified' => true,
                'description' => 'test',
                'likesCount' => 1,
                'coverUrl' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=1000',
                'avatarUrl' => 'https://ui-avatars.com/api/?name=cihuyyyy&background=ff0000&color=fff',
            ],
            'posts' => [
                [
                    'id' => 1,
                    'author' => ['name' => 'Nur Ifant Ristanto', 'avatarUrl' => 'https://ui-avatars.com/api/?name=Nur+Ifant&background=random'],
                    'action' => 'menambahkan album foto (1 pics)',
                    'time' => 'TODAY, 8:01 PM',
                    'content' => '',
                    'imageUrl' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=1000',
                    'likes' => 0,
                    'comments' => [
                        ['id' => 1, 'author' => ['name' => 'ifant', 'username' => 'cihuyyy', 'avatarUrl' => 'https://ui-avatars.com/api/?name=ifant'], 'text' => '', 'time' => 'TODAY, 8:38 PM'],
                        ['id' => 2, 'author' => ['name' => 'Nur Ifant Ristanto', 'avatarUrl' => 'https://ui-avatars.com/api/?name=Nur+Ifant'], 'text' => 'cihuyyyy', 'time' => 'TODAY, 7:59 PM']
                    ]
                ]
            ],
            'reviews' => [
                'rating' => 4.9,
                'count' => 532
            ],
            'networkLinks' => [
                ['id' => 1, 'label' => 'LinkedIn', 'url' => '#'],
                ['id' => 2, 'label' => 'phpBB Group', 'url' => '#'],
                ['id' => 3, 'label' => 'Facebook', 'url' => '#']
            ]
        ];

        return view('pages.show', compact('pageData'));
    }

    public function create()
    {
        return view('pages.create');
    }
}
