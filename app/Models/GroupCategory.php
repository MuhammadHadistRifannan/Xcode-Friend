<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupCategory extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_categories';

    protected $fillable = [
        'name',
        'groups',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'groups' => 'integer',
        ];
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'category');
    }
}
