<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'jcow_liked';
    public $timestamps = false;
    protected $fillable = [
        'uid',
        'stream_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}
