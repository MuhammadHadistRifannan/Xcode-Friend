<?php

use Illuminate\Support\Facades\Route;

// Controllers from GitHub
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// Local Controllers
use App\Http\Controllers\PageController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileDesignController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminController;

// ==========================================
// 1. AREA BERANDA / FEED
// ==========================================

// Halaman Landing Page (Statistik & Feed Publik)
Route::get('/', [HomeController::class, 'guest'])->name('home.guest');

// Halaman Dashboard 3 Kolom (Hanya bisa diakses jika sudah login)
Route::get('/beranda', [HomeController::class, 'index'])->middleware('auth')->name('beranda');

// ==========================================
// 2. AREA OTENTIKASI (LOGIN & REGISTER)
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==========================================
// 3. ADMIN ROUTES
// ==========================================
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login-process', [AdminController::class, 'loginProcess'])->name('admin.login.process');
// TODO: Bungkus dengan Middleware Auth
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/site-configuration', [AdminController::class, 'siteConfiguration'])->name('admin.site-config');
Route::get('/admin/modules', [AdminController::class, 'modules'])->name('admin.modules');
Route::get('/admin/menu', [AdminController::class, 'menu'])->name('admin.menu');
Route::get('/admin/user-roles', [AdminController::class, 'userRoles'])->name('admin.user-roles');
Route::get('/admin/translate', [AdminController::class, 'translate'])->name('admin.translate');

// ==========================================
// 4. PAGES ROUTES
// ==========================================
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
Route::get('/pages/mine', [PageController::class, 'mine'])->name('pages.mine');
Route::get('/pages/{id}', [PageController::class, 'show'])->name('pages.show');
Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
Route::put('/pages/{id}', [PageController::class, 'update'])->name('pages.update');
Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
Route::post('/pages/{id}/like', [PageController::class, 'like'])->name('pages.like');
Route::delete('/pages/{id}/unlike', [PageController::class, 'unlike'])->name('pages.unlike');

Route::get('/my-pages', [MyPageController::class, 'index'])->name('my-pages.index');

// ==========================================
// 5. VIDEOS ROUTES
// ==========================================
Route::get('/videos', [VideoController::class, 'publicIndex'])->name('videos.public');
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
Route::get('/video', [VideoController::class, 'index'])->name('video.index');
Route::get('/video/album/{id}/edit', [VideoController::class, 'editAlbum'])->name('video.album.edit');
Route::put('/video/album/{id}', [VideoController::class, 'updateAlbum'])->name('video.album.update');
Route::delete('/video/album/{id}', [VideoController::class, 'destroyAlbum'])->name('video.album.destroy');
Route::delete('/video/video/{id}', [VideoController::class, 'destroyVideo'])->name('video.video.destroy');
Route::get('/videos/{id}/watch', [VideoController::class, 'watch'])->name('videos.watch');

// ==========================================
// 6. PHOTOS ROUTES
// ==========================================
Route::get('/foto', [PhotoController::class, 'index'])->name('foto.index');
Route::get('/photos/upload', [PhotoController::class, 'create'])->name('photos.upload');
Route::post('/photos/upload', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/foto/album/{id}/edit', [PhotoController::class, 'editAlbum'])->name('foto.album.edit');
Route::put('/foto/album/{id}', [PhotoController::class, 'updateAlbum'])->name('foto.album.update');
Route::delete('/foto/album/{id}', [PhotoController::class, 'destroyAlbum'])->name('foto.album.destroy');
Route::put('/foto/photo/{id}', [PhotoController::class, 'updatePhoto'])->name('foto.photo.update');
Route::delete('/foto/photo/{id}', [PhotoController::class, 'destroyPhoto'])->name('foto.photo.destroy');
Route::get('/foto/{id}', [PhotoController::class, 'show'])->name('foto.show');

// ==========================================
// 7. GROUPS ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    // Custom Browse & Mine
    Route::get('/groups/browse', [GroupController::class, 'browse'])->name('groups.browse');
    Route::get('/groups/mine', [GroupController::class, 'mine'])->name('groups.mine');
    
    // Join & Leave Group
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    
    // Member Management
    Route::get('/groups/{group}/members', [GroupController::class, 'members'])->name('groups.members');
    Route::delete('/groups/{group}/members/{uid}', [GroupController::class, 'kickMember'])->name('groups.members.kick');
    
    // Pending Approval Management
    Route::get('/groups/{group}/pending', [GroupController::class, 'pending'])->name('groups.pending');
    Route::post('/groups/{group}/approve', [GroupController::class, 'approve'])->name('groups.approve');

    // Stream (Wall Post)
    Route::post('/groups/{id}/stream', [GroupController::class, 'postStream'])->name('groups.stream');
    Route::post('/stream/{id}/like', [GroupController::class, 'likeStream'])->name('stream.like');
    Route::post('/stream/{id}/comment', [GroupController::class, 'commentStream'])->name('stream.comment');

    // CRUD Resource Utama
    Route::resource('groups', GroupController::class);
});

// ==========================================
// 8. OTHER ROUTES
// ==========================================
Route::get('/desain-profil', [ProfileDesignController::class, 'index'])->name('desain-profil.index');
Route::get('/undang', [InvitationController::class, 'index'])->name('undang.index');
