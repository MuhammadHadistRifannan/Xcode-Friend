<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'jcow_friend_reqs';

    protected $fillable = [
        'uid',
        'fid',
        'msg',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'fid');
    }
}