<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'jcow_groups';

    protected $fillable = [
        'uri',
        'name',
        'slogan',
        'creatorid',
        'creator',
        'description',
        'members',
        'logo',
        'private',
        'needapproval',
        'posts',
        'topics',
        'lastptime',
        'lastpname',
        'password',
        'custom_css',
        'style_ids',
        'category',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'members' => 'integer',
            'private' => 'boolean',
            'needapproval' => 'boolean',
            'posts' => 'integer',
            'topics' => 'integer',
            'lastptime' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'creatorid');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GroupCategory::class, 'category');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'gid');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(GroupTopic::class, 'gid');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(GroupPost::class, 'gid');
    }

    public function postcats(): HasMany
    {
        return $this->hasMany(GroupPostcat::class, 'gid');
    }
}
