<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumThread extends Model
{
    use HasFactory;

    protected $table = 'jcow_forum_threads';

    protected $fillable = [
        'fid',
        'old_fid',
        'pid',
        'userid',
        'username',
        'topic',
        'views',
        'posts',
        'closed',
        'lastpostusername',
        'lastpostcreated',
        'icon',
        'thread_type',
        'thread_lock',
        'got_poll',
        'got_attach',
        'stressed',
        'digg',
        'dugg',
        'votes',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'old_fid' => 'integer',
            'pid' => 'integer',
            'views' => 'integer',
            'posts' => 'integer',
            'closed' => 'boolean',
            'lastpostcreated' => 'integer',
            'icon' => 'integer',
            'thread_type' => 'integer',
            'thread_lock' => 'boolean',
            'got_poll' => 'boolean',
            'got_attach' => 'boolean',
            'stressed' => 'boolean',
            'digg' => 'integer',
            'dugg' => 'integer',
        ];
    }

    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'fid');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'userid');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'tid');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ForumAttachment::class, 'tid');
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(ForumPoll::class, 'id', 'tid');
    }
}
