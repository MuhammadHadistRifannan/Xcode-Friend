<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSecurityLog extends Model
{
    use HasFactory;

    protected $table = 'ai_security_logs';

    protected $fillable = [
        'detected_at',
        'attack_type',
        'source_ip',
        'target',
        'severity',
        'ai_analysis',
        'status',
        'ip_blocked',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
            'ip_blocked'  => 'boolean',
            'raw_payload' => 'array',
        ];
    }

    /**
     * Normalisasi level severity menjadi lowercase standar: critical, high, medium, low, info.
     */
    public static function normalizeSeverity(?string $severity): string
    {
        $val = strtolower(trim((string) $severity));

        return match ($val) {
            'critical', 'kritis', 'crit' => 'critical',
            'high', 'tinggi', 'danger' => 'high',
            'medium', 'sedang', 'moderate', 'warning', 'warn' => 'medium',
            'low', 'rendah' => 'low',
            'info', 'informational', 'informasi' => 'info',
            default => 'medium',
        };
    }

    public function getSeverityBadgeAttribute(): array
    {
        return match (strtolower((string) $this->severity)) {
            'critical' => [
                'label' => 'CRITICAL',
                'bg'    => 'bg-red-100',
                'text'  => 'text-red-800',
                'border'=> 'border-red-300',
                'dot'   => 'bg-red-600',
            ],
            'high' => [
                'label' => 'HIGH',
                'bg'    => 'bg-orange-100',
                'text'  => 'text-orange-800',
                'border'=> 'border-orange-300',
                'dot'   => 'bg-orange-500',
            ],
            'medium' => [
                'label' => 'MEDIUM',
                'bg'    => 'bg-amber-100',
                'text'  => 'text-amber-800',
                'border'=> 'border-amber-300',
                'dot'   => 'bg-amber-500',
            ],
            'low' => [
                'label' => 'LOW',
                'bg'    => 'bg-blue-100',
                'text'  => 'text-blue-800',
                'border'=> 'border-blue-300',
                'dot'   => 'bg-blue-500',
            ],
            default => [
                'label' => 'INFO',
                'bg'    => 'bg-gray-100',
                'text'  => 'text-gray-700',
                'border'=> 'border-gray-300',
                'dot'   => 'bg-gray-500',
            ],
        };
    }
}
