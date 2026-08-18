<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chatroom extends Model
{
    use HasFactory;

    protected $table = 'jcow_chatrooms';

    protected $fillable = [
        'uid',
        'fid',
        'content',
        'request_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'request_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'fid');
    }
}
