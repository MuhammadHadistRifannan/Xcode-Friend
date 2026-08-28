<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * Tabel: jcow_stories
     * Video disimpan di sini dengan penanda kolom app = 'video'.
     * - title     : judul video
     * - content   : deskripsi video
     * - var1      : URL YouTube (link video)
     * - thumbnail : thumbnail / cover video
     * - cid       : category/album ID (FK ke jcow_story_categories.id)
     * - uid       : ID user pemilik
     * - created   : Unix timestamp pembuatan
     */
    protected $table = 'jcow_stories';

    // Tabel ini tidak menggunakan kolom created_at / updated_at standar Laravel
    public $timestamps = false;

    /**
     * Global scope: selalu filter hanya record dengan app = 'video'.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('video_app', function ($query) {
            $query->where('app', 'video');
        });
    }

    protected $fillable = [
        'cid',
        'uid',
        'title',
        'content',
        'thumbnail',
        'var1',
        'var2',
        'var3',
        'var4',
        'var5',
        'app',
        'created',
        'updated',
        'views',
        'tags',
        'featured',
        'sticky',
        'closed',
        'lastreply',
        'lastreplyuname',
        'lastreplyuid',
        'stream_id',
        'digg',
        'dugg',
        'photos',
        'comments',
        'blob1',
        'text1',
        'text2',
        'rating',
        'page_id',
        'page_type',
    ];

    // ────────────────────────────────────────────
    //  RELASI
    // ────────────────────────────────────────────

    /**
     * Video ini dimiliki oleh sebuah Album Video.
     * FK: cid → jcow_story_categories.id
     */
    public function album()
    {
        return $this->belongsTo(AlbumVideo::class, 'cid', 'id');
    }

    // ────────────────────────────────────────────
    //  ACCESSOR / HELPERS
    // ────────────────────────────────────────────

    /**
     * Ekstrak YouTube Video ID dari var1 (URL YouTube).
     * Mendukung:
     *   - https://www.youtube.com/watch?v=VIDEO_ID
     *   - https://youtu.be/VIDEO_ID
     *   - https://www.youtube.com/embed/VIDEO_ID
     */
    public function getYoutubeIdAttribute(): ?string
    {
        $url = $this->var1;
        if (!$url) return null;

        if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            return $m[1];
        }
        if (preg_match('/(?:v=|\/embed\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * URL thumbnail YouTube resolusi medium (mqdefault.jpg).
     */
    public function getYoutubeThumbnailAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? "https://img.youtube.com/vi/{$id}/mqdefault.jpg" : null;
    }

    /**
     * URL embed YouTube yang aman untuk iframe.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    /**
     * Tanggal pembuatan diformat (dari Unix timestamp di kolom 'created').
     */
    public function getFormattedDateAttribute(): string
    {
        if (!$this->created) return '-';
        return \Carbon\Carbon::createFromTimestamp($this->created)->translatedFormat('j M Y');
    }
}
