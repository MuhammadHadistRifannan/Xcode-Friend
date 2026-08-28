<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumVideo extends Model
{
    use HasFactory;

    /**
     * Tabel: jcow_story_categories
     * Digunakan sebagai "Album Video" dengan penanda kolom app = 'video'.
     * Kolom penting:
     * - name        : nama album / playlist video
     * - description : deskripsi album
     * - app         : 'video' (penanda ini adalah album video, bukan foto)
     * - uri         : path cover / thumbnail album (opsional)
     * - gid         : group ID (opsional)
     * - weight      : urutan tampil
     */
    protected $table = 'jcow_story_categories';

    // Tabel ini tidak memiliki kolom created_at / updated_at standar Laravel
    public $timestamps = false;

    /**
     * Global scope: hanya tampilkan album dengan app = 'video'.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('video_app', function ($query) {
            $query->where('app', 'video');
        });
    }

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

    // ────────────────────────────────────────────
    //  RELASI
    // ────────────────────────────────────────────

    /**
     * Relasi: Ambil SEMUA video dalam album ini.
     * FK: jcow_stories.cid → jcow_story_categories.id
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'cid', 'id')
                    ->where('app', 'video')
                    ->orderBy('created', 'asc');
    }

    /**
     * Relasi: Ambil video TERBARU (untuk dijadikan cover album - fallback).
     * FK: jcow_stories.cid → jcow_story_categories.id
     */
    public function latestVideo()
    {
        return $this->hasOne(Video::class, 'cid', 'id')
                    ->where('app', 'video')
                    ->orderBy('id', 'desc');
    }

    // ────────────────────────────────────────────
    //  ACCESSOR
    // ────────────────────────────────────────────

    /**
     * Accessor: Kembalikan video yang jadi sampul album.
     *
     * Prioritas:
     *  1. var1 berisi ID video yang dipilih user sebagai sampul → gunakan itu
     *  2. Fallback → video dengan ID tertinggi di album (sama dengan latestVideo)
     *
     * Jika relasi 'videos' sudah di-eager-load, gunakan koleksi yang sudah ada
     * (tanpa query tambahan / N+1 safe). Jika belum, lakukan query langsung.
     */
    public function getCoverVideoAttribute(): ?Video
    {
        // ── Kasus 1: relasi 'videos' sudah eager-loaded ──
        if ($this->relationLoaded('videos')) {
            $collection = $this->videos;

            // Jika var1 terisi (cover sudah dipilih)
            if ($this->var1 && is_numeric($this->var1)) {
                return $collection->firstWhere('id', (int) $this->var1)
                    ?? $collection->sortByDesc('id')->first();
            }

            return $collection->sortByDesc('id')->first();
        }

        // ── Kasus 2: tidak ada eager load, query langsung ──
        if ($this->var1 && is_numeric($this->var1)) {
            $video = Video::where('cid', $this->id)
                          ->where('id', (int) $this->var1)
                          ->first();
            if ($video) return $video;
        }

        return Video::where('cid', $this->id)
                    ->orderBy('id', 'desc')
                    ->first();
    }
}
