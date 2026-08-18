<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumAttachment extends Model
{
    use HasFactory;

    protected $table = 'jcow_forum_attachments';

    protected $fillable = [
        'pid',
        'tid',
        'uri',
        'des',
        'size',
        'orginal_name',
        'downloads',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'downloads' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'pid');
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'tid');
    }
}
