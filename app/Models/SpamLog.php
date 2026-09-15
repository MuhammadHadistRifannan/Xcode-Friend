<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamLog extends Model
{
    protected $table = 'jcow_spam_log';

    public $timestamps = false; // Karena menggunakan `created` timestamp manual dari Jcow

    protected $fillable = [
        'user_id',
        'reasons',
        'message',
        'created'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
