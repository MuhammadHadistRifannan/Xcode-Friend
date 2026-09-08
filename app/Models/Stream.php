<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $table = 'jcow_streams';
    public $timestamps = false; // Karena pakai UNIX timestamp 'created'
    protected $fillable = ['message', 'wall_id', 'uid', 'attachment', 'created', 'type', 'app', 'aid', 'hide', 'likes', 'privacy'];

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
        return $this->hasMany(Like::class, 'stream_id', 'id');
    }

    // Relasi: Mengambil data target (Grup/Page) jika app = 'group' atau 'page'
    public function targetPage()
    {
        // Menggunakan Page::class karena Group juga di tabel jcow_pages 
        // tanpa global scope yang membatasi.
        return $this->belongsTo(Page::class, 'aid', 'id')->withoutGlobalScopes();
    }

    // Relasi: Mengambil data target user (Jika postingan di dinding user lain)
    public function targetWallUser()
    {
        return $this->belongsTo(User::class, 'wall_id', 'id');
    }

    /**
     * Accessor: konversi UNIX 'created' ke Carbon agar bisa pakai diffForHumans()
     */
    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::createFromTimestamp($this->created);
    }

    /**
     * Scope: Hanya tampilkan postingan yang bisa dilihat oleh user tertentu
     * berdasarkan pengaturan privasi (public, friends, private)
     */
    public function scopeVisibleTo($query, $user = null)
    {
        if (!$user) {
            // Guest hanya bisa melihat postingan public
            return $query->where('privacy', 'public');
        }

        return $query->where(function ($q) use ($user) {
            // 1. Postingan Public
            $q->where('privacy', 'public')
              // 2. Postingan Private tapi milik user sendiri
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('privacy', 'private')->where('uid', $user->id);
              })
              // 3. Postingan Friends
              ->orWhere(function ($q3) use ($user) {
                  $q3->where('privacy', 'friends')
                     ->where(function ($q4) use ($user) {
                         $q4->where('uid', $user->id) // Postingan sendiri
                            ->orWhereIn('uid', function ($sub) use ($user) {
                                // Atau postingan teman
                                $sub->select('fid')
                                    ->from('jcow_friends')
                                    ->where('uid', $user->id);
                            });
                     });
              });
        });
    }
}
