<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stream extends Model
{
    use HasFactory;

    protected $table = 'jcow_streams';

    protected $fillable = [
        'message',
        'wall_id',
        'uid',
        'attachment',
        'type',
        'app',
        'aid',
        'hide',
        'likes',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'type' => 'integer',
            'aid' => 'integer',
            'hide' => 'boolean',
            'likes' => 'integer',
            'wall_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'stream_id');
    }

    public function likedBy(): HasMany
    {
        return $this->hasMany(Liked::class, 'stream_id');
    }
}
