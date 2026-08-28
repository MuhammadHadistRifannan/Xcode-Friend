<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\AlbumVideo;

class VideoController extends Controller
{
    /**
     * Halaman index: Menampilkan daftar semua Album video.
     * Eager-load 'videos' agar accessor coverVideo tidak memicu N+1.
     */
    public function index()
    {
        $albums = AlbumVideo::with('videos')->get();
        $isPublic = false;

        return view('video.index', compact('albums', 'isPublic'));
    }

    /**
     * Halaman index publik: Menampilkan daftar album video (tanpa aksi edit/hapus).
     */
    public function publicIndex()
    {
        $albums = AlbumVideo::with('videos')->get();
        $isPublic = true;

        return view('video.index', compact('albums', 'isPublic'));
    }

    /**
     * Halaman form tambah video baru.
     */
    public function create()
    {
        $albums = AlbumVideo::orderBy('name')->get();

        return view('video.create', compact('albums'));
    }

    /**
     * Simpan video baru ke database.
     * Route: POST /videos
     *
     * Input form:
     *  - title          : Judul video (wajib)
     *  - description    : Deskripsi video (opsional)
     *  - youtube_url    : URL YouTube (wajib)
     *  - tags           : Tag, dipisah koma (opsional)
     *  - album_id       : ID album yang sudah ada (opsional)
     *  - new_album_name : Nama album baru jika album_id = 'new' (wajib jika dipilih)
     *  - privacy        : everyone | friends | only_me (opsional, default: everyone)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'youtube_url'    => 'required|url',
            'tags'           => 'nullable|string',
            'album_id'       => 'nullable|string',
            'new_album_name' => 'required_if:album_id,new|nullable|string|max:150',
            'privacy'        => 'nullable|in:everyone,friends,only_me',
        ], [
            'title.required'          => 'Judul video wajib diisi.',
            'youtube_url.required'    => 'URL YouTube wajib diisi.',
            'youtube_url.url'         => 'Format URL tidak valid.',
            'new_album_name.required_if' => 'Nama playlist baru wajib diisi.',
        ]);

        try {
            // ── Tentukan Album ──────────────────────────────────────────
            $albumId = null;

            if ($request->album_id === 'new' && $request->new_album_name) {
                // Buat album baru
                $album = AlbumVideo::create([
                    'name'        => $request->new_album_name,
                    'description' => '',
                    'app'         => 'video',
                    'weight'      => 0,
                    'gid'         => 0, // Wajib diisi karena di DB tidak ada default
                    'var1'        => '',
                    'var2'        => '',
                    'var3'        => '',
                    'var4'        => '',
                    'var5'        => '',
                    'uri'         => '',
                ]);
                $albumId = $album->id;

            } elseif ($request->album_id && is_numeric($request->album_id)) {
                // Gunakan album yang sudah ada (verifikasi dulu)
                $album = AlbumVideo::find($request->album_id);
                $albumId = $album?->id;
            }

            // ── Simpan Video ────────────────────────────────────────────
            // Catatan: jcow_stories memiliki kolom blob1 NOT NULL.
            // Kolom ini digunakan oleh sistem lain; kita isi dengan '' (empty string).
            Video::create([
                'title'   => $request->title,
                'content' => $request->description ?? '',
                'var1'    => $request->youtube_url,       // URL YouTube
                'tags'    => $request->tags ?? '',
                'cid'     => $albumId ?? 0,               // 0 = tanpa album
                'uid'     => 1,                           // TODO: ganti dengan auth()->id()
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
                'page_id' => 0,
                'page_type' => '',
            ]);

            return redirect()
                ->route('video.index')
                ->with('success', 'Video "' . $request->title . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan video: ' . $e->getMessage()]);
        }
    }

    /**
     * Halaman Watch Video: menampilkan pemutar + sidebar playlist album.
     *
     * @param  int|string  $id  ID video di tabel jcow_stories
     */
    public function watch($id)
    {
        // 1. Ambil video beserta relasi albumnya (atau 404 jika tidak ada)
        $video = Video::with('album')->findOrFail($id);

        // 2. Ambil album tempat video ini berada
        $album = $video->album;

        // 3. Ambil semua video di album yang sama, diurutkan berdasarkan waktu dibuat
        $albumVideos = collect();
        if ($album) {
            $albumVideos = Video::where('cid', $album->id)
                                ->orderBy('created', 'asc')
                                ->get();
        }

        return view('video.watch', compact('video', 'album', 'albumVideos'));
    }

