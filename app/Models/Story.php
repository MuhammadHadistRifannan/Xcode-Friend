<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    use HasFactory;

    protected $table = 'jcow_stories';

    protected $fillable = [
        'cid',
        'sticky',
        'closed',
        'title',
        'thumbnail',
        'content',
        'uid',
        'lastreply',
        'lastreplyuname',
        'lastreplyuid',
        'views',
        'comments',
        'stream_id',
        'app',
        'digg',
        'dugg',
        'photos',
        'tags',
        'featured',
        'var1',
        'var2',
        'var3',
        'var4',
        'var5',
        'text1',
        'text2',
        'blob1',
        'rating',
        'page_id',
        'page_type',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'cid' => 'integer',
            'sticky' => 'boolean',
            'closed' => 'boolean',
            'lastreply' => 'integer',
            'lastreplyuid' => 'integer',
            'views' => 'integer',
            'comments' => 'integer',
            'digg' => 'integer',
            'dugg' => 'integer',
            'photos' => 'integer',
            'featured' => 'boolean',
            'page_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StoryCategory::class, 'cid');
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function storyPhotos(): HasMany
    {
        return $this->hasMany(StoryPhoto::class, 'sid');
    }
}
