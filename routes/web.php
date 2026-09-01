<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CaptchaController;

// ==========================================
// 1. AREA BERANDA / FEED
// ==========================================

// Halaman Landing Page (Statistik & Feed Publik)
Route::get('/', [HomeController::class, 'guest'])->name('home.guest');

Route::middleware('auth')->group(function () {
    Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
    Route::post('/stream', [StreamController::class, 'store'])->name('stream.store');
    Route::post('/like/{stream}', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('like.toggle');
    Route::post('/comment/{stream}', [\App\Http\Controllers\CommentController::class, 'store'])->name('comment.store');
    
    // Profil Edit (Settings)
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/background', [ProfileController::class, 'updateBackground'])->name('profile.background.update');
    
    // Album API
    Route::get('/api/albums', [AlbumController::class, 'search'])->name('album.search');
    Route::post('/api/albums', [AlbumController::class, 'store'])->name('album.store');
});

// Rute Profil Pengguna (contoh: xcode-friends.com/@giska) - Bisa diakses publik
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');



// ==========================================
// 2. AREA OTENTIKASI (LOGIN & REGISTER)
// ==========================================

// Dibungkus middleware 'guest' agar user yang sudah login tidak bisa masuk ke sini
Route::middleware('guest')->group(function () {

    // Halaman Login Polos
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    // Halaman Register
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'store']);

    // Halaman Lupa Sandi
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');

});

// Logout (Hanya bisa ditekan jika user sedang login)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Captcha Image Generator
Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha.generate');

// ==========================================
// 3. AREA ADMIN
// ==========================================
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', function () {
        return '<h1>Dashboard Admin</h1><p>Selamat datang, Admin!</p><form method="POST" action="'.route('logout').'">'.csrf_field().'<button type="submit">Logout</button></form>';
    })->name('admin.dashboard');
});
