<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Arahkan ke tabel legacy
    protected $table = 'jcow_accounts';

    // Nonaktifkan timestamps bawaan (karena kita pakai 'created' & 'lastlogin' UNIX)
    public $timestamps = false;

    protected $fillable = [
        'username', 'fullname', 'email', 'password', 'gender',
        'birthyear', 'birthmonth', 'birthday', 'country', 'about_me',
        'created', 'lastlogin', 'ipaddress', 'hide_age'
    ];

    protected $hidden = [
        'password', 'token', 'jcowsess', 'chpass'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    // Tambahkan relasi ini di dalam class User
    public function streams()
    {
        return $this->hasMany(Stream::class, 'uid', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'uid', 'id');
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'fid', 'id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'uid', 'id');
    }
}
