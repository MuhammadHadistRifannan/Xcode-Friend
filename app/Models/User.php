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

    /**
     * The "booted" method of the model.
     * Membersihkan seluruh data turunan, konten, dan file saat user dihapus.
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            app(\App\Services\AccountDeletionService::class)->deleteUserData((int) $user->id, $user);
        });
    }

    protected $fillable = [
        'username', 'fullname', 'email', 'password', 'gender',
        'birthyear', 'birthmonth', 'birthday', 'country', 'about_me',
        'created', 'lastlogin', 'last_seen', 'ipaddress', 'hide_age', 'roles', 'disabled',
        'settings', 'avatar', 'level', 'points',
        'location', 'signature', 'blurbs', 'remember_token',
        'profile_permission', 'hide_me',
        'var1', 'var2', 'var3', 'var4', 'var5', 'var6', 'var7'
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

    public function isAdmin(): bool
    {
        $roleLower = strtolower(trim((string) ($this->roles ?? '')));
        return ((int) $this->level === 1)
            || ($roleLower === '3')
            || in_array($roleLower, ['admin', 'administrator']);
    }

    public function hasRole(?string $roleName = ''): bool
    {
        $target = strtolower(trim((string) ($roleName ?? '')));
        if ($this->isAdmin()) {
            return in_array($target, ['admin', 'administrator']);
        }
        $userRole = strtolower(trim((string) ($this->roles ?? '')));
        if ($userRole === '' || $userRole === '0' || $userRole === '2' || $userRole === 'member') {
            return in_array($target, ['general member', 'member', 'user']);
        }
        return $userRole === $target;
    }



    public function getNameAttribute()
    {
        return $this->fullname;
    }

    public static function avatarUrl(?string $avatar): string
    {
        if (empty($avatar)) {
            return asset('assets/img/default.png');
        }
        $avatar = ltrim(trim($avatar), '/');
        if (in_array(strtolower($avatar), ['undefined.jpg', 's_undefined.jpg', 'default.png', 'default-avatar.png', 'logo.jpg', 's_logo.jpg'])) {
            return asset('assets/img/default.png');
        }
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }
        if (str_starts_with($avatar, 'uploads/avatars/')) {
            $path = public_path($avatar);
            return file_exists($path) ? asset($avatar) : asset('assets/img/default.png');
        }
        if (str_starts_with($avatar, 'avatars/')) {
            $path = public_path('uploads/' . $avatar);
            return file_exists($path) ? asset('uploads/' . $avatar) : asset('assets/img/default.png');
        }
        if (str_starts_with($avatar, 'uploads/')) {
            $path = public_path($avatar);
            return file_exists($path) ? asset($avatar) : asset('assets/img/default.png');
        }
        $path = public_path('uploads/avatars/' . $avatar);
        if (file_exists($path)) {
            return asset('uploads/avatars/' . $avatar);
        }
        if (file_exists(public_path('storage/avatars/' . $avatar))) {
            return asset('storage/avatars/' . $avatar);
        }
        return asset('assets/img/default.png');
    }

    public function getAvatarUrlAttribute(): string
    {
        return self::avatarUrl($this->avatar);
    }

    public function getIsOnlineAttribute(): bool
    {
        $lastSeen = (int) ($this->last_seen ?? 0);
        $lastLogin = (int) ($this->lastlogin ?? 0);
        $effective = max($lastSeen, $lastLogin);

        return $effective > 0 && (time() - $effective) < 300;
    }

    public function getLastActiveTextAttribute(): string
    {
        $lastSeen = (int) ($this->last_seen ?? 0);
        $lastLogin = (int) ($this->lastlogin ?? 0);
        $effective = max($lastSeen, $lastLogin);

        if ($effective <= 0) {
            return 'Offline';
        }

        if ((time() - $effective) < 300) {
            return 'Online';
        }

        return \Carbon\Carbon::createFromTimestamp($effective)->diffForHumans();
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
