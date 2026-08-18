<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_members';

    protected $fillable = [
        'gid',
        'uid',
        'nickname',
        'about_me',
        'hide_profile',
        'status',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'hide_profile' => 'boolean',
            'status' => 'integer',
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
}
