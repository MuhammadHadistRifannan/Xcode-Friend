<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Black extends Model
{
    use HasFactory;

    protected $table = 'jcow_blacks';

    protected $fillable = [
        'uid',
        'bid',
        'bname',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'uid');
    }

    public function blocked(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'bid');
    }
}