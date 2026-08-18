<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupPostcat extends Model
{
    use HasFactory;

    protected $table = 'jcow_group_postcats';

    protected $fillable = [
        'gid',
        'name',
    ];

    public $timestamps = false;

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'gid');
    }
}
