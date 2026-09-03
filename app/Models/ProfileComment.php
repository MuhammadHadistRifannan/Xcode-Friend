<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProfileComment extends Model
{
    protected $table = 'jcow_profile_comments';
    public $timestamps = false;
    protected $guarded = [];
}