    // ════════════════════════════════════════════════════════════════
    //  ALBUM CRUD
    // ════════════════════════════════════════════════════════════════

    /**
     * Halaman form Edit Album Video.
     * Route: GET /video/album/{id}/edit
     */
    public function editAlbum($id)
    {
        $album = AlbumVideo::findOrFail($id);

        // Tentukan video sampul saat ini
        $coverVideo = null;
        if ($album->var1 && is_numeric($album->var1)) {
            $coverVideo = Video::where('cid', $id)->where('id', (int) $album->var1)->first();
        }
        if (!$coverVideo) {
            $coverVideo = Video::where('cid', $id)->orderBy('id', 'desc')->first();
        }

        // Paginate video untuk picker (8 per halaman)
        $videos = Video::where('cid', $id)
                       ->orderBy('created', 'asc')
                       ->paginate(8, ['*'], 'video_page');

        return view('video.edit_album', compact('album', 'coverVideo', 'videos'));
    }

    /**
     * Proses update Album Video.
     * Route: PUT /video/album/{id}
     *
     * Input form:
     *  - name           → Nama album (wajib)
     *  - description    → Deskripsi album (opsional)
     *  - cover_video_id → ID video yang dipilih sebagai sampul (opsional)
     */
    public function updateAlbum(Request $request, $id)
    {
        try {
            $request->validate([
                'name'           => 'required|string|max:150',
                'description'    => 'nullable|string',
                'cover_video_id' => 'nullable|integer',
            ]);

            $album = AlbumVideo::findOrFail($id);

            $updateData = [
                'name'        => $request->name,
                'description' => $request->description ?? '',
            ];

            // Simpan ID sampul ke kolom var1 album jika valid
            if ($request->cover_video_id) {
                $coverExists = Video::where('id', $request->cover_video_id)
                                    ->where('cid', $album->id)
                                    ->exists();
                if ($coverExists) {
                    $updateData['var1'] = (string) $request->cover_video_id;
                }
            }

            $album->update($updateData);

            return redirect()
                ->route('video.index')
                ->with('success', 'Album "' . $album->name . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Update gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Hapus Album beserta semua video di dalamnya.
     * Route: DELETE /video/album/{id}
     */
    public function destroyAlbum($id)
    {
        try {
            $album = AlbumVideo::findOrFail($id);
            $albumName = $album->name;

            // Hapus semua video di album ini
            Video::where('cid', $album->id)->delete();

            // Hapus record album
            $album->delete();

            return redirect()
                ->route('video.index')
                ->with('success', 'Album "' . $albumName . '" beserta semua videonya berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hapus album gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus satu video dari album.
     * Route: DELETE /video/video/{id}
     */
    public function destroyVideo($id)
    {
        try {
            $video = Video::findOrFail($id);
            $albumId = $video->cid;

            // Jika video ini adalah sampul album (var1), clear var1
            $album = AlbumVideo::find($albumId);
            if ($album && (string) $album->var1 === (string) $id) {
                $album->update(['var1' => '']);
            }

            $video->delete();

            return redirect()
                ->route('video.album.edit', $albumId)
                ->with('success', 'Video berhasil dihapus dari album.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hapus video gagal: ' . $e->getMessage()]);
        }
    }
}
