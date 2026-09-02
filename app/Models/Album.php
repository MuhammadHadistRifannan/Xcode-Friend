<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    // Tabel: jcow_story_categories (digunakan sebagai Album Foto/Video)
    protected $table = 'jcow_story_categories';

    // Tabel ini tidak memiliki kolom created_at / updated_at standar Laravel
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'gid',
        'weight',
        'app',
        'uri',
        'var1',
        'var2',
        'var3',
        'var4',
        'var5',
    ];

    protected $attributes = [
        'var1' => '',
        'var2' => '',
        'var3' => '',
        'var4' => '',
        'var5' => '',
        'uri'  => '',
        'description' => '',
        'weight' => 0,
    ];

    /**
     * Relasi: Ambil SEMUA foto dalam album ini.
     * FK: jcow_story_photos.sid → jcow_story_categories.id
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'sid', 'id');
    }

    /**
     * Relasi: Ambil HANYA foto TERBARU (untuk dijadikan cover album).
     * FK: jcow_story_photos.sid → jcow_story_categories.id
     */
    public function latestPhoto()
    {
        return $this->hasOne(Photo::class, 'sid', 'id')->orderBy('id', 'desc');
    }

    // Scope untuk album foto milik user tertentu
    public function scopePhotos($query, $uid)
    {
        return $query->where('app', 'photos')->where('gid', $uid);
    }

    // Scope untuk album video milik user tertentu
    public function scopeVideos($query, $uid)
    {
        return $query->where('app', 'videos')->where('gid', $uid);
    }
}
