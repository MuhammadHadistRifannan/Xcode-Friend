<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Stream;
use App\Models\Comment;
use App\Models\Friend;

class AuthController extends Controller
{
    // Memproses Login
    public function authenticate(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'], // Bisa Email atau Username
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password], $request->has('remember'))) {
            $request->session()->regenerate();

            // Update timestamp legacy saat berhasil login
            $user = Auth::user();
            $user->lastlogin = time();
            $user->ipaddress = $request->ip();
            $user->save();

            return redirect()->intended('/beranda');
        }

        return back()->withErrors(['login' => 'Email/Username atau Password salah.'])->onlyInput('login');
    }

    // Memproses Registrasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:jcow_accounts,email', 'max:120'],
            'username' => ['required', 'string', 'min:4', 'max:18', 'unique:jcow_accounts,username', 'regex:/^[a-zA-Z0-9]+$/'],
            'password' => ['required', 'string', 'min:6'],
            'fullname' => ['required', 'string', 'max:30'],
            'birthyear' => ['required', 'integer'],
            'birthmonth' => ['required', 'integer'],
            'birthday' => ['required', 'integer'],
            'gender' => ['required', 'in:0,1,2'],
            'country' => ['required', 'string'],
            'about_me' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'fullname' => $validated['fullname'],
            'birthyear' => $validated['birthyear'],
            'birthmonth' => $validated['birthmonth'],
            'birthday' => $validated['birthday'],
            'gender' => $validated['gender'],
            'country' => $validated['country'],
            'about_me' => $validated['about_me'] ?? '',
            'hide_age' => $request->has('hide_age') ? 1 : 0,
            'created' => time(),
            'lastlogin' => time(),
            'ipaddress' => $request->ip(),
            'points' => 0, 'avatar' => '', 'roles' => '', 'jcowsess' => '', 'token' => '',
            'signature' => '', 'blurbs' => '', 'location' => '', 'chpass' => '',
            'disabled' => 0, 'intr' => '', 'reg_code' => '', 'forum_posts' => 0,
            'featured' => 0, 'locale' => '', 'state' => '', 'wall_id' => 0, 'followers' => 0,
            'settings' => '', 'var1' => '', 'var2' => '', 'var3' => '', 'var4' => '',
            'var5' => '', 'var6' => '', 'var7' => '', 'pass' => '', 'hide_me' => 0,
        ]);

        Auth::login($user);
        return redirect('/beranda');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    // Pastikan di bagian atas sudah ada: use App\Models\Stream; use App\Models\Comment; use App\Models\Friend;

    public function showLoginForm()
    {
        // Mengambil statistik global
        $stats = [
            'activities' => Stream::count(),
            'members' => User::count(),
            'friendships' => Friend::count(),
            'comments' => Comment::count(),
        ];

        // 4 User yang terakhir login
        $recentLogins = User::where('avatar', '!=', '')->orderBy('lastlogin', 'desc')->take(4)->get();

        // Mengambil Feed Publik
        $publicStreams = Stream::with(['user', 'comments.user'])->orderBy('created', 'desc')->take(5)->get();

        return view('auth.login', compact('stats', 'recentLogins', 'publicStreams'));
    }
}
