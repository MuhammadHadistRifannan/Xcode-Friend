<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumPost extends Model
{
    use HasFactory;

    protected $table = 'jcow_forum_posts';

    protected $fillable = [
        'tid',
        'uid',
        'title',
        'message',
        'ip',
        'is_first',
        'attach',
        'bbcode_off',
        'emote_off',
        'got_attach',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'is_first' => 'boolean',
            'attach' => 'integer',
            'bbcode_off' => 'boolean',
            'emote_off' => 'boolean',
            'got_attach' => 'boolean',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'tid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ForumAttachment::class, 'pid');
    }
}
