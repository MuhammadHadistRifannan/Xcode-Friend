<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileDesignController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('pages.index');
});

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login-process', [AdminController::class, 'loginProcess'])->name('admin.login.process');
// TODO: Bungkus dengan Middleware Auth
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/site-configuration', [AdminController::class, 'siteConfiguration'])->name('admin.site-config');
Route::get('/admin/modules', [AdminController::class, 'modules'])->name('admin.modules');
Route::get('/admin/menu', [AdminController::class, 'menu'])->name('admin.menu');
Route::get('/admin/user-roles', [AdminController::class, 'userRoles'])->name('admin.user-roles');
Route::get('/admin/translate', [AdminController::class, 'translate'])->name('admin.translate');

Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

// Create Routes (Must be defined BEFORE dynamic {id} routes)
Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
Route::get('/photos/upload', [PhotoController::class, 'create'])->name('photos.upload');
Route::post('/photos/upload', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
Route::get('/videos/{id}/watch', [VideoController::class, 'watch'])->name('videos.watch');

Route::get('/pages/{id}', [PageController::class, 'show'])->name('pages.show');

// Dropdown Menu Routes
Route::get('/my-pages', [MyPageController::class, 'index'])->name('my-pages.index');
Route::get('/video', [VideoController::class, 'index'])->name('video.index');
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/desain-profil', [ProfileDesignController::class, 'index'])->name('desain-profil.index');
Route::get('/foto', [PhotoController::class, 'index'])->name('foto.index');
// Album CRUD Routes (MUST be before wildcard /foto/{id})
Route::get('/foto/album/{id}/edit', [PhotoController::class, 'editAlbum'])->name('foto.album.edit');
Route::put('/foto/album/{id}', [PhotoController::class, 'updateAlbum'])->name('foto.album.update');
Route::delete('/foto/album/{id}', [PhotoController::class, 'destroyAlbum'])->name('foto.album.destroy');
// Foto (individual photo) routes
Route::put('/foto/photo/{id}', [PhotoController::class, 'updatePhoto'])->name('foto.photo.update');
Route::delete('/foto/photo/{id}', [PhotoController::class, 'destroyPhoto'])->name('foto.photo.destroy');
// Album Show Route
Route::get('/foto/{id}', [PhotoController::class, 'show'])->name('foto.show');
Route::get('/undang', [InvitationController::class, 'index'])->name('undang.index');
