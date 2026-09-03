<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    public $timestamps = false;
    protected $table = 'jcow_story_categories';

    protected $fillable = ['gid', 'name', 'description', 'weight', 'app', 'var1', 'var2', 'var3', 'var4', 'var5', 'uri'];

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
