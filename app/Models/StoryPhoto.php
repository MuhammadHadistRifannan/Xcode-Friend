<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryPhoto extends Model
{
    use HasFactory;

    protected $table = 'jcow_story_photos';

    protected $fillable = [
        'sid',
        'uri',
        'des',
        'thumb',
        'size',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class, 'sid');
    }
}
