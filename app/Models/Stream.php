<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $table = 'jcow_streams';
    public $timestamps = false; // Karena pakai UNIX timestamp 'created'
    protected $fillable = ['message', 'wall_id', 'uid', 'attachment', 'created', 'type', 'app', 'aid', 'hide', 'likes', 'privacy'];

    // Relasi: 1 Postingan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id')->withDefault([
            'fullname' => 'Pengguna',
            'username' => 'user',
            'avatar'   => '',
        ]);
    }

    // Relasi: 1 Postingan memiliki banyak Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class, 'stream_id', 'id')->orderBy('created', 'asc');
    }

    // Relasi: 1 Postingan memiliki banyak Likes
    public function likedBy()
    {
        return $this->hasMany(Like::class, 'stream_id', 'id');
    }

    // Relasi: Mengambil data target (Grup/Page) jika app = 'group' atau 'page'
    public function targetPage()
    {
        // Menggunakan Page::class karena Group juga di tabel jcow_pages 
        // tanpa global scope yang membatasi.
        return $this->belongsTo(Page::class, 'aid', 'id')->withoutGlobalScopes();
    }

    // Relasi: Mengambil data target grup jika app = 'group' via wall_id
    public function targetGroup()
    {
        return $this->belongsTo(Group::class, 'wall_id', 'id')->withoutGlobalScopes();
    }

    /**
     * Accessor targetPage dengan fallback cerdas ke wall_id jika aid kosong
     */
    public function getTargetPageAttribute()
    {
        if ($this->relationLoaded('targetPage') && $this->getRelation('targetPage')) {
            return $this->getRelation('targetPage');
        }

        if ($this->relationLoaded('targetGroup') && $this->getRelation('targetGroup')) {
            return $this->getRelation('targetGroup');
        }

        if (!empty($this->aid) && $this->aid > 0) {
            $page = Page::withoutGlobalScopes()->find($this->aid);
            if ($page) {
                $this->setRelation('targetPage', $page);
                return $page;
            }
        }

        if (in_array($this->app, ['group', 'page']) && !empty($this->wall_id) && $this->wall_id > 0) {
            $page = Page::withoutGlobalScopes()->find($this->wall_id);
            if ($page) {
                $this->setRelation('targetPage', $page);
                return $page;
            }
        }

        return null;
    }

    // Relasi: Mengambil data target user (Jika postingan di dinding user lain)
    public function targetWallUser()
    {
        return $this->belongsTo(User::class, 'wall_id', 'id');
    }

    /**
     * Accessor: konversi UNIX 'created' ke Carbon agar bisa pakai diffForHumans()
     */
    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::createFromTimestamp($this->created);
    }

    /**
     * Scope: Hanya tampilkan postingan yang bisa dilihat oleh user tertentu
     * berdasarkan pengaturan privasi (public, friends, private)
     */
    public function scopeVisibleTo($query, $user = null)
    {
        $query->where('hide', 0)
              ->where('jcow_streams.uid', '>', 0)
              ->whereExists(function ($sub) {
                  $sub->select(\Illuminate\Support\Facades\DB::raw(1))
                      ->from('jcow_accounts')
                      ->whereColumn('jcow_accounts.id', 'jcow_streams.uid');
              });

        if (!$user) {
            // Guest hanya bisa melihat postingan public
            return $query->where('privacy', 'public');
        }

        // Sembunyikan postingan dari pengguna yang diblokir atau yang memblokir user ini
        $query->whereNotIn('jcow_streams.uid', function ($sub) use ($user) {
            $sub->select('bid')->from('jcow_blacks')->where('uid', $user->id);
        })->whereNotIn('jcow_streams.uid', function ($sub) use ($user) {
            $sub->select('uid')->from('jcow_blacks')->where('bid', $user->id);
        });

        return $query->where(function ($q) use ($user) {
            // 1. Postingan Public
            $q->where('privacy', 'public')
              // 2. Postingan Private tapi milik user sendiri
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('privacy', 'private')->where('uid', $user->id);
              })
              // 3. Postingan Friends
              ->orWhere(function ($q3) use ($user) {
                  $q3->where('privacy', 'friends')
                     ->where(function ($q4) use ($user) {
                         $q4->where('uid', $user->id) // Postingan sendiri
                            ->orWhereIn('uid', function ($sub) use ($user) {
                                // Atau postingan teman
                                $sub->select('fid')
                                    ->from('jcow_friends')
                                    ->where('uid', $user->id);
                            });
                     });
              });
        });
    }

    /**
     * Accessor: parse attachment data baik format JSON (baru) maupun PHP serialized (legacy JCow)
     */
    public function getParsedAttachmentAttribute(): ?array
    {
        if (empty($this->attachment)) return null;

        $json = json_decode($this->attachment, true);
        if (is_array($json)) {
            return $json;
        }

        $unserialized = @unserialize($this->attachment);
        if (is_array($unserialized)) {
            return $unserialized;
        }

        // Fallback untuk string serialized JCow yang panjang karakternya bergeser karena escaping tanda kutip
        $repaired = preg_replace_callback('!s:(\d+):"(.*?)";!s', function($match) {
            return 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        }, $this->attachment);
        $repairedUnserialized = @unserialize($repaired);
        if (is_array($repairedUnserialized)) {
            return $repairedUnserialized;
        }

        return null;
    }

    /**
     * Accessor: daftar URL foto yang tersimpan pada lampiran postingan
     */
    public function getPhotoUrlsAttribute(): array
    {
        $att = $this->parsed_attachment;
        $rawList = [];
        if ($att) {
            if (!empty($att['photos'])) {
                $rawList = is_array($att['photos']) ? $att['photos'] : [$att['photos']];
            } elseif (!empty($att['photo'])) {
                $rawList = is_array($att['photo']) ? $att['photo'] : [$att['photo']];
            } elseif (!empty($att['thumb'])) {
                $rawList = is_array($att['thumb']) ? $att['thumb'] : [$att['thumb']];
            }
        } elseif (!empty($this->attachment) && !str_starts_with($this->attachment, 'youtube:')) {
            $trimmed = trim($this->attachment);
            if (!str_starts_with($trimmed, '{') && !str_starts_with($trimmed, '[') && !str_starts_with($trimmed, 'a:')) {
                $ext = strtolower(pathinfo($trimmed, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'])) {
                    $rawList = [$trimmed];
                }
            }
        }

        $urls = [];
        $validExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        foreach ($rawList as $p) {
            if (is_array($p)) {
                $p = $p['url'] ?? $p['uri'] ?? $p['thumb'] ?? (is_string(reset($p)) ? reset($p) : '');
            }
            if (empty($p) || !is_string($p)) continue;
            $p = trim($p);
            if (str_starts_with($p, 'a:') || str_starts_with($p, '{') || str_starts_with($p, '[')) {
                continue;
            }
            if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
                $urls[] = $p;
                continue;
            }
            $clean = ltrim($p, '/');
            $ext = strtolower(pathinfo($clean, PATHINFO_EXTENSION));
            if (!in_array($ext, $validExts) && !file_exists(public_path($clean))) {
                continue;
            }
            if (file_exists(public_path($clean))) {
                $urls[] = asset($clean);
            } elseif (file_exists(public_path('storage/' . $clean))) {
                $urls[] = asset('storage/' . $clean);
            } elseif (file_exists(public_path('storage/posts/' . $clean))) {
                $urls[] = asset('storage/posts/' . $clean);
            } elseif (file_exists(public_path('storage/photos/' . $clean))) {
                $urls[] = asset('storage/photos/' . $clean);
            } elseif (file_exists(public_path('uploads/' . $clean))) {
                $urls[] = asset('uploads/' . $clean);
            } elseif (file_exists(public_path('uploads/photos/' . $clean))) {
                $urls[] = asset('uploads/photos/' . $clean);
            } elseif (file_exists(public_path('uploads/posts/' . $clean))) {
                $urls[] = asset('uploads/posts/' . $clean);
            } else {
                if (str_starts_with($clean, 'uploads/')) {
                    $urls[] = asset($clean);
                } elseif (str_starts_with($clean, 'photos/') || str_starts_with($clean, 'posts/')) {
                    $urls[] = asset('storage/' . $clean);
                } else {
                    $urls[] = asset('storage/posts/' . $clean);
                }
            }
        }
        return $urls;
    }
}
