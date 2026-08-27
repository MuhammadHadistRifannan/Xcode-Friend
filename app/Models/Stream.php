<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $table = 'jcow_streams';
    public $timestamps = false; // Karena pakai UNIX timestamp 'created'
    protected $fillable = ['message', 'wall_id', 'uid', 'attachment', 'created', 'type', 'app', 'aid', 'hide', 'likes'];

    // Relasi: 1 Postingan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    // Relasi: 1 Postingan memiliki banyak Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class, 'stream_id', 'id')->orderBy('created', 'asc');
    }

    // Relasi: 1 Postingan memiliki banyak Likes
    public function likedBy()
    {
        return $this->hasMany(Liked::class, 'stream_id', 'id');
    }
}
