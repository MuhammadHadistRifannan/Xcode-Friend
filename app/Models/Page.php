<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Tabel legacy Jcow — tidak menggunakan konvensi nama tabel Laravel.
     */
    protected $table = 'jcow_pages';

    /**
     * Tabel ini tidak punya kolom created_at / updated_at Laravel bawaan.
     * Kolom 'updated' adalah Unix timestamp yang dikelola manual.
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi via mass-assignment.
     * Semua kolom NOT NULL (tanpa default di DB) wajib ada di sini.
     */
    protected $fillable = [
        'uri',
        'name',
        'description',
        'logo',
        'uid',        // FK → users.id (Auth user yang membuat page)
        'updated',    // Unix timestamp, diisi dengan time() saat store/update
        'views',      // Counter views — default 0
        'users',      // Cache count followers — default 0
        'type',       // Tipe page — default string kosong
        'style_ids',  // Custom style IDs — default string kosong
        'custom_css', // Custom CSS — default string kosong
        'background', // Background — default string kosong
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Pembuat page (pemilik).
     * jcow_pages.uid → users.id
     */
    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'uid');
    }

    /**
     * User yang menyukai (like/follow) page ini.
     * Pivot: jcow_page_users (pid = page id, uid = user id)
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'jcow_page_users', // tabel pivot
            'pid',             // FK dari sisi Page di pivot
            'uid'              // FK dari sisi User di pivot
        );
    }

    // =========================================================================
    // ACCESSOR / HELPER
    // =========================================================================

    /**
     * URL logo publik. Mengembalikan null jika logo belum di-set,
     * sehingga view bisa menampilkan placeholder.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
