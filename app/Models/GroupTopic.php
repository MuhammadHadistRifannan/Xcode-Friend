<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupTopic extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_topics';

    protected $fillable = [
        'gid',
        'old_fid',
        'pid',
        'uid',
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'gid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(GroupPost::class, 'tid');
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(GroupPoll::class, 'id', 'tid');
    }
}
