<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupPoll extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_polls';

    protected $fillable = [
        'tid',
        'question',
        'options',
        'timeout',
        'options_per_user',
        'voters',
        'total',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'timeout' => 'integer',
            'options_per_user' => 'integer',
            'total' => 'integer',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(GroupTopic::class, 'tid');
    }
}
