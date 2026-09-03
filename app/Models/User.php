<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Arahkan ke tabel legacy JCow
    protected $table = 'jcow_accounts';

    // Legacy menggunakan Unix timestamps (integer)
    public $timestamps = false;

    protected $fillable = [
        'username', 'fullname', 'email', 'password', 'gender',
        'birthyear', 'birthmonth', 'birthday', 'country', 'about_me',
        'created', 'lastlogin', 'ipaddress', 'hide_age', 'roles'
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



    public function getNameAttribute()
    {
        return $this->fullname;
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'jcow_friends', 'uid', 'fid');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_id');
    }


    // Tambahkan relasi ini di dalam class User
    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'id')->withDefault();
    }

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

    // =========================================================================
    // RELASI PAGES
    // =========================================================================

    /**
     * Pages yang dibuat oleh user ini.
     * jcow_pages.uid → users.id
     */
    public function createdPages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'uid');
    }

    /**
     * Pages yang disukai (liked/followed) oleh user ini.
     * Pivot: jcow_page_users (uid = user id, pid = page id)
     */
    public function likedPages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Page::class,
            'jcow_page_users', // tabel pivot
            'uid',             // FK dari sisi User di pivot
            'pid'              // FK dari sisi Page di pivot
        );
    }
}
