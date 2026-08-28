<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// ==========================================
// 1. AREA BERANDA / FEED
// ==========================================

// Halaman Landing Page (Statistik & Feed Publik)
Route::get('/', [HomeController::class, 'guest'])->name('home.guest');

// Halaman Dashboard 3 Kolom (Hanya bisa diakses jika sudah login)
Route::get('/beranda', [HomeController::class, 'index'])->middleware('auth')->name('beranda');

Route::middleware('auth')->group(function () {
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/outbox', [App\Http\Controllers\MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

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

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dev-login/bima', function () {
    $user = App\Models\User::where('email', 'bima@xcode.test')->first() ?? App\Models\User::first();
    Auth::login($user);
    return redirect()->route('messages.index')->with('success', 'Berhasil login sebagai Bima!');
});

Route::get('/dev-login/giska', function () {
    $user = App\Models\User::where('email', 'giska@xcode.test')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('messages.index');
    }
    return 'User Giska belum ditemukan di database! Jalankan tinker dulu.';
});