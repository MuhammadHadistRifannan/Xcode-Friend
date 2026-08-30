<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'jcow_reports';

    public $timestamps = false; // Karena kita pakai 'created' dengan format UNIX timestamp

    protected $fillable = [
        'uid',
        'url',
        'message',
        'hasread',
        'created'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}
