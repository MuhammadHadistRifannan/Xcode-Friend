<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $table = 'jcow_pages';

    protected $fillable = [
        'uri',
        'uid',
        'views',
        'logo',
        'name',
        'style_ids',
        'custom_css',
        'background',
        'type',
        'description',
        'users',
    ];

    public $timestamps = false;
    public const UPDATED_AT = 'updated_at';

    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
            'views' => 'integer',
            'users' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function pageUsers(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'jcow_page_users', 'pid', 'uid');
    }
}
