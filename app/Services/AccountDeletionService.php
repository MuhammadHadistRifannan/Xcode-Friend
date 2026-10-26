<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AccountDeletionService
{
    /**
     * Hapus akun pengguna secara lengkap dan permanen beserta seluruh file dan data terkait.
     */
    public function deleteAccount(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $userId = (int) $user->id;

            // 1. Bersihkan seluruh data turunan, konten, dan file fisik milik akun
            $this->deleteUserData($userId, $user);

            // 2. Hapus entitas akun dari tabel jcow_accounts
            DB::table('jcow_accounts')->where('id', $userId)->delete();

            return true;
        });
    }

    /**
     * Membersihkan seluruh data, relasi, konten, dan file fisik yang dimiliki oleh user ID tertentu.
     */
    public function deleteUserData(int $userId, ?User $user = null): void
    {
        if ($userId <= 0) {
            return;
        }

        // Ambil data user jika belum diberikan
        if (!$user) {
            $user = User::find($userId);
        }

        $userEmail = $user ? $user->email : (string) DB::table('jcow_accounts')->where('id', $userId)->value('email');
        $userAvatar = $user ? $user->avatar : (string) DB::table('jcow_accounts')->where('id', $userId)->value('avatar');

        // =========================================================================
        // A. PENGHAPUSAN FILE MEDIA FISIK MILIK PENGGUNA
        // =========================================================================

        // 1. Avatar User
        if (!empty($userAvatar)) {
            $this->deleteFileSafely($userAvatar, 'avatars');
        }

        // 2. Profile Design (Wallpaper, Cover, Background, Music)
        if (Schema::hasTable('jcow_profiles')) {
            $profile = DB::table('jcow_profiles')->where('id', $userId)->first();
            if ($profile) {
                if (!empty($profile->background)) {
                    $this->deleteFileSafely($profile->background, 'backgrounds');
                }
                if (!empty($profile->custom_css)) {
                    $theme = json_decode($profile->custom_css, true);
                    if (is_array($theme)) {
                        if (!empty($theme['bg_image'])) {
                            $this->deleteFileSafely($theme['bg_image'], 'backgrounds');
                        }
                        if (!empty($theme['profile_music'])) {
                            $this->deleteFileSafely($theme['profile_music'], 'music');
                        }
                    }
                }
            }
        }

        // 3. Album & Foto (jcow_story_categories & jcow_story_photos)
        $albumIds = [];
        if (Schema::hasTable('jcow_story_categories')) {
            $albumIds = DB::table('jcow_story_categories')
                ->where('gid', $userId)
                ->pluck('id')
                ->toArray();
        }

        if (!empty($albumIds) && Schema::hasTable('jcow_story_photos')) {
            $photos = DB::table('jcow_story_photos')
                ->whereIn('sid', $albumIds)
                ->get();

            foreach ($photos as $photo) {
                if (!empty($photo->uri)) {
                    $this->deleteFileSafely($photo->uri, 'photos');
                }
                if (!empty($photo->thumb)) {
                    $this->deleteFileSafely($photo->thumb, 'photos');
                }
            }

            // Hapus record foto dalam album milik user
            DB::table('jcow_story_photos')->whereIn('sid', $albumIds)->delete();
        }

        // 4. Video & Stories (jcow_stories)
        if (Schema::hasTable('jcow_stories')) {
            $stories = DB::table('jcow_stories')
                ->where('uid', $userId)
                ->orWhere(function ($q) use ($albumIds) {
                    if (!empty($albumIds)) {
                        $q->whereIn('cid', $albumIds);
                    }
                })
                ->get();

            foreach ($stories as $story) {
                if (!empty($story->thumbnail)) {
                    $this->deleteFileSafely($story->thumbnail, 'videos');
                }
            }
        }

        // 5. Streams & Status Attachments (jcow_streams)
        $userStreams = collect();
        $wallStreams = collect();

        if (Schema::hasTable('jcow_streams')) {
            // Ambil semua stream buatan pengguna
            $userStreams = DB::table('jcow_streams')->where('uid', $userId)->get();
            foreach ($userStreams as $stream) {
                if ($stream->app === 'music' && !empty($stream->attachment)) {
                    $this->deleteFileSafely($stream->attachment, 'music');
                }
                if (!empty($stream->attachment)) {
                    $this->deleteStreamAttachments($stream->attachment);
                }
            }

            // Ambil semua stream di wall profil pengguna yang dibuat oleh user lain
            $wallStreams = DB::table('jcow_streams')
                ->where('app', 'feed')
                ->where('wall_id', $userId)
                ->where('uid', '!=', $userId)
                ->get();

            foreach ($wallStreams as $wStream) {
                if (!empty($wStream->attachment)) {
                    $this->deleteStreamAttachments($wStream->attachment);
                }
            }
        }

        // 6. Chat Message Attachments
        if (Schema::hasTable('jcow_messages')) {
            $messagesWithAttachments = DB::table('jcow_messages')
                ->where('from_id', $userId)
                ->whereNotNull('attachment')
                ->where('attachment', '!=', '')
                ->pluck('attachment');

            foreach ($messagesWithAttachments as $chatAtt) {
                $this->deleteFileSafely($chatAtt, 'chat_attachments');
            }
        }

        // 7. Pages & Groups yang dibuat oleh pengguna (Logo & Background)
        if (Schema::hasTable('jcow_pages')) {
            $userPages = DB::table('jcow_pages')->where('uid', $userId)->get();
            foreach ($userPages as $p) {
                if (!empty($p->logo)) {
                    $this->deleteFileSafely($p->logo, 'pages_logo');
                }
                if (!empty($p->background)) {
                    $this->deleteFileSafely($p->background, 'backgrounds');
                }
            }
        }

        // =========================================================================
        // B. PENGHAPUSAN RECORD DATABASE (KONTEN & RELASI)
        // =========================================================================

        $userStreamIds = $userStreams->pluck('id')->toArray();
        $wallStreamIds = $wallStreams->pluck('id')->toArray();
        $allTargetStreamIds = array_unique(array_merge($userStreamIds, $wallStreamIds));

        // 1. Likes / Reaksi yang diberikan oleh User A pada postingan orang lain
        if (Schema::hasTable('jcow_liked')) {
            $likedStreamIds = DB::table('jcow_liked')
                ->where('uid', $userId)
                ->pluck('stream_id')
                ->toArray();

            if (!empty($likedStreamIds) && Schema::hasTable('jcow_streams')) {
                // Turunkan counter like pada postingan pemilik asli (tanpa merusak post pemilik asli)
                foreach ($likedStreamIds as $sid) {
                    DB::table('jcow_streams')
                        ->where('id', $sid)
                        ->where('likes', '>', 0)
                        ->decrement('likes');
                }
            }
            DB::table('jcow_liked')->where('uid', $userId)->delete();

            // Hapus likes yang ada pada postingan User A sendiri
            if (!empty($allTargetStreamIds)) {
                DB::table('jcow_liked')->whereIn('stream_id', $allTargetStreamIds)->delete();
            }
        }

        // 2. Komentar yang ditulis oleh User A pada postingan manapun
        if (Schema::hasTable('jcow_comments')) {
            DB::table('jcow_comments')->where('uid', $userId)->delete();

            // Hapus komentar pada postingan User A sendiri
            if (!empty($allTargetStreamIds)) {
                DB::table('jcow_comments')->whereIn('stream_id', $allTargetStreamIds)->delete();
            }
        }

        // Komentar dinding profil (jcow_profile_comments)
        if (Schema::hasTable('jcow_profile_comments')) {
            DB::table('jcow_profile_comments')
                ->where('uid', $userId)
                ->orWhere('target_id', $userId)
                ->delete();
        }

        // 3. Postingan Stream milik User A & di Dinding User A
        if (Schema::hasTable('jcow_streams')) {
            DB::table('jcow_streams')->where('uid', $userId)->delete();
            DB::table('jcow_streams')->where('app', 'feed')->where('wall_id', $userId)->delete();
        }

        // 4. Video & Stories (jcow_stories)
        if (Schema::hasTable('jcow_stories')) {
            DB::table('jcow_stories')->where('uid', $userId)->delete();
            if (!empty($albumIds)) {
                DB::table('jcow_stories')->whereIn('cid', $albumIds)->delete();
            }
        }

        // 5. Album Kategori (jcow_story_categories)
        if (Schema::hasTable('jcow_story_categories')) {
            DB::table('jcow_story_categories')->where('gid', $userId)->delete();
        }

        // 6. Pages & Groups yang dibuat oleh pengguna
        if (Schema::hasTable('jcow_pages')) {
            $ownedPageIds = DB::table('jcow_pages')->where('uid', $userId)->pluck('id')->toArray();
            if (!empty($ownedPageIds)) {
                if (Schema::hasTable('jcow_streams')) {
                    DB::table('jcow_streams')->whereIn('wall_id', $ownedPageIds)->whereIn('app', ['page', 'group'])->delete();
                }
                DB::table('jcow_pages')->whereIn('id', $ownedPageIds)->delete();
            }
        }
        if (Schema::hasTable('jcow_groups')) {
            DB::table('jcow_groups')->where('uid', $userId)->delete();
        }

        // Keanggotaan grup/page milik pengguna
        if (Schema::hasTable('jcow_group_members')) {
            DB::table('jcow_group_members')->where('uid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_group_members_pending')) {
            DB::table('jcow_group_members_pending')->where('uid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_page_users')) {
            DB::table('jcow_page_users')->where('uid', $userId)->delete();
        }

        // 7. Relasi Pertemanan, Pengikut, dan Blacklist
        if (Schema::hasTable('jcow_friends')) {
            DB::table('jcow_friends')->where('uid', $userId)->orWhere('fid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_friend_reqs')) {
            DB::table('jcow_friend_reqs')->where('uid', $userId)->orWhere('fid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_followers')) {
            DB::table('jcow_followers')->where('uid', $userId)->orWhere('fid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_blacks')) {
            DB::table('jcow_blacks')->where('uid', $userId)->orWhere('bid', $userId)->delete();
        }

        // 8. Pesan Pribadi & Notifikasi
        if (Schema::hasTable('jcow_messages')) {
            DB::table('jcow_messages')->where('from_id', $userId)->orWhere('to_id', $userId)->delete();
        }
        if (Schema::hasTable('jcow_messages_sent')) {
            DB::table('jcow_messages_sent')->where('from_id', $userId)->orWhere('to_id', $userId)->delete();
        }
        if (Schema::hasTable('jcow_messages_hidden')) {
            DB::table('jcow_messages_hidden')->where('user_id', $userId)->delete();
        }

        // 9. Laporan dan Spam Log
        if (Schema::hasTable('jcow_reports')) {
            DB::table('jcow_reports')->where('uid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_spam_log')) {
            DB::table('jcow_spam_log')->where('user_id', $userId)->delete();
        }

        // 10. Profil Metadata
        if (Schema::hasTable('jcow_profiles')) {
            DB::table('jcow_profiles')->where('id', $userId)->delete();
        }

        // 11. Forum Legacy (jika ada)
        if (Schema::hasTable('jcow_forum_posts')) {
            DB::table('jcow_forum_posts')->where('uid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_forum_threads')) {
            DB::table('jcow_forum_threads')->where('uid', $userId)->delete();
        }
        if (Schema::hasTable('jcow_forum_subscribes')) {
            DB::table('jcow_forum_subscribes')->where('uid', $userId)->delete();
        }

        // 12. Sesi & Reset Token
        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->where('user_id', $userId)->delete();
        }
        if (!empty($userEmail) && Schema::hasTable('password_reset_tokens')) {
            DB::table('password_reset_tokens')->where('email', $userEmail)->delete();
        }
    }

    /**
     * Membersihkan sisa data 'orphan' (postingan, video, foto, album, komentar)
     * yang pemiliknya sudah tidak ada di jcow_accounts.
     */
    public function purgeOrphans(): array
    {
        $stats = [
            'streams'  => 0,
            'stories'  => 0,
            'photos'   => 0,
            'albums'   => 0,
            'comments' => 0,
            'likes'    => 0,
        ];

        // 1. Hapus orphan streams (di mana uid bukan user aktif atau uid = 0)
        if (Schema::hasTable('jcow_streams') && Schema::hasTable('jcow_accounts')) {
            $orphanStreams = DB::table('jcow_streams')
                ->leftJoin('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_streams.uid')
                ->where(function ($q) {
                    $q->whereNull('jcow_accounts.id')
                      ->orWhere('jcow_streams.uid', 0);
                })
                ->select('jcow_streams.id', 'jcow_streams.attachment', 'jcow_streams.app')
                ->get();

            if ($orphanStreams->isNotEmpty()) {
                $orphanIds = $orphanStreams->pluck('id')->toArray();
                foreach ($orphanStreams as $os) {
                    if (!empty($os->attachment)) {
                        $this->deleteStreamAttachments((string) $os->attachment);
                    }
                }
                if (Schema::hasTable('jcow_liked')) {
                    DB::table('jcow_liked')->whereIn('stream_id', $orphanIds)->delete();
                }
                if (Schema::hasTable('jcow_comments')) {
                    DB::table('jcow_comments')->whereIn('stream_id', $orphanIds)->delete();
                }
                DB::table('jcow_streams')->whereIn('id', $orphanIds)->delete();
                $stats['streams'] = count($orphanIds);
            }
        }

        // 2. Hapus orphan stories / videos
        if (Schema::hasTable('jcow_stories') && Schema::hasTable('jcow_accounts')) {
            $orphanStories = DB::table('jcow_stories')
                ->leftJoin('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_stories.uid')
                ->where(function ($q) {
                    $q->whereNull('jcow_accounts.id')
                      ->orWhere('jcow_stories.uid', 0);
                })
                ->select('jcow_stories.id', 'jcow_stories.thumbnail')
                ->get();

            if ($orphanStories->isNotEmpty()) {
                $storyIds = $orphanStories->pluck('id')->toArray();
                foreach ($orphanStories as $ost) {
                    if (!empty($ost->thumbnail)) {
                        $this->deleteFileSafely((string) $ost->thumbnail, 'videos');
                    }
                }
                DB::table('jcow_stories')->whereIn('id', $storyIds)->delete();
                $stats['stories'] = count($storyIds);
            }
        }

        // 3. Hapus orphan categories / albums
        if (Schema::hasTable('jcow_story_categories') && Schema::hasTable('jcow_accounts')) {
            $orphanAlbums = DB::table('jcow_story_categories')
                ->leftJoin('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_story_categories.gid')
                ->where(function ($q) {
                    $q->whereNull('jcow_accounts.id')
                      ->orWhere('jcow_story_categories.gid', 0);
                })
                ->pluck('jcow_story_categories.id')
                ->toArray();

            if (!empty($orphanAlbums)) {
                if (Schema::hasTable('jcow_story_photos')) {
                    $orphanPhotos = DB::table('jcow_story_photos')
                        ->whereIn('sid', $orphanAlbums)
                        ->get();

                    foreach ($orphanPhotos as $op) {
                        if (!empty($op->uri)) $this->deleteFileSafely((string) $op->uri, 'photos');
                        if (!empty($op->thumb)) $this->deleteFileSafely((string) $op->thumb, 'photos');
                    }
                    DB::table('jcow_story_photos')->whereIn('sid', $orphanAlbums)->delete();
                    $stats['photos'] = count($orphanPhotos);
                }
                DB::table('jcow_story_categories')->whereIn('id', $orphanAlbums)->delete();
                $stats['albums'] = count($orphanAlbums);
            }
        }

        // 4. Hapus orphan comments
        if (Schema::hasTable('jcow_comments') && Schema::hasTable('jcow_accounts')) {
            $orphanComments = DB::table('jcow_comments')
                ->leftJoin('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_comments.uid')
                ->where(function ($q) {
                    $q->whereNull('jcow_accounts.id')
                      ->orWhere('jcow_comments.uid', 0);
                })
                ->pluck('jcow_comments.id')
                ->toArray();

            if (!empty($orphanComments)) {
                DB::table('jcow_comments')->whereIn('id', $orphanComments)->delete();
                $stats['comments'] = count($orphanComments);
            }
        }

        // 5. Hapus orphan likes
        if (Schema::hasTable('jcow_liked') && Schema::hasTable('jcow_accounts')) {
            $orphanLikes = DB::table('jcow_liked')
                ->leftJoin('jcow_accounts', 'jcow_accounts.id', '=', 'jcow_liked.uid')
                ->where(function ($q) {
                    $q->whereNull('jcow_accounts.id')
                      ->orWhere('jcow_liked.uid', 0);
                })
                ->pluck('jcow_liked.id')
                ->toArray();

            if (!empty($orphanLikes)) {
                DB::table('jcow_liked')->whereIn('id', $orphanLikes)->delete();
                $stats['likes'] = count($orphanLikes);
            }
        }

        return $stats;
    }

    /**
     * Menghapus satu berkas media secara aman dari storage/public tanpa merusak folder atau file user lain.
     */
    public function deleteFileSafely(?string $filepath, string $defaultFolder = ''): void
    {
        if (empty($filepath)) {
            return;
        }

        // Jangan pernah menghapus link eksternal (YouTube, CDN, URL web)
        if (str_starts_with($filepath, 'http://') || str_starts_with($filepath, 'https://')) {
            return;
        }

        // Cegah path traversal
        if (str_contains($filepath, '..')) {
            return;
        }

        $cleanPath = ltrim(trim($filepath), '/');
        $filename = basename($cleanPath);

        // Jangan pernah menghapus gambar default sistem
        if (in_array(strtolower($filename), ['default.png', 'hero-banner.jpg', 'no-cover.png', 'no-avatar.png'])) {
            return;
        }

        $candidates = [
            $cleanPath,
            str_replace(['storage/', 'uploads/'], '', $cleanPath),
            $defaultFolder ? $defaultFolder . '/' . $filename : $filename,
            $filename,
        ];

        foreach (array_unique($candidates) as $cand) {
            if (empty($cand)) continue;

            // 1. Coba hapus via Storage disk 'public'
            try {
                if (Storage::disk('public')->exists($cand)) {
                    Storage::disk('public')->delete($cand);
                }
            } catch (\Throwable $e) {
                // Abaikan jika file tidak ada di storage disk
            }

            // 2. Coba hapus via public_path('uploads/...')
            $absUploadsPath = public_path('uploads/' . $cand);
            if (file_exists($absUploadsPath) && is_file($absUploadsPath)) {
                @unlink($absUploadsPath);
            }

            // 3. Coba hapus via public_path(...)
            $absPath = public_path($cand);
            if (file_exists($absPath) && is_file($absPath)) {
                @unlink($absPath);
            }
        }
    }

    /**
     * Mengurai lampiran postingan stream (JSON / Serialized) dan menghapus berkas fotonya.
     */
    private function deleteStreamAttachments(string $attachment): void
    {
        if (empty($attachment)) return;

        // 1. Format JSON modern
        $json = json_decode($attachment, true);
        if (is_array($json)) {
            if (isset($json['photos']) && is_array($json['photos'])) {
                foreach ($json['photos'] as $p) {
                    $this->deleteFileSafely((string) $p, 'posts');
                }
            }
            return;
        }

        // 2. Format PHP Serialized legacy JCow
        $unserialized = @unserialize($attachment);
        if (is_array($unserialized)) {
            if (isset($unserialized['thumb']) && is_array($unserialized['thumb'])) {
                foreach ($unserialized['thumb'] as $t) {
                    $this->deleteFileSafely((string) $t, 'posts');
                }
            }
            if (isset($unserialized['photos']) && is_array($unserialized['photos'])) {
                foreach ($unserialized['photos'] as $p) {
                    $this->deleteFileSafely((string) $p, 'posts');
                }
            }
        }
    }
}
