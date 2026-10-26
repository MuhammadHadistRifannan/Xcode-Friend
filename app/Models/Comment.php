<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    protected $table = 'jcow_comments';
    public $timestamps = false;
    protected $fillable = [
        'target_id',
        'uid',
        'message',
        'created',
        'stream_id'
    ];

    protected $casts = [
        'uid' => 'integer',
        'stream_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id')->withDefault([
            'fullname' => 'Pengguna',
            'username' => 'user',
            'avatar'   => '',
        ]);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::createFromTimestamp($this->created);
    }
}
