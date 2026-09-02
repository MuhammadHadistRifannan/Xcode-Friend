<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'jcow_friends';
    public $timestamps = false;
    protected $fillable = ['uid', 'fid', 'created'];
}
