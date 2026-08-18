<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banned extends Model
{
    use HasFactory;

    protected $table = 'jcow_banned';

    protected $fillable = [
        'username',
        'ip1',
        'ip2',
        'ip3',
        'ip4',
        'created',
        'expired',
        'operator',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created' => 'integer',
            'expired' => 'integer',
        ];
    }
}
