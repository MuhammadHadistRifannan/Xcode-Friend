<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    // Tabel: jcow_story_photos (menyimpan foto-foto dalam sebuah album)
    protected $table = 'jcow_story_photos';

    // Tabel ini tidak memiliki kolom created_at / updated_at standar Laravel
    public $timestamps = false;

    protected $fillable = [
        'sid',   // album_id (foreign key ke jcow_story_categories.id)
        'uri',   // path gambar di storage
        'des',   // deskripsi foto
        'thumb', // path thumbnail
        'size',  // ukuran file dalam bytes
    ];

    /**
     * Relasi: Foto ini dimiliki oleh sebuah Album.
     * FK: sid → jcow_story_categories.id
     */
    public function album()
    {
        return $this->belongsTo(Album::class, 'sid', 'id');
    }

    public function story()
    {
        return $this->belongsTo(Story::class, 'sid', 'id');
    }

    /**
     * Memeriksa apakah file fisik foto benar-benar ada di storage.
     */
    public function fileExists(): bool
    {
        if (empty($this->uri)) {
            return false;
        }
        $uri = ltrim(trim($this->uri), '/');
        if (str_starts_with($uri, 'http://') || str_starts_with($uri, 'https://')) {
            return true;
        }

        $cleanUri = str_replace(['storage/', 'uploads/'], '', $uri);

        // Periksa di Storage disk public (root = public/uploads)
        if (Storage::disk('public')->exists($cleanUri)) {
            return true;
        }
        if (Storage::disk('public')->exists('posts/' . $cleanUri)) {
            return true;
        }
        if (Storage::disk('public')->exists('photos/' . $cleanUri)) {
            return true;
        }
        if (file_exists(public_path('uploads/' . $cleanUri))) {
            return true;
        }
        if (file_exists(public_path('storage/' . $cleanUri))) {
            return true;
        }
        if (file_exists(public_path($uri))) {
            return true;
        }

        return false;
    }

    public function getPhotoUrlAttribute(): string
    {
        if (empty($this->uri)) {
            return asset('assets/img/default.png');
        }
        $uri = ltrim(trim($this->uri), '/');
        if (str_starts_with($uri, 'http://') || str_starts_with($uri, 'https://')) {
            return $uri;
        }
        if (str_starts_with($uri, 'POST-')) {
            return asset('storage/posts/' . $uri);
        }
        if (str_starts_with($uri, 'uploads/')) {
            return asset($uri);
        }
        if (str_starts_with($uri, 'photos/') || str_starts_with($uri, 'posts/') || str_starts_with($uri, 'storage/')) {
            return asset('storage/' . ltrim(str_replace('storage/', '', $uri), '/'));
        }
        return asset('uploads/' . $uri);
    }

    public function getThumbUrlAttribute(): string
    {
        $thumb = ltrim(trim($this->thumb ?: ($this->uri ?? '')), '/');
        if (empty($thumb)) {
            return asset('assets/img/default.png');
        }
        if (str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://')) {
            return $thumb;
        }
        if (str_starts_with($thumb, 'POST-')) {
            return asset('storage/posts/' . $thumb);
        }
        if (str_starts_with($thumb, 'uploads/')) {
            return asset($thumb);
        }
        if (str_starts_with($thumb, 'photos/') || str_starts_with($thumb, 'posts/') || str_starts_with($thumb, 'storage/')) {
            return asset('storage/' . ltrim(str_replace('storage/', '', $thumb), '/'));
        }
        return asset('uploads/' . $thumb);
    }
}
