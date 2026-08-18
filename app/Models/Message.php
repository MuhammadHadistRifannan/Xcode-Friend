<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $table = 'jcow_messages';

    protected $fillable = [
        'from_id',
        'to_id',
        'subject',
        'message',
        'hasread',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'hasread' => 'boolean',
        ];
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_id');
    }
}
