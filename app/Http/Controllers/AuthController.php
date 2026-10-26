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
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->login);
        $passwordInput = $request->password;
        $remember = $request->has('remember');

        // Cari akun berdasarkan username ATAU email (case-insensitive di MySQL)
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Email/Username atau Password salah.',
            ])->onlyInput('login');
        }

        // Cek status disabled pada akun (2 = Suspend)
        // Catatan: Status 1 (Pending) tetap diizinkan login dengan akses fitur terbatas dan peringatan di halaman
        if ((int) $user->disabled === 2) {
            return back()->withErrors([
                'login' => 'Akun Anda sedang disuspend.',
            ])->onlyInput('login');
        }

        // Verifikasi password (mendukung Bcrypt maupun MD5 legacy via Md5BcryptHasher)
        if (Hash::check($passwordInput, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            $updates = [
                'lastlogin' => time(),
            ];

            // Jika password masih format MD5 legacy, otomatis upgrade ke Bcrypt
            if (Hash::needsRehash($user->password)) {
                $updates['password'] = Hash::make($passwordInput);
            }

            $user->update($updates);

            // Cek apakah user adalah admin
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            // Jika berhasil, alihkan ke Dashboard (Beranda)
            return redirect()->intended('/beranda');
        }

        // Jika password tidak cocok
        return back()->withErrors([
            'login' => 'Email/Username atau Password salah.',
        ])->onlyInput('login');
    }

    // =====================================
    // MEMPROSES REGISTRASI
    // =====================================
    public function store(Request $request)
    {
        // Validasi Visual CAPTCHA jika diaktifkan di konfigurasi admin
        $disableCaptcha = \App\Helpers\SettingHelper::get('disable_recaptcha_reg', '0') == '1';
        if (!$disableCaptcha && !\App\Http\Controllers\CaptchaController::verify($request->captcha_answer ?? '')) {
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

        // Cek mode verifikasi akun (acc_verify: 0 = Auto, 1 = Email, 2 = Admin Approval)
        $accVerify = \App\Helpers\SettingHelper::get('acc_verify', '1');
        $disabledStatus = ($accVerify == '2') ? 1 : 0;

        // Simpan data User ke database
        $newUser = User::create([
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
            'disabled'    => $disabledStatus, 'intr' => '', 'reg_code' => '', 'forum_posts' => 0,
            'featured'    => 0, 'locale' => '', 'state' => '', 'wall_id' => 0, 'followers' => 0,
            'var1'        => '', 'var2' => '', 'var3' => '', 'var4' => '',
            'var5'        => '', 'var6' => '', 'var7' => '', 'pass' => '', 'hide_me' => 0,
        ]);

        // Jika pendaftar menggunakan link undangan / referral
        if ($request->filled('ref')) {
            $referrer = User::where('username', $request->ref)->first();
            if ($referrer) {
                $invite = \App\Models\Invite::where('uid', $referrer->id)
                    ->where('email', $validated['email'])
                    ->first();

                if ($invite) {
                    $invite->update(['status' => 1]);
                } else {
                    // Jika tidak ada di DB (daftar pakai link sembarangan tanpa via email Tab 2),
                    // buatkan record baru agar pengundang tetap dapat kredit!
                    \App\Models\Invite::create([
                        'uid'     => $referrer->id,
                        'email'   => $validated['email'],
                        'status'  => 1, // Langsung dianggap Diterima
                        'created' => time(),
                    ]);
                }

                // --- Auto Friend/Follow Feature ---
                // Karena mereka saling mengundang, otomatis buat mereka berteman
                try {
                    $friendService = app(\App\Services\FriendService::class);
                    // Kirim permohonan dari referrer ke pendaftar baru
                    $friendService->sendRequest($referrer->id, $newUser->id, 'Bergabung melalui undangan.');
                    // Langsung setujui oleh pendaftar baru
                    $friendService->acceptRequest($newUser->id, $referrer->id);
                } catch (\Exception $e) {
                    // Abaikan jika terjadi kegagalan (misalnya karena batasan sistem)
                }
            }
        }

        if ($disabledStatus === 1) {
            return redirect('/login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan verifikasi dari administrator. Anda dapat masuk dengan akses fitur terbatas.');
        }

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.');
    }

    // =====================================
    // MEMPROSES LOGOUT
    // =====================================
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_seen = 0;
            $user->save();
        }

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Alihkan ke Landing Page Guest setelah logout
        return redirect('/');
    }
}