<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
