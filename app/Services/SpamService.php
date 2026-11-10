<?php

namespace App\Services;

use App\Helpers\SettingHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpamService
{
    private const SPAM_THRESHOLD = 5;          // Batas jumlah pelanggaran spam sebelum suspend otomatis
    private const TIME_WINDOW = 60;             // Jendela waktu (detik) untuk perhitungan auto-ban dan deteksi duplikat
    private const MAX_LINKS = 2;                // Batas maksimal tautan eksternal per pesan (3 atau lebih ditandai spam)
    private const MAX_ATTACHMENTS_WINDOW = 30;  // Jendela waktu (detik) untuk frekuensi lampiran
    private const MAX_ATTACHMENTS_RATE = 5;     // Batas maksimal lampiran dalam jendela waktu

    /**
     * Kata-kata percakapan pendek yang dikecualikan dari deteksi duplikat
     */
    private const DUPLICATE_WHITELIST = [
        'oke', 'ok', 'iya', 'ya', 'tidak', 'nggak', 'gak', 'bukan',
        'makasih', 'terima kasih', 'thanks', 'thx', 'siap', 'siap bos',
        'halo', 'hai', 'hello', 'hi', 'p', 'test', 'tes', 'ping',
        'mantap', 'sip', 'haha', 'hahaha', 'wkwk', 'wkwkwk', 'hehe',
        'siap min', 'baik', 'bisa', 'siap kak', 'pagi', 'siang', 'sore', 'malam'
    ];

    /**
     * Memeriksa apakah pesan/postingan yang dikirim terindikasi sebagai spam.
     *
     * @param int $userId
     * @param string $message
     * @param string|null $attachment
     * @return bool
     */
    public function recordThisPosting(int $userId, string $message, ?string $attachment = null): bool
    {
        $isSpam = false;
        $reasons = [];
        $trimmedMessage = trim($message);

        // 1. Deteksi tautan berlebih (external links >= 3)
        if (!empty($trimmedMessage)) {
            $linkCount = $this->countExternalLinks($trimmedMessage);
            if ($linkCount > self::MAX_LINKS) {
                $isSpam = true;
                $reasons[] = 'too_many_links';
            }
        }

        // 2. Integrasi kata terlarang (banned words) dari konfigurasi admin
        if (!empty($trimmedMessage)) {
            $bannedWords = $this->getBannedWords();
            foreach ($bannedWords as $word) {
                if (empty($word)) {
                    continue;
                }
                // Menggunakan batas kata (\b) dengan flag Unicode dan Case-Insensitive (/iu)
                // agar tidak terjadi false positive pada substring kata normal
                $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
                if (@preg_match($pattern, $trimmedMessage)) {
                    $isSpam = true;
                    $reasons[] = 'banned_word';
                    break;
                }
            }
        }

        // 3. Deteksi pesan identik/duplikat berulang dalam waktu singkat
        if (!empty($trimmedMessage) && $this->isDuplicateMessage($userId, $trimmedMessage)) {
            $isSpam = true;
            $reasons[] = 'duplicate_message';
        }

        // 4. Deteksi flooding pengiriman lampiran gambar
        if (!empty($attachment) && $this->isAttachmentFlooding($userId)) {
            $isSpam = true;
            $reasons[] = 'attachment_flooding';
        }

        // Jika terindikasi spam, simpan log dan periksa ambang batas auto-ban
        if ($isSpam) {
            $loggedMessage = !empty($trimmedMessage) ? $trimmedMessage : ('[Lampiran Foto: ' . basename($attachment) . ']');
            $this->logSpam($userId, $loggedMessage, $reasons);
            $this->checkAndBanUser($userId);
            return true;
        }

        return false;
    }

    /**
     * Menghitung tautan eksternal dalam pesan dengan mengecualikan host internal/sendiri.
     */
    private function countExternalLinks(string $message): int
    {
        $pattern = '/(?:https?:\/\/|www\.)[^\s<>"\'{}|\\^`]+[^\s<>"\'{}|\\^`.,;:?!]/iu';
        if (!preg_match_all($pattern, $message, $matches)) {
            return 0;
        }

        $urls = $matches[0] ?? [];
        $externalCount = 0;

        $currentHost = '';
        try {
            $currentHost = request()->getHost();
        } catch (\Exception $e) {}

        $internalDomains = array_filter([$currentHost, 'localhost', '127.0.0.1', 'xcode-friends.com', 'xcode.co.id']);

        foreach ($urls as $url) {
            $urlToParse = str_starts_with(strtolower($url), 'www.') ? ('http://' . $url) : $url;
            $parsedHost = parse_url($urlToParse, PHP_URL_HOST);
            $isInternal = false;

            if ($parsedHost) {
                foreach ($internalDomains as $domain) {
                    if (strcasecmp($parsedHost, $domain) === 0 || str_ends_with(strtolower($parsedHost), '.' . strtolower($domain))) {
                        $isInternal = true;
                        break;
                    }
                }
            }

            if (!$isInternal) {
                $externalCount++;
            }
        }

        return $externalCount;
    }

    /**
     * Mengambil daftar kata terlarang dari konfigurasi words_filter di jcow_texts (via SettingHelper).
     */
    private function getBannedWords(): array
    {
        $bannedWords = [];

        // 1. Ambil dari pengaturan resmi Admin Panel (words_filter)
        $wordsFilter = SettingHelper::get('words_filter', '');
        if (!empty($wordsFilter) && is_string($wordsFilter)) {
            $parts = preg_split('/[\r\n,]+/', $wordsFilter);
            foreach ($parts as $p) {
                $trimmed = trim($p);
                if ($trimmed !== '') {
                    $bannedWords[] = $trimmed;
                }
            }
        }

        // 2. Fallback jika terdapat legacy key spam_banned_words
        try {
            $legacy = SettingHelper::get('spam_banned_words');
            if (!empty($legacy)) {
                if (is_string($legacy)) {
                    $decoded = json_decode($legacy, true);
                    if (is_array($decoded)) {
                        $bannedWords = array_merge($bannedWords, $decoded);
                    }
                } elseif (is_array($legacy)) {
                    $bannedWords = array_merge($bannedWords, $legacy);
                }
            }
        } catch (\Exception $e) {}

        return array_values(array_unique(array_filter($bannedWords)));
    }

    /**
     * Memeriksa apakah pesan merupakan duplikasi berulang dalam jendela waktu tertentu.
     */
    private function isDuplicateMessage(int $userId, string $message): bool
    {
        $normalized = mb_strtolower(trim(preg_replace('/\s+/u', ' ', $message)));
        $length = mb_strlen($normalized);

        // Jangan tandai pesan pendek (<= 10 karakter) atau kata percakapan umum
        if ($length <= 10 || in_array($normalized, self::DUPLICATE_WHITELIST, true)) {
            return false;
        }

        try {
            $recentSent = DB::table('jcow_messages_sent')
                ->where('from_id', $userId)
                ->where('created', '>=', time() - self::TIME_WINDOW)
                ->select('message', 'to_id', 'created')
                ->orderBy('created', 'desc')
                ->limit(10)
                ->get();

            if ($recentSent->isEmpty()) {
                return false;
            }

            $identicalCount = 0;
            $now = time();

            foreach ($recentSent as $sent) {
                $sentNormalized = mb_strtolower(trim(preg_replace('/\s+/u', ' ', (string) ($sent->message ?? ''))));
                if ($sentNormalized === $normalized) {
                    $identicalCount++;
                    $secondsAgo = $now - (int) $sent->created;

                    // Mengirimkan pesan identik dalam waktu kurang dari 10 detik dianggap spam flooding
                    if ($secondsAgo < 10) {
                        return true;
                    }
                }
            }

            // Pesan identik (> 10 karakter) yang dikirim berulang 2 kali atau lebih dalam 60 detik dianggap spam
            if ($identicalCount >= 2) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Memeriksa frekuensi pengiriman lampiran gambar (flooding attachment).
     */
    private function isAttachmentFlooding(int $userId): bool
    {
        try {
            $recentAttachments = DB::table('jcow_messages_sent')
                ->where('from_id', $userId)
                ->whereNotNull('attachment')
                ->where('attachment', '!=', '')
                ->where('created', '>=', time() - self::MAX_ATTACHMENTS_WINDOW)
                ->count();

            return $recentAttachments >= self::MAX_ATTACHMENTS_RATE;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Mencatat pelanggaran spam ke database jcow_spam_log.
     */
    private function logSpam(int $userId, string $message, array $reasons): void
    {
        try {
            DB::table('jcow_spam_log')->insert([
                'user_id' => $userId,
                'message' => mb_substr($message, 0, 1000),
                'reasons' => implode(', ', $reasons),
                'created' => time(),
            ]);
        } catch (\Exception $e) {}
    }

    /**
     * Memeriksa ambang batas pelanggaran untuk melakukan suspend akun jika diperlukan.
     */
    private function checkAndBanUser(int $userId): void
    {
        try {
            $recentSpamCount = DB::table('jcow_spam_log')
                ->where('user_id', $userId)
                ->where('created', '>=', time() - self::TIME_WINDOW)
                ->count();

            if ($recentSpamCount >= self::SPAM_THRESHOLD) {
                $this->banUser($userId);
            }
        } catch (\Exception $e) {}
    }

    /**
     * Mensuspend akun pengguna yang melanggar (disabled = 2) tanpa merusak audit log.
     */
    private function banUser(int $userId): void
    {
        try {
            $user = DB::table('jcow_accounts')->where('id', $userId)->first();
            if (!$user) {
                return;
            }

            // PERLINDUNGAN: Jangan pernah auto-ban akun Administrator atau Moderator
            $roleLower = strtolower(trim((string) ($user->roles ?? '')));
            if ((int) ($user->level ?? 0) === 1 || $roleLower === '3' || in_array($roleLower, ['admin', 'administrator', 'moderator'], true)) {
                return;
            }

            // Hindari pembaruan jika akun sudah disuspend sebelumnya
            if ((int) ($user->disabled ?? 0) === 2) {
                return;
            }

            // Terapkan suspend (disabled = 2) sesuai mekanisme native Xcode-Friends
            DB::table('jcow_accounts')->where('id', $userId)->update([
                'disabled' => 2,
            ]);

            // Catat ke tabel jcow_banned untuk rekam jejak historis jika tabel tersedia
            if (Schema::hasTable('jcow_banned')) {
                $alreadyBanned = DB::table('jcow_banned')->where('uid', $userId)->exists();
                if (!$alreadyBanned) {
                    DB::table('jcow_banned')->insert([
                        'ip' => request()->ip() ?? '127.0.0.1',
                        'uid' => $userId,
                        'email' => $user->email ?? '',
                        'created' => time(),
                    ]);
                }
            }

            // CATATAN KEAMANAN & AUDIT:
            // Riwayat jcow_spam_log sengaja TIDAK dihapus agar administrator tetap memiliki bukti audit.
        } catch (\Exception $e) {}
    }
}
