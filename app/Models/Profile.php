<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'jcow_profiles';
    public $timestamps = false;
    protected $guarded = [];
}