<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupPost extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_posts';

    protected $fillable = [
        'gid',
        'tid',
        'uid',
        'username',
        'rtid',
        'rid',
        'rname',
        'message',
        'ip',
        'attach',
        'bbcode_off',
        'emote_off',
        'got_attach',
        'topic',
        'is_first',
        'replies',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'tid' => 'integer',
            'rtid' => 'integer',
            'rid' => 'integer',
            'attach' => 'integer',
            'bbcode_off' => 'boolean',
            'emote_off' => 'boolean',
            'got_attach' => 'boolean',
            'is_first' => 'boolean',
            'replies' => 'integer',
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

    public function topic(): BelongsTo
    {
        return $this->belongsTo(GroupTopic::class, 'tid');
    }
}
