<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'jcow_tags';

    protected $fillable = [
        'name',
        'app',
        'num',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'num' => 'integer',
        ];
    }

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'jcow_tag_ids', 'tid', 'sid');
    }
}
