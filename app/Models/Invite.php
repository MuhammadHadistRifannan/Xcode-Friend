<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    // Tabel legacy
    protected $table = 'jcow_invites';

    // Nonaktifkan timestamps bawaan karena pakai kolom 'created' dengan UNIX timestamp
    public $timestamps = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'uid',
        'email',
        'status',
        'created'
    ];

    /**
     * Relasi ke pembuat undangan.
     * jcow_invites.uid -> jcow_accounts.id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
