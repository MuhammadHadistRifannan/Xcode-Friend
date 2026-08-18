<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoryCategory extends Model
{
    use HasFactory;

    protected $table = 'jcow_story_categories';

    protected $fillable = [
        'gid',
        'name',
        'description',
        'weight',
        'app',
        'var1',
        'var2',
        'var3',
        'var4',
        'var5',
        'uri',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'gid' => 'integer',
            'weight' => 'integer',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(StoryCatGroup::class, 'gid');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'cid');
    }
}
