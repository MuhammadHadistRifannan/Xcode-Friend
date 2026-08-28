<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Arahkan ke tabel sumber asli JCow
    protected $table = 'jcow_accounts';

    // 2. Matikan timestamps bawaan karena legacy menggunakan Unix Timestamp (integer)
    public $timestamps = false;

    // 3. Sesuaikan kolom pengisian massal dengan field jcow_accounts
    protected $fillable = [
        'username', 'fullname', 'email', 'password', 'created', 'lastlogin'
    ];

    protected $hidden = [
        'password', 'token', 'jcowsess'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // 4. Accessor: Menjembatani $user->name di UI agar membaca $user->fullname dari Database
    public function getNameAttribute()
    {
        return $this->fullname;
    }

    // --- RELASI PERTEMANAN (Legacy: jcow_friends) ---
    public function friends()
    {
        return $this->belongsToMany(User::class, 'jcow_friends', 'uid', 'fid');
    }

    // --- RELASI PESAN (Legacy: jcow_messages) ---
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_id');
    }
}