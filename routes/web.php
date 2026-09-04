<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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


Route::middleware('auth')->group(function () {
    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/conversation/{userId}', [App\Http\Controllers\MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{id}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/bulk-delete', [App\Http\Controllers\MessageController::class, 'bulkDelete'])->name('messages.bulkDelete');
    Route::post('/messages/delete-for-everyone/{id}', [App\Http\Controllers\MessageController::class, 'deleteForEveryone'])->name('messages.deleteForEveryone');

    // Friends
    Route::get('/friends', [App\Http\Controllers\FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/requests', [App\Http\Controllers\FriendController::class, 'requests'])->name('friends.requests');
    Route::post('/friends/request', [App\Http\Controllers\FriendController::class, 'sendRequest'])->name('friends.sendRequest');
    Route::post('/friends/accept/{userId}', [App\Http\Controllers\FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/friends/reject/{userId}', [App\Http\Controllers\FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/cancel/{userId}', [App\Http\Controllers\FriendController::class, 'cancelRequest'])->name('friends.cancelRequest');
    Route::delete('/friends/unfriend/{userId}', [App\Http\Controllers\FriendController::class, 'unfriend'])->name('friends.unfriend');
    Route::post('/friends/follow/{userId}', [App\Http\Controllers\FriendController::class, 'follow'])->name('friends.follow');
    Route::post('/friends/unfollow/{userId}', [App\Http\Controllers\FriendController::class, 'unfollow'])->name('friends.unfollow');
    Route::post('/friends/block/{userId}', [App\Http\Controllers\FriendController::class, 'block'])->name('friends.block');
    Route::post('/friends/unblock/{userId}', [App\Http\Controllers\FriendController::class, 'unblock'])->name('friends.unblock');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');

    // Telusur (Browse Members)
    Route::get('/telusur', [App\Http\Controllers\TelusurController::class, 'index'])->name('telusur.index');
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
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');

});

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

// Dev Login (Development)
Route::get('/dev-login/bima', function () {
    $user = App\Models\User::find(1); 
    Auth::login($user);
    return redirect()->route('messages.index');
});

Route::get('/dev-login/giska', function () {
    $user = App\Models\User::find(2); 
    if ($user) {
        Auth::login($user);
        return redirect()->route('messages.index');
    }
    return 'Akun ID 2 tidak ada di database! Coba ganti angka find(2) menjadi find(3).';
});
