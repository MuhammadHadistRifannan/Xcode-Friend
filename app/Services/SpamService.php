<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SpamService
{
    private const SPAM_THRESHOLD = 5;
    private const TIME_WINDOW = 60;
    private const MAX_LINKS = 5;

    public function recordThisPosting(int $userId, string $message): bool
    {
        $isSpam = false;
        $reasons = [];

        $linkCount = preg_match_all('/https?:\/\//', $message);
        if ($linkCount > self::MAX_LINKS) {
            $isSpam = true;
            $reasons[] = 'too_many_links';
        }

        $bannedWords = $this->getBannedWords();
        $messageLower = mb_strtolower($message);
        foreach ($bannedWords as $word) {
            if (mb_strpos($messageLower, mb_strtolower($word)) !== false) {
                $isSpam = true;
                $reasons[] = 'banned_word';
                break;
            }
        }

        if ($isSpam) {
            $this->logSpam($userId, $message, $reasons);
            $this->checkAndBanUser($userId);
            return true;
        }

        return false;
    }

    private function getBannedWords(): array
    {
        $result = DB::table('jcow_gvars')
            ->where('gkey', 'spam_banned_words')
            ->first();

        if ($result && $result->gvalue) {
            return json_decode($result->gvalue, true) ?? [];
        }

        return [];
    }

    private function logSpam(int $userId, string $message, array $reasons): void
    {
        DB::table('jcow_spam_log')->insert([
            'user_id' => $userId,
            'message' => $message,
            'reasons' => json_encode($reasons),
            'created' => time(),
        ]);
    }

    private function checkAndBanUser(int $userId): void
    {
        $recentSpamCount = DB::table('jcow_spam_log')
            ->where('user_id', $userId)
            ->where('created', '>=', time() - self::TIME_WINDOW)
            ->count();

        if ($recentSpamCount >= self::SPAM_THRESHOLD) {
            $this->banUser($userId);
        }
    }

    private function banUser(int $userId): void
    {
        $user = DB::table('jcow_accounts')->where('id', $userId)->first();
        if (!$user) {
            return;
        }

        DB::table('jcow_banned')->insert([
            'ip' => request()->ip(),
            'uid' => $userId,
            'email' => $user->email ?? '',
            'created' => time(),
        ]);

        DB::table('jcow_spam_log')
            ->where('user_id', $userId)
            ->delete();
    }
}
