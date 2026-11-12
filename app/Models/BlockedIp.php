<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class BlockedIp extends Model
{
    use HasFactory;

    protected $table = 'blocked_ips';

    protected $fillable = [
        'ip_address',
        'reason',
        'ai_security_log_id',
        'blocked_by',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public static function isBlocked(?string $ip): bool
    {
        if (empty($ip) || in_array($ip, ['127.0.0.1', '::1'], true)) {
            return false;
        }

        try {
            $blockedList = Cache::remember('security:blocked_ips', 60, function () {
                if (!Schema::hasTable('blocked_ips')) {
                    return [];
                }
                return static::query()
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    })
                    ->pluck('ip_address')
                    ->all();
            });

            return in_array($ip, $blockedList, true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function clearBlockedCache(): void
    {
        Cache::forget('security:blocked_ips');
    }
}
