<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {
        // TODO: Ganti dengan logika autentikasi sesungguhnya saat middleware sudah ada
        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $stats = [
            'total_members' => \Illuminate\Support\Facades\DB::table('jcow_accounts')->count(),
            'pending_members' => \Illuminate\Support\Facades\DB::table('jcow_accounts')->where('disabled', 1)->count(),
            'total_photos' => \Illuminate\Support\Facades\DB::table('jcow_story_photos')->count(),
            'total_videos' => \Illuminate\Support\Facades\DB::table('jcow_stories')->where('app', 'video')->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function siteConfiguration()
    {
        $config = [
            'site_name' => 'X-CODE NETWORK',
            'slogan' => 'Advanced Infrastructure Management',
            'keywords' => 'x-code, network, cybersecurity, control panel, infrastructure, devops',
            'webmaster_email' => 'sysadmin@x-code.network',
            'footer_message' => '© 2024 X-CODE TECHNOLOGY NETWORK. ALL RIGHTS RESERVED.',
            'network_viewing' => 'Registered Members Only',
            'account_verification' => 'Email Verification Link',
            'pending_limit' => 5,
            'website_offline' => false,
            'offline_reason' => 'The X-CODE network is currently undergoing scheduled maintenance. Please check back later.',
            'max_name_length' => 200,
            'enable_on_signup' => true,
            'enable_on_login' => false,
            'locations' => ['Indonesia', 'USA', 'Japan', 'Australia', 'Austria']
        ];
        
        return view('admin.site-config', compact('config'));
    }

    public function modules()
    {
        $community_modules = [
            ['name' => 'BLOGS', 'desc' => 'User-generated long-form editorial publishing system.', 'type' => 'CORE', 'version' => 'V1.0.1'],
            ['name' => 'EVENTS', 'desc' => 'Schedule events.', 'type' => 'CORE', 'version' => 'V1.3.0'],
            ['name' => 'FEED', 'desc' => 'Algorithmic timeline and chronological activity stream.', 'type' => 'COMP', 'version' => 'V1.8.3'],
            ['name' => 'PHOTOS', 'desc' => 'High-resolution image processing and gallery deployment.', 'type' => 'COMP', 'version' => 'V1.1.0'],
        ];

        $core_modules = [
            ['name' => 'ACCOUNT', 'desc' => 'User lifecycle, authentication, and security credentials.', 'type' => 'CORE', 'version' => 'V4.0.0'],
            ['name' => 'ADMIN CP', 'desc' => 'Global command center and architecture overrides.', 'type' => 'CORE', 'version' => 'V5.0.3'],
            ['name' => 'BLOCK / UNBLOCK', 'desc' => 'Inter-user access and social restriction routing.', 'type' => 'CORE', 'version' => 'V0.4.1'],
        ];

        return view('admin.modules', compact('community_modules', 'core_modules'));
    }

    public function menu()
    {
        $community_menu = [
            ['id' => 1, 'active' => true, 'weight' => 1, 'name' => 'Browse', 'path' => 'browse'],
            ['id' => 2, 'active' => true, 'weight' => 2, 'name' => 'News Feed', 'path' => 'feed'],
            ['id' => 3, 'active' => true, 'weight' => 6, 'name' => 'Blogs', 'path' => 'blogs'],
            ['id' => 4, 'active' => true, 'weight' => 11, 'name' => 'Videos', 'path' => 'videos'],
        ];

        $personal_menu = [
            ['id' => 5, 'active' => true, 'weight' => 2, 'name' => 'Dashboard', 'path' => 'dashboard'],
            ['id' => 6, 'active' => true, 'weight' => 3, 'name' => 'Photos', 'path' => 'photos/mine'],
            ['id' => 7, 'active' => true, 'weight' => 4, 'name' => 'Blogs', 'path' => 'blogs/mine'],
            ['id' => 8, 'active' => true, 'weight' => 5, 'name' => 'Videos', 'path' => 'videos/mine'],
            ['id' => 9, 'active' => true, 'weight' => 25, 'name' => 'My account', 'path' => 'account'],
            ['id' => 10, 'active' => true, 'weight' => 26, 'name' => 'Invite', 'path' => 'invite'],
        ];

        return view('admin.menu', compact('community_menu', 'personal_menu'));
    }

    public function userRoles()
    {
        $current_roles = ['Guest', 'General member', 'Administrator'];

        return view('admin.user-roles', compact('current_roles'));
    }

    public function translate()
    {
        return view('admin.translate');
    }

    public function reports()
    {
        $reports = \App\Models\Report::with('user')->orderBy('created', 'desc')->get();
        return view('admin.reports', compact('reports'));
    }

    public function reportsResolve($id)
    {
        \App\Models\Report::where('id', $id)->update(['hasread' => 1]);
        return back()->with('success', 'Laporan telah ditandai sebagai telah diselesaikan.');
    }
}
