<?php

use Illuminate\Support\Facades\Route;

// Controllers from GitHub
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CaptchaController;

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
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// ==========================================
// 3. ADMIN ROUTES
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
    Route::post('/login-process', [\App\Http\Controllers\AdminController::class, 'loginProcess'])->name('admin.login.process');
    
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.index');
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Members Management
        Route::get('/members', [\App\Http\Controllers\AdminMemberController::class, 'index'])->name('admin.members');
        Route::put('/members/{id}/role', [\App\Http\Controllers\AdminMemberController::class, 'updateRole'])->name('admin.members.role');
        Route::delete('/members/{id}/ban', [\App\Http\Controllers\AdminMemberController::class, 'banMember'])->name('admin.members.ban');
        
        // Site Configuration
        Route::get('/site-configuration', [\App\Http\Controllers\AdminSettingsController::class, 'index'])->name('admin.site-config');
        Route::post('/site-configuration', [\App\Http\Controllers\AdminSettingsController::class, 'update'])->name('admin.settings.update');
        // Modules Management
        Route::get('/modules', [\App\Http\Controllers\AdminModuleController::class, 'index'])->name('admin.modules');
        Route::post('/modules/toggle', [\App\Http\Controllers\AdminModuleController::class, 'toggle'])->name('admin.modules.toggle');
        // Custom Fields
        Route::get('/custom-fields', [\App\Http\Controllers\AdminCustomFieldController::class, 'index'])->name('admin.custom-fields');
        Route::post('/custom-fields', [\App\Http\Controllers\AdminCustomFieldController::class, 'store'])->name('admin.custom-fields.store');
        Route::delete('/custom-fields/{id}', [\App\Http\Controllers\AdminCustomFieldController::class, 'destroy'])->name('admin.custom-fields.destroy');
        // Themes & Blocks
        Route::get('/themes', [\App\Http\Controllers\AdminThemeController::class, 'index'])->name('admin.themes');
        Route::post('/themes/update', [\App\Http\Controllers\AdminThemeController::class, 'update'])->name('admin.themes.update');
        Route::get('/themes/blocks', [\App\Http\Controllers\AdminBlockController::class, 'index'])->name('admin.themes.blocks');
        Route::post('/themes/blocks', [\App\Http\Controllers\AdminBlockController::class, 'update'])->name('admin.themes.blocks.update');
        Route::get('/menu', [\App\Http\Controllers\AdminController::class, 'menu'])->name('admin.menu');
        Route::get('/user-roles', [\App\Http\Controllers\AdminController::class, 'userRoles'])->name('admin.user-roles');
        Route::get('/translate', [\App\Http\Controllers\AdminController::class, 'translate'])->name('admin.translate');
        Route::get('/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('admin.reports');
        Route::patch('/reports/{id}/resolve', [\App\Http\Controllers\AdminController::class, 'reportsResolve'])->name('admin.reports.resolve');
    });
});

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
Route::post('/pages/{id}/stream', [PageController::class, 'postStream'])->name('pages.stream');
Route::get('/pages/{page}/media/{type}', [PageController::class, 'media'])->name('pages.media');
Route::get('/pages/{id}/followers', [PageController::class, 'followers'])->name('pages.followers');

// ==========================================
// 5. VIDEOS ROUTES
// ==========================================
Route::get('/videos', [VideoController::class, 'publicIndex'])->name('videos.public');
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');

// ==========================================
// 6. INVITATION ROUTES
// ==========================================
Route::get('/invitation', [InvitationController::class, 'index'])->name('invitation.index');
Route::post('/invitation/email', [InvitationController::class, 'sendEmail'])->name('invitation.email');
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
    
    // Group Reports
    Route::get('/groups/{group}/reports', [GroupController::class, 'reports'])->name('groups.reports');
    Route::patch('/groups/{group}/reports/{id}/resolve', [GroupController::class, 'reportsResolve'])->name('groups.reports.resolve');
    
    // Group Media Full View
    Route::get('/groups/{group}/media/{type}', [GroupController::class, 'media'])->name('groups.media');
    
    // Group Invites
    Route::get('/groups/{group}/invite', [GroupController::class, 'invite'])->name('groups.invite');
    Route::post('/groups/{group}/invite', [GroupController::class, 'sendInvite'])->name('groups.sendInvite');
    
    // Pending Approval Management
    Route::get('/groups/{group}/pending', [GroupController::class, 'pending'])->name('groups.pending');
    Route::post('/groups/{group}/approve', [GroupController::class, 'approve'])->name('groups.approve');

    // Stream (Wall Post)
    Route::post('/groups/{id}/stream', [GroupController::class, 'postStream'])->name('groups.stream');
    
    // Reports
    Route::post('/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
    
    Route::post('/stream/{id}/like', [GroupController::class, 'likeStream'])->name('stream.like');
    Route::post('/stream/{id}/comment', [GroupController::class, 'commentStream'])->name('stream.comment');
    Route::delete('/stream/{id}', [GroupController::class, 'destroyStream'])->name('stream.destroy');
    Route::put('/comment/{id}', [GroupController::class, 'updateComment'])->name('comment.update');
    Route::delete('/comment/{id}', [GroupController::class, 'destroyComment'])->name('comment.destroy');

    // CRUD Resource Utama
    Route::resource('groups', GroupController::class);
});

// ==========================================
// 8. OTHER ROUTES
// ==========================================
Route::get('/desain-profil', [ProfileDesignController::class, 'index'])->name('desain-profil.index');
Route::get('/undang', [InvitationController::class, 'index'])->name('undang.index');
// ==========================================
// Captcha & Extra Routes (from Remote)
// ==========================================
// Captcha Image Generator
Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha.generate');
