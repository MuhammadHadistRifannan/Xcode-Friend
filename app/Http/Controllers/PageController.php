<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Video;
use App\Models\AlbumVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    // =========================================================================
    // INDEX — Tampilkan semua Pages (grid publik)
    // =========================================================================

    public function index()
    {
        // withCount('followers') menambah kolom 'followers_count' ke setiap objek Page
        $pages = Page::withCount('followers')
            ->orderByDesc('updated')
            ->get();

        return view('pages.index', compact('pages'));
    }

    // =========================================================================
    // MINE — Pages yang dibuat oleh Auth user + Pages yang disukai
    // =========================================================================

    public function mine()
    {
        $userId = Auth::id();

        // Ambil data langsung dari model Page (lebih aman dari relasi jika User null)
        $createdPages = Page::where('uid', $userId)
            ->withCount('followers')
            ->orderByDesc('updated')
            ->get();

        $likedPages = Page::whereHas('followers', function ($q) use ($userId) {
            $q->where('jcow_page_users.uid', $userId);
        })
            ->withCount('followers')
            ->orderByDesc('updated')
            ->get();

        return view('pages.mine', compact('createdPages', 'likedPages'));
    }

    // =========================================================================
    // CREATE — Tampilkan form buat page baru
    // =========================================================================

    public function create()
    {
        if (Auth::check() && (int) Auth::user()->disabled === 1) {
            return redirect()->route('pages.index')->with('error', 'Akun Anda masih berstatus pending verifikasi. Pembuatan halaman baru hanya dapat dilakukan setelah akun disetujui penuh oleh Administrator.');
        }

        return view('pages.create');
    }

    // =========================================================================
    // STORE — Simpan page baru ke database
    // =========================================================================

    public function store(Request $request)
    {
        if (Auth::check() && (int) Auth::user()->disabled === 1) {
            return redirect()->route('pages.index')->with('error', 'Akun Anda masih berstatus pending verifikasi. Pembuatan halaman baru hanya dapat dilakukan setelah akun disetujui penuh oleh Administrator.');
        }
        $validated = $request->validate([
            'uri' => [
                'required',
                'string',
                'min:6',
                'max:50',
                'regex:/^[0-9a-z]+$/', // hanya angka dan huruf kecil, sesuai legacy
                Rule::unique('jcow_pages', 'uri'),
            ],
            'name'        => ['required', 'string', 'max:100'],
            'logo'        => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'uri.regex'  => 'URL hanya boleh mengandung huruf kecil (a-z) dan angka (0-9).',
            'uri.unique' => 'URL halaman ini sudah digunakan, pilih yang lain.',
            'uri.min'    => 'URL minimal 6 karakter.',
            'logo.required' => 'Logo halaman wajib diunggah (jangan gunakan foto default).',
            'logo.image'    => 'Logo harus berupa gambar.',
        ]);

        $uid = Auth::id();

        $logoPath = '';
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('pages/logos', 'public');
        }

        $page = Page::create([
            'uri'         => $validated['uri'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? '',
            'logo'        => $logoPath,
            'uid'         => $uid,
            'updated'     => time(),
            // Kolom NOT NULL di jcow_pages yang tidak punya DEFAULT di DB
            'views'       => 0,
            'users'       => 0,
            'type'        => '',
            'style_ids'   => '',
            'custom_css'  => '',
            'background'  => '',
        ]);

        return redirect()
            ->route('pages.show', $page->id)
            ->with('success', 'Halaman berhasil dibuat!');
    }

    // =========================================================================
    // SHOW — Detail page
    // =========================================================================

    public function show($id)
    {
        $page = is_numeric($id)
            ? Page::findOrFail($id)
            : Page::where('uri', $id)->firstOrFail();
        $filter = request()->query('filter');

        $page->load([
            'creator',
            'streams' => function ($query) use ($filter) {
                if ($filter === 'photo') {
                    $query->where('attachment', '!=', '')
                          ->where('attachment', 'not like', 'youtube:%');
                } elseif ($filter === 'video') {
                    $query->where('attachment', 'like', 'youtube:%');
                }
                $query->with(['user', 'comments.user', 'targetPage', 'targetWallUser']);
                $query->orderBy('created', 'desc');
            }
        ]);
        $page->loadCount('followers');

        // Fallback ke user ID 1 jika Auth belum aktif untuk keperluan testing
        $userId = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

        // Cek apakah user sudah menyukai page ini
        $isLiked = $page->followers()->where('uid', $userId)->exists();

        // Cek apakah user adalah pemilik page
        $isOwner = ($page->uid == $userId);

        $recentFollowers = $page->followers()->take(5)->get();

        // Mock data untuk posts & sidebar (ganti dengan query real nanti)
        $pageData = [
            'profile' => [
                'id'          => $page->id,
                'name'        => $page->name,
                'isVerified'  => false,
                'description' => $page->description,
                'likesCount'  => $page->followers_count,
                'coverUrl'    => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=1000',
                'avatarUrl'   => $page->logo_url ?? asset('assets/img/default.png'),
                'isLiked'     => $isLiked,
                'isOwner'     => $isOwner,
                'recentFollowers' => $recentFollowers,
            ],
            'posts'        => [],
            'reviews'      => ['rating' => 0, 'count' => 0],
            'networkLinks' => [],
        ];

        $recentPhotosQuery = \App\Models\Stream::where('wall_id', $page->id)
            ->where('app', 'page')
            ->where('attachment', '!=', '')
            ->where(function($q) {
                $q->where('type', 2)
                  ->orWhere(function($sq) {
                      $sq->where('type', '!=', 3)->where('attachment', 'not like', 'youtube:%');
                  });
            })
            ->with('user')
            ->orderBy('created', 'desc')
            ->take(30)
            ->get();

        $recentPhotos = $recentPhotosQuery->filter(function($stream) {
            return count($stream->photo_urls) > 0;
        })->take(9);

        $recentVideos = \App\Models\Stream::where('wall_id', $page->id)
            ->where('app', 'page')
            ->where('attachment', '!=', '')
            ->where(function($q) {
                $q->where('type', 3)
                  ->orWhere('attachment', 'like', 'youtube:%');
            })
            ->with('user')
            ->orderBy('created', 'desc')
            ->take(9)
            ->get();

        return view('pages.show', compact('page', 'pageData', 'isLiked', 'recentPhotos', 'recentVideos', 'filter'));
    }

    // =========================================================================
    // EDIT — Tampilkan form edit (hanya pemilik)
    // =========================================================================

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        abort_if((int) $page->uid !== (int) Auth::id() && !$isAdmin, 403, 'Anda tidak memiliki izin untuk mengedit halaman ini.');

        return view('pages.edit', compact('page'));
    }

    // =========================================================================
    // UPDATE — Simpan perubahan page
    // =========================================================================

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        abort_if((int) $page->uid !== (int) Auth::id() && !$isAdmin, 403, 'Anda tidak memiliki izin untuk mengedit halaman ini.');

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,webp', 'max:5120'],
        ]);

        $updateData = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? $page->description,
            'updated'     => time(),
        ];

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($page->logo && Storage::disk('public')->exists($page->logo)) {
                Storage::disk('public')->delete($page->logo);
            }
            // Simpan ke storage/app/public/pages_logo
            $path = Storage::disk('public')->put('pages_logo', $request->file('logo'));
            $updateData['logo'] = $path;
        }

        $page->update($updateData);

        return redirect()
            ->route('pages.show', $page->id)
            ->with('success', 'Halaman berhasil diperbarui!');
    }

    // =========================================================================
    // DESTROY — Hapus page (hanya pemilik)
    // =========================================================================

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        abort_if($page->uid !== Auth::id(), 403, 'Anda tidak memiliki izin untuk menghapus halaman ini.');

        // Bersihkan stream/post milik page ini
        \Illuminate\Support\Facades\DB::table('jcow_streams')
            ->where('wall_id', $id)
            ->where('app', 'page')
            ->delete();

        // Hapus logo dari storage jika ada
        if ($page->logo && Storage::disk('public')->exists($page->logo)) {
            Storage::disk('public')->delete($page->logo);
        }

        // Detach semua followers dari pivot (tidak ada FK constraint di DB)
        $page->followers()->detach();

        $page->delete();

        return redirect()
            ->route('pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }

    // =========================================================================
    // LIKE — User menyukai sebuah page
    // =========================================================================

    public function like($id)
    {
        $page = Page::findOrFail($id);

        $userId = Auth::id();

        // syncWithoutDetaching agar tidak double-insert jika sudah like
        $page->followers()->syncWithoutDetaching([$userId]);

        // Update cache count di kolom 'users' pada tabel jcow_pages
        $page->update(['users' => $page->followers()->count()]);

        if (request()->expectsJson()) {
            return response()->json([
                'liked'       => true,
                'likes_count' => $page->followers()->count(),
            ]);
        }

        return back()->with('success', 'Kamu menyukai halaman ini!');
    }

    // =========================================================================
    // UNLIKE — User batal menyukai sebuah page
    // =========================================================================

    public function unlike($id)
    {
        $page = Page::findOrFail($id);

        $userId = Auth::id();

        // Secara eksplisit hapus relasi dari pivot
        \DB::table('jcow_page_users')
            ->where('pid', $id)
            ->where('uid', $userId)
            ->delete();

        // Update cache count
        $page->update(['users' => $page->followers()->count()]);

        if (request()->expectsJson()) {
            return response()->json([
                'liked'       => false,
                'likes_count' => $page->followers()->count(),
            ]);
        }

        return back()->with('success', 'Kamu batal menyukai halaman ini.');
    }

    public function postStream(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $userId = Auth::id();
        
        $isOwner = ($page->uid == $userId);
        if (!$isOwner) abort(403, 'Hanya admin halaman yang dapat memposting.');

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'photos' => 'nullable|array|max:10',
            'video_url' => 'nullable|string|max:255',
        ]);

        if (empty($request->message) && !$request->hasFile('photos') && empty($request->video_url)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Postingan tidak boleh kosong.'
                ], 422);
            }
            return back()->withErrors(['message' => 'Postingan tidak boleh kosong.']);
        }

        $attachment = '';
        $type = 1; // 1 = teks
        $albumId = (int) $request->input('album_id', 0);
        $videoAlbumId = (int) $request->input('video_album_id', 0);

        if ($request->hasFile('photos')) {
            if ($albumId > 0) {
                $exists = Album::where('id', $albumId)->exists();
                if (!$exists) {
                    $albumId = 0;
                }
            }
            if ($albumId === 0) {
                $defaultAlbum = Album::firstOrCreate(
                    ['gid' => $userId, 'app' => 'photos', 'name' => 'Unggahan Halaman ' . $page->name],
                    ['description' => 'Foto yang diunggah di halaman ' . $page->name, 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $albumId = $defaultAlbum->id;
            }

            $files = $request->file('photos');
            $savedPhotos = [];
            foreach($files as $index => $file) {
                $filename = 'POST-' . $userId . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $file->storeAs('posts', $filename, 'public');
                $savedPhotos[] = $filename;

                Photo::create([
                    'sid'   => $albumId,
                    'uri'   => 'posts/' . $filename,
                    'des'   => $request->message ?? '',
                    'thumb' => 'posts/' . $filename,
                    'size'  => $file->getSize(),
                ]);
            }
            $attachment = json_encode([
                'photos' => $savedPhotos,
                'album_id' => $albumId
            ]);
            $type = 2; // 2 = gambar
        } elseif (!empty($request->video_url)) {
            if ($videoAlbumId > 0) {
                $exists = AlbumVideo::where('id', $videoAlbumId)->exists();
                if (!$exists) {
                    $videoAlbumId = 0;
                }
            }
            if ($videoAlbumId === 0) {
                $defaultAlbum = AlbumVideo::firstOrCreate(
                    ['gid' => $userId, 'app' => 'video', 'name' => 'Unggahan Halaman ' . $page->name],
                    ['description' => 'Video yang diunggah di halaman ' . $page->name, 'weight' => 0, 'var1' => 'public', 'uri' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '']
                );
                $videoAlbumId = $defaultAlbum->id;
            }

            $attachment = json_encode([
                'video_url' => $request->video_url,
                'album_id' => $videoAlbumId,
                'title' => $request->video_title ?? '',
                'desc' => $request->video_desc ?? ''
            ]);
            $type = 3; // 3 = video

            Video::create([
                'title'   => $request->video_title ?? 'Video Halaman',
                'content' => $request->video_desc ?? ($request->message ?? ''),
                'var1'    => $request->video_url,
                'tags'    => $request->video_tags ?? '',
                'cid'     => $videoAlbumId,
                'uid'     => $userId,
                'app'     => 'video',
                'created' => time(),
                'updated' => time(),
                'views'   => 0,
                'featured'=> 0,
                'sticky'  => 0,
                'closed'  => 0,
                'digg'    => 0,
                'dugg'    => 0,
                'photos'  => 0,
                'rating'  => 0,
                'blob1'   => '',
                'thumbnail' => '',
                'lastreply' => 0,
                'lastreplyuname' => '',
                'lastreplyuid' => 0,
                'comments' => 0,
                'stream_id' => 0,
                'var2' => '',
                'var3' => '',
                'var4' => '',
                'var5' => '',
                'text1' => '',
                'text2' => '',
                'page_id' => $page->id,
                'page_type' => 'page',
            ]);
        }

        $stream = \App\Models\Stream::create([
            'message'    => $request->message ?? '',
            'wall_id'    => $id,
            'uid'        => $userId,
            'attachment' => $attachment,
            'created'    => time(),
            'type'       => $type,
            'app'        => 'page',
            'aid'        => $id,
            'hide'       => 0,
            'likes'      => 0,
            'privacy'    => $request->input('privacy', 'public'),
        ]);

        $stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);
        $html = view('components.single-stream', ['stream' => $stream])->render();

        try {
            \App\Events\StreamCreated::dispatch($stream, $html);
        } catch (\Throwable $e) {
            \Log::warning('Gagal broadcast StreamCreated: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Postingan berhasil diunggah!',
                'stream_id' => $stream->id,
                'html' => $html
            ]);
        }

        return redirect()->route('pages.show', $id)->with('success', 'Postingan berhasil diunggah!');
    }

    public function media(Page $page, $type)
    {
        $query = \App\Models\Stream::where('wall_id', $page->id)->where('app', 'page')->where('attachment', '!=', '');

        if ($type === 'photo') {
            $rawList = \App\Models\Stream::where('wall_id', $page->id)
                ->where('app', 'page')
                ->where('attachment', '!=', '')
                ->where(function($q) {
                    $q->where('type', 2)
                      ->orWhere(function($sq) {
                          $sq->where('type', '!=', 3)->where('attachment', 'not like', 'youtube:%');
                      });
                })
                ->with('user')
                ->orderBy('created', 'desc')
                ->get()
                ->filter(function($stream) {
                    return count($stream->photo_urls) > 0;
                });

            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 12;
            $currentItems = $rawList->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $mediaList = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $rawList->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
            ]);
            $title = 'Foto Halaman';
        } elseif ($type === 'video') {
            $query = \App\Models\Stream::where('wall_id', $page->id)->where('app', 'page')->where('attachment', '!=', '');
            $query->where(function($q) {
                $q->where('type', 3)
                  ->orWhere('attachment', 'like', 'youtube:%');
            });
            $mediaList = $query->with('user')->orderBy('created', 'desc')->paginate(12);
            $title = 'Vidio Halaman';
        } else {
            abort(404);
        }

        return view('pages.media', compact('page', 'type', 'mediaList', 'title'));
    }

    public function followers($id)
    {
        $page = Page::with('followers')->findOrFail($id);
        return view('pages.followers', compact('page'));
    }
}
