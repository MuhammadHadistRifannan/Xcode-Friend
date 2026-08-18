<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'jcow_roles';

    protected $fillable = [
        'name',
    ];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'jcow_role_user', 'role_id', 'user_id');
    }
}
