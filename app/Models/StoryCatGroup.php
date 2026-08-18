<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoryCatGroup extends Model
{
    use HasFactory;

    protected $table = 'jcow_story_cat_groups';

    protected $fillable = [
        'name',
        'app',
        'weight',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
        ];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(StoryCategory::class, 'gid');
    }
}
