<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    use HasFactory;

    protected $table = 'jcow_forums';

    protected $fillable = [
        'weight',
        'parent_id',
        'name',
        'type_pic',
        'description',
        'rules',
        'forum_type',
        'threads',
        'posts',
        'lastpostname',
        'lastposttopicid',
        'lastposttopic',
        'lastpostcreated',
        'moderator',
        'settings',
        'fmembers',
        'image',
        'read_roles',
        'upload_roles',
        'thread_roles',
        'reply_roles',
        'moderators',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
            'parent_id' => 'integer',
            'threads' => 'integer',
            'posts' => 'integer',
            'lastposttopicid' => 'integer',
            'lastpostcreated' => 'integer',
            'fmembers' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Forum::class, 'parent_id');
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'fid');
    }
}
