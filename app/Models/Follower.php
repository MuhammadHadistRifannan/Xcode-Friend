<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'jcow_followers';
    public $timestamps = false;
    protected $fillable = ['uid', 'fid'];
}
