<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Report;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'disabled' => 0,
        ];

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();

            // Only admin can login via this route
            if ($user->level == 1 || in_array(strtolower($user->roles ?? ''), ['admin', 'administrator'])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            \Illuminate\Support\Facades\Auth::logout();
            return back()->withErrors(['email' => 'Akun ini tidak memiliki akses admin.']);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function dashboard()
    {
        $stats = [
            'total_members'    => User::count(),
            'active_members'   => User::where('disabled', 0)->count(),
            'pending_members'  => User::where('disabled', 1)->count(),
            'suspended_members'=> User::where('disabled', 2)->count(),
            'total_streams'    => DB::table('jcow_streams')->count(),
            'total_comments'   => DB::table('jcow_comments')->count(),
            'total_photos'     => DB::table('jcow_story_photos')->count(),
            'total_videos'     => DB::table('jcow_stories')->where('app', 'video')->count(),
            'total_groups'     => DB::table('jcow_groups')->count(),
            'total_pages'      => DB::table('jcow_pages')->count(),
            'pending_reports'  => DB::table('jcow_reports')->where('hasread', 0)->count(),
        ];

        // Recent 5 members
        $recentMembers = User::orderBy('id', 'desc')->limit(5)->get();

        // Recent 5 reports
        $recentReports = DB::table('jcow_reports')
            ->where('hasread', 0)
            ->orderBy('created', 'desc')
            ->limit(5)
            ->get();

        // DB status
        $dbStatus = true;
        try { DB::connection()->getPdo(); } catch (\Exception $e) { $dbStatus = false; }

        return view('admin.dashboard', compact('stats', 'recentMembers', 'recentReports', 'dbStatus'));
    }

    public function siteConfiguration()
    {
        return redirect()->route('admin.site-config');
    }

    public function modules()
    {
        $modules = DB::table('jcow_modules')->get();
        return view('admin.modules', compact('modules'));
    }



    public function userRoles()
    {
        $roles = DB::table('jcow_roles')->get();
        return view('admin.user-roles', compact('roles'));
    }

    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        DB::table('jcow_roles')->insert(['name' => $request->name]);
        return back()->with('success', "Role '{$request->name}' berhasil ditambahkan.");
    }

    public function destroyRole($id)
    {
        // Protect default roles (id 1-3 assumed core)
        if ($id <= 3) {
            return back()->with('error', 'Role inti tidak bisa dihapus.');
        }
        DB::table('jcow_roles')->where('id', $id)->delete();
        return back()->with('success', 'Role berhasil dihapus.');
    }

    public function translate()
    {
        $langs = DB::table('jcow_langs')->select('lang')->distinct()->get()->pluck('lang');
        return view('admin.translate', compact('langs'));
    }

    public function reports(Request $request)
    {
        $query = Report::with('user')->orderBy('created', 'desc');
        
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('hasread', 0);
            } elseif ($request->status === 'resolved') {
                $query->where('hasread', 1);
            }
        }
        
        $reports = $query->paginate(20)->withQueryString();
        
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('hasread', 0)->count(),
            'resolved' => Report::where('hasread', 1)->count(),
        ];
        
        return view('admin.reports', compact('reports', 'stats'));
    }

    public function reportsResolve($id)
    {
        Report::where('id', $id)->update(['hasread' => 1]);
        return back()->with('success', 'Laporan telah ditandai sebagai selesai.');
    }

    public function reportsDestroy($id)
    {
        Report::where('id', $id)->delete();
        return back()->with('success', 'Laporan telah dihapus.');
    }
}
