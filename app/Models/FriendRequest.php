<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $table = 'jcow_friend_reqs';
    public $timestamps = false;
    protected $fillable = ['uid', 'fid', 'created', 'msg'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'fid');
    }
}
