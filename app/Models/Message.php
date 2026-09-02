<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    // 1. Arahkan ke tabel pesan utama
    protected $table = 'jcow_messages';
    
    // 2. Matikan timestamps otomatis
    public $timestamps = false;

    // 3. Sesuaikan kolom dengan jcow_messages
    protected $fillable = [
        'subject', 'message', 'from_id', 'to_id', 'created', 'hasread'
    ];

    // 4. Accessor: Menjembatani nama kolom untuk UI Blade
    public function getIsReadAttribute()
    {
        return $this->hasread;
    }

    // Mengubah unix timestamp 'created' menjadi objek Carbon DateTime
    public function getCreatedAtAttribute()
    {
        return Carbon::createFromTimestamp($this->created);
    }

    // --- RELASI PENERIMA & PENGIRIM ---
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}