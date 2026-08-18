<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    protected $table = 'jcow_followers';

    protected $fillable = [
        'uid',
        'fid',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'fid');
    }
}