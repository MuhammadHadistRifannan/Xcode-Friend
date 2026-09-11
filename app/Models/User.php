<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $username
 * @property string $fullname
 * @property string $email
 * @property string $password
 * @property int|null $gender
 * @property int|null $birthyear
 * @property int|null $birthmonth
 * @property int|null $birthday
 * @property string|null $country
 * @property string|null $about_me
 * @property int|null $created
 * @property int|null $lastlogin
 * @property string|null $ipaddress
 * @property int|null $hide_age
 * @property string|null $roles
 * @property int|null $disabled
 * @property string|null $settings
 * @property string|null $avatar
 * @property int|null $level
 * @property int|null $points
 * @property string|null $location
 * @property string|null $signature
 * @property string|null $blurbs
 * @property string|null $remember_token
 * @property string|null $token
 * @property string|null $jcowsess
 * @property string|null $chpass
 * @property-read array $settings_array
 * @property-read string $name
 * @property-read string $avatar_url
 */
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
        'created', 'lastlogin', 'ipaddress', 'hide_age', 'roles', 'disabled',
        'settings', 'avatar', 'level', 'points',
        'location', 'signature', 'blurbs', 'remember_token'
    ];

    protected $hidden = [
        'password', 'token', 'jcowsess', 'chpass'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'disabled' => 'integer',
        ];
    }

    public function getSettingsArrayAttribute(): array
    {
        if (empty($this->settings)) return [];
        $decoded = json_decode($this->settings, true);
        return is_array($decoded) ? $decoded : [];
    }



    public function getNameAttribute()
    {
        return $this->fullname;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('assets/img/default.png');
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

    public function followerUsers()
    {
        return $this->belongsToMany(User::class, 'jcow_followers', 'fid', 'uid');
    }

    public function followingUsers()
    {
        return $this->belongsToMany(User::class, 'jcow_followers', 'uid', 'fid');
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
     * Groups yang dibuat oleh user ini.
     * jcow_pages.uid = users.id (with group global scope)
     */
    public function createdGroups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class, 'uid');
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

    /**
     * Groups yang diikuti oleh user ini.
     */
    public function likedGroups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'jcow_page_users',
            'uid',
            'pid'
        );
    }
}
