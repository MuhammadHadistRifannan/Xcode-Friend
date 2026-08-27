<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Rute di dalam blok ini HANYA bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {
    
    // --- ROUTES UNTUK PESAN (MESSAGES) ---
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/outbox', [App\Http\Controllers\MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

});

// --- RUTE SEMENTARA UNTUK MULTI-ACCOUNT TESTING ---
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
    return "User Giska belum ditemukan di database! Jalankan tinker dulu.";
});

// --- RUTE SEMENTARA UNTUK MENCEGAH ERROR MIDDLEWARE ---
Route::get('/login', function () {
    return "Halaman Login sedang dikerjakan Giska. Silakan pergi ke URL: /dev-login untuk masuk otomatis.";
})->name('login');