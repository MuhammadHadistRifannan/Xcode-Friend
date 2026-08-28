<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'jcow_comments';
    public $timestamps = false;
    protected $fillable = ['target_id', 'uid', 'message', 'created', 'stream_id'];

    // Relasi: 1 Komentar dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}
