<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liked extends Model
{
    protected $table = 'jcow_liked';
    public $timestamps = false;
    protected $fillable = ['uid', 'stream_id'];
}
