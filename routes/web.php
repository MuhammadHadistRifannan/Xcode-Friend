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
    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/outbox', [App\Http\Controllers\MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('/messages/create', [App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{id}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{id}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/bulk-delete', [App\Http\Controllers\MessageController::class, 'bulkDelete'])->name('messages.bulkDelete');

    // Friends
    Route::get('/friends', [App\Http\Controllers\FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/requests', [App\Http\Controllers\FriendController::class, 'requests'])->name('friends.requests');
    Route::post('/friends/request', [App\Http\Controllers\FriendController::class, 'sendRequest'])->name('friends.sendRequest');
    Route::post('/friends/accept/{userId}', [App\Http\Controllers\FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/friends/reject/{userId}', [App\Http\Controllers\FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/unfriend/{userId}', [App\Http\Controllers\FriendController::class, 'unfriend'])->name('friends.unfriend');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
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
    // Memaksa login menggunakan User urutan pertama (ID 1)
    $user = App\Models\User::find(1); 
    Auth::login($user);
    return redirect()->route('messages.index');
});

Route::get('/dev-login/giska', function () {
    // Memaksa login menggunakan User urutan kedua (ID 2)
    $user = App\Models\User::find(2); 
    if ($user) {
        Auth::login($user);
        return redirect()->route('messages.index');
    }
    return 'Akun ID 2 tidak ada di database! Coba ganti angka find(2) menjadi find(3).';
});