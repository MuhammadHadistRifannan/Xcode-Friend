<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // =====================================
    // MEMPROSES LOGIN
    // =====================================
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cek apakah user mencentang tombol "Remember me"
        $remember = $request->has('remember');

        // Tentukan apakah input 'login' itu berupa email atau username
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Coba lakukan login ke sistem
        if (Auth::attempt([$fieldType => $request->login, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Update waktu login terakhir
            if ($user) {
                $user->update([
                    'lastlogin' => time()
                ]);
            }

            // Cek apakah user adalah admin
            $role = Auth::user()->roles;
            $roleLower = strtolower($role);
            if ($role == 1 || in_array($roleLower, ['admin', 'administrator'])) {
                // Jika Admin, alihkan ke Dashboard Admin
                return redirect()->intended('/admin/dashboard');
            }

            // Jika berhasil, alihkan ke Dashboard (Beranda)
            return redirect()->intended('/beranda');
        }

        // Jika gagal login (sandi/email salah)
        return back()->withErrors([
            'login' => 'Email/Username atau Password salah.',
        ])->onlyInput('login');
    }

    // =====================================
    // MEMPROSES REGISTRASI
    // =====================================
    public function store(Request $request)
    {
        // Validasi Visual CAPTCHA
        if (!\App\Http\Controllers\CaptchaController::verify($request->captcha_answer ?? '')) {
            return back()->withErrors(['captcha_answer' => 'Kode verifikasi captcha salah. Silakan coba lagi.'])->withInput();
        }

        $validated = $request->validate([
            'email'        => ['required', 'email', 'unique:jcow_accounts,email', 'max:120'],
            'username'     => ['required', 'string', 'min:4', 'max:18', 'unique:jcow_accounts,username', 'regex:/^[a-zA-Z0-9]+$/'],
            'password'     => ['required', 'string', 'min:6'],
            'fullname'     => ['required', 'string', 'max:30'],
            'birthyear'    => ['required', 'integer'],
            'birthmonth'   => ['required', 'integer'],
            'birthday'     => ['required', 'integer'],
            'gender'       => ['required', 'in:0,1,2'],
            'country'      => ['required', 'string'],
            'about_me'     => ['nullable', 'string'],
            'motto'        => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'website'      => ['nullable', 'string', 'max:255'],
            'forum_id'     => ['nullable', 'string', 'max:255'],
            'fav_film'     => ['nullable', 'string'],
        ], [
            'email.unique'    => 'Alamat email ini sudah terdaftar.',
            'username.unique' => 'Username ini sudah digunakan oleh orang lain.',
            'username.regex'  => 'Username hanya boleh berisi huruf dan angka tanpa spasi.',
            'password.min'    => 'Password harus memiliki minimal 6 karakter.',
        ]);

        // Kumpulkan field tambahan ke dalam settings JSON
        $settings = json_encode([
            'motto'        => $request->motto ?? '',
            'phone_number' => $request->phone_number ?? '',
            'website'      => $request->website ?? '',
            'forum_id'     => $request->forum_id ?? '',
            'fav_film'     => $request->fav_film ?? '',
        ]);

        // Simpan data User ke database
        User::create([
            'email'      => $validated['email'],
            'username'   => $validated['username'],
            'password'   => Hash::make($validated['password']),
            'fullname'   => $validated['fullname'],
            'birthyear'  => $validated['birthyear'],
            'birthmonth' => $validated['birthmonth'],
            'birthday'   => $validated['birthday'],
            'gender'     => $validated['gender'],
            'country'    => $validated['country'],
            'about_me'   => $validated['about_me'] ?? '',
            'hide_age'   => $request->has('hide_age') ? 1 : 0,
            'settings'   => $settings,

            // Kolom Legacy JCow (Default/Bawaan)
            'created'     => time(),
            'lastlogin'   => time(),
            'ipaddress'   => $request->ip(),
            'points'      => 0, 'avatar' => '', 'roles' => '', 'jcowsess' => '', 'token' => '',
            'signature'   => '', 'blurbs' => '', 'location' => '', 'chpass' => '',
            'disabled'    => 0, 'intr' => '', 'reg_code' => '', 'forum_posts' => 0,
            'featured'    => 0, 'locale' => '', 'state' => '', 'wall_id' => 0, 'followers' => 0,
            'var1'        => '', 'var2' => '', 'var3' => '', 'var4' => '',
            'var5'        => '', 'var6' => '', 'var7' => '', 'pass' => '', 'hide_me' => 0,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.');
    }

    // =====================================
    // MEMPROSES LOGOUT
    // =====================================
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Alihkan ke Landing Page Guest setelah logout
        return redirect('/');
    }
}