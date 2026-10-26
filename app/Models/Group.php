<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Group extends Model
{
    // Arahkan ke tabel legacy jcow_pages
    protected $table = 'jcow_pages';
    
    // Matikan timestamps bawaan Laravel karena legacy pakai kolom 'updated' int
    public $timestamps = false;
    
    protected $fillable = [
        'uri',
        'name',
        'description',
        'logo',
        'uid',
        'updated',
        'type',
        'views',
        'users',
        'style_ids',
        'custom_css',
        'background'
    ];

    protected static function boot()
    {
        parent::boot();

        // Global scope untuk selalu filter type = 'group' atau 'private_group'
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->whereIn('type', ['group', 'private_group']);
        });
    }

    /**
     * Relasi ke pembuat grup (User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * Relasi ke member aktif grup (via jcow_page_users)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'jcow_page_users', 'pid', 'uid');
    }

    /**
     * Relasi ke pending members (via jcow_group_members_pending)
     */
    public function pendingMembers()
    {
        // ignored = 2 bisa digunakan sebagai status kick/blocked
        return $this->belongsToMany(User::class, 'jcow_group_members_pending', 'gid', 'uid')
                    ->withPivot('ignored'); 
    }

    /**
     * Relasi ke postingan dinding grup (jcow_streams.wall_id = group.id)
     */
    public function streams()
    {
        return $this->hasMany(Stream::class, 'wall_id', 'id')
                    ->where(function($q) {
                        $q->where('app', 'group')
                          ->orWhereIn('app', ['', 'events', 'photo', 'photos', 'video', 'videos', 'feed']);
                    })
                    ->orderBy('created', 'desc');
    }

    /**
     * URL logo publik. Mengembalikan null jika logo belum di-set,
     * sehingga view bisa menampilkan placeholder.
     */
    public function getLogoUrlAttribute(): ?string
    {
        $logo = trim((string) $this->logo);
        if ($logo === '') {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        if (str_starts_with($logo, 'uploads/')) {
            return asset($logo);
        }

        if (str_starts_with($logo, 'storage/')) {
            return asset($logo);
        }

        if (file_exists(public_path('uploads/' . $logo))) {
            return asset('uploads/' . $logo);
        }

        if (file_exists(public_path('uploads/avatars/' . $logo))) {
            return asset('uploads/avatars/' . $logo);
        }

        if (str_starts_with($logo, 'group_logos/') || str_starts_with($logo, 'groups/')) {
            return asset('uploads/' . $logo);
        }

        return asset('uploads/avatars/' . $logo);
    }

    /**
     * URL background banner grup.
     */
    public function getBackgroundUrlAttribute(): ?string
    {
        $bg = trim((string) $this->background);
        if ($bg === '') {
            return null;
        }

        if (str_starts_with($bg, 'http://') || str_starts_with($bg, 'https://')) {
            return $bg;
        }

        if (str_starts_with($bg, 'uploads/')) {
            return asset($bg);
        }

        if (str_starts_with($bg, 'storage/')) {
            return asset($bg);
        }

        if (file_exists(public_path('uploads/' . $bg))) {
            return asset('uploads/' . $bg);
        }

        return asset('uploads/' . ltrim($bg, '/'));
    }
}
