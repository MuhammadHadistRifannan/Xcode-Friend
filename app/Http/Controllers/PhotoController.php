<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Album;
use App\Models\Photo;

class PhotoController extends Controller
{
    /**
     * Halaman index: Menampilkan daftar semua Album foto.
     * Setiap album sudah ter-eager-load dengan foto terbaru (untuk cover).
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $query = Album::whereIn('app', ['photos', 'photo'])->with('latestPhoto');

        if ($filter === 'mine' && auth()->check()) {
            $query->where('gid', auth()->id());
        } else {
            $query->where(function ($q) {
                $q->has('photos');
                if (auth()->check()) {
                    $q->orWhere('gid', auth()->id());
                }
            });
        }

        $albums = $query->orderBy('id', 'desc')->paginate(16)->withQueryString();

        return view('photos.index', compact('albums', 'filter'));
    }

    /**
     * Halaman upload form.
     * Ambil daftar album dari DB untuk ditampilkan di dropdown pilih album.
     */
    public function create()
    {
        // Ambil semua album milik user dari tabel jcow_story_categories
        $albums = Album::whereIn('app', ['photos', 'photo'])->where('gid', auth()->id())->orderBy('name')->get();

        return view('foto.upload', compact('albums'));
    }

    /**
     * Halaman detail album: Menampilkan foto di dalam satu album (8 per halaman).
     */
    public function show($id)
    {
        $album = Album::findOrFail($id);

        if (auth()->check() && $album->gid > 0 && (int) $album->gid !== (int) auth()->id()) {
            $friendRepo = app(\App\Repositories\Contracts\FriendRepositoryInterface::class);
            if ($friendRepo->areBlocked(auth()->id(), (int) $album->gid)) {
                abort(404, 'Album tidak ditemukan.');
            }
        }

        // Paginate 12 foto per halaman, diurutkan dari terbaru
        $photos = $album->photos()->orderBy('id', 'desc')->paginate(12);

        if (request()->ajax()) {
            $html = '';
            foreach ($photos as $photo) {
                $html .= view('photos.partials._photo-item', compact('photo', 'album'))->render();
            }
            return response()->json([
                'html' => $html,
                'hasMore' => $photos->hasMorePages(),
            ]);
        }

        return view('photos.show', compact('album', 'photos'));
    }

    /**
     * Proses penyimpanan foto yang diunggah.
     * Nama input form: photos[], album_id, new_album_name, deskripsi[]
     */
    public function store(Request $request)
    {
        try {
            // 1. Validasi Input
            // 'photos' HARUS SAMA dengan name="photos[]" di form
            $request->validate([
                'photos'         => 'required|array|min:1',
                'photos.*'       => 'image|mimes:jpeg,png,jpg,gif|max:10240',
                'album_id'       => 'nullable',
                'new_album_name' => 'nullable|string|max:150',
                'deskripsi'      => 'nullable|array',
            ]);

            // TODO: Ganti dengan Auth::id() setelah sistem auth aktif
            $albumId = null;

            // 2. Buat album baru jika new_album_name diisi
            if (!empty($request->new_album_name)) {
                $album = Album::create([
                    'name'        => $request->new_album_name,
                    'description' => '',
                    'gid'         => auth()->id(),
                    'weight'      => 0,
                    'app'         => 'photos',
                    'uri'         => '',
                    'var1'        => '',
                    'var2'        => '',
                    'var3'        => '',
                    'var4'        => '',
                    'var5'        => '',
                ]);
                $albumId = $album->id;

            } elseif ($request->album_id && $request->album_id !== 'new') {
                // Gunakan album yang sudah ada
                $albumId = (int) $request->album_id;
            }

            if (!$albumId) {
                return back()
                    ->withErrors(['album_id' => 'Silakan pilih album yang ada atau buat album baru.'])
                    ->withInput();
            }

            $savedPhotos = [];
            $allDescs = [];

            // 3. Looping dan simpan tiap foto
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $file) {
                    // Simpan file ke storage/app/public/photos
                    $path = Storage::disk('public')->put('photos', $file);
                    $deskripsi = $request->deskripsi[$index] ?? ($request->deskripsi[0] ?? '');

                    // 4. Simpan ke tabel jcow_story_photos
                    Photo::create([
                        'sid'   => $albumId,
                        'uri'   => $path,
                        'des'   => $deskripsi,
                        'thumb' => $path,
                        'size'  => $file->getSize(),
                    ]);

                    $savedPhotos[] = $path;
                    if (!empty($deskripsi)) {
                        $allDescs[] = $deskripsi;
                    }
                }
            }

            // Buat record Stream agar foto muncul di feed beranda dan tab foto profil
            if (count($savedPhotos) > 0) {
                $album = Album::find($albumId);
                $message = !empty($allDescs) ? implode("\n", array_unique($allDescs)) : '';
                if (empty($message) && $album) {
                    $message = 'Mengunggah foto ke album ' . $album->name;
                }

                $stream = \App\Models\Stream::create([
                    'message'    => $message,
                    'uid'        => auth()->id(),
                    'created'    => time(),
                    'type'       => 2, // 2 = gambar
                    'app'        => 'photos',
                    'wall_id'    => 0,
                    'attachment' => json_encode([
                        'photos'   => $savedPhotos,
                        'album_id' => $albumId,
                    ]),
                    'aid'        => $albumId,
                    'hide'       => 0,
                    'likes'      => 0,
                    'privacy'    => 'public',
                ]);

                try {
                    $stream->loadMissing(['user', 'comments.user', 'targetPage', 'targetWallUser']);
                    $html = view('components.single-stream', ['stream' => $stream])->render();
                    \App\Events\StreamCreated::dispatch($stream, $html);
                } catch (\Throwable $e) {
                    \Log::warning('Gagal broadcast StreamCreated dari PhotoController: ' . $e->getMessage());
                }
            }

            if ($request->input('from') === 'profile' && auth()->check()) {
                return redirect()->route('profile.show', [auth()->user()->username, 'tab' => 'foto'])->with('success', 'Foto berhasil diunggah!');
            }

            // 5. Redirect dengan pesan sukses
            return redirect()->route('foto.index')->with('success', 'Foto berhasil diunggah!');

        } catch (\Exception $e) {
            // DEBUG: Tampilkan error — hapus blok ini saat production!
            return back()
                ->withErrors(['error' => 'Upload gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Tampilkan form Edit Album.
     * Route: GET /foto/album/{id}/edit
     */
    public function editAlbum($id)
    {
        // Ambil album + cover saat ini
        $album = Album::with('latestPhoto')->findOrFail($id);
        abort_if($album->gid != auth()->id(), 403, 'Anda tidak memiliki izin untuk mengedit album ini.');

        // Hapus otomatis record foto jika file fisiknya sudah terhapus dari disk
        $orphanedPhotos = $album->photos()->get()->filter(function ($photo) {
            return !$photo->fileExists();
        });
        if ($orphanedPhotos->isNotEmpty()) {
            Photo::whereIn('id', $orphanedPhotos->pluck('id'))->delete();
        }

        // Paginate foto untuk photo picker (8 per halaman)
        $photos = $album->photos()->orderBy('id', 'desc')->paginate(8, ['*'], 'photo_page');

        return view('photos.edit_album', compact('album', 'photos'));
    }

    /**
     * Proses Update Album (nama & opsional ganti sampul dari foto yang sudah ada).
     * Route: PUT /foto/album/{id}
     *
     * Input form:
     *  - name           => Nama album baru (wajib) — name="name"
     *  - cover_photo_id => ID foto yang dipilih sebagai sampul (opsional) — name="cover_photo_id"
     */
    public function updateAlbum(Request $request, $id)
    {
        try {
            // 1. Validasi input
            $request->validate([
                // 'name' HARUS SAMA dengan name="name" di form
                'name'           => 'required|string|max:150',
                // 'cover_photo_id' HARUS SAMA dengan name="cover_photo_id" (input hidden) di form
                'cover_photo_id' => 'nullable|integer|exists:jcow_story_photos,id',
            ]);

            $album = Album::findOrFail($id);
            abort_if($album->gid != auth()->id(), 403, 'Anda tidak memiliki izin untuk memperbarui album ini.');

            // 2. Update nama album di tabel jcow_story_categories
            $album->update(['name' => $request->name]);

            // 3. Jika user memilih foto sebagai sampul baru
            if ($request->cover_photo_id) {
                $selectedPhoto = Photo::where('id', $request->cover_photo_id)
                    ->where('sid', $album->id) // Pastikan foto ini milik album ini
                    ->firstOrFail();

                // Strategi: jadikan foto terpilih sebagai yang ber-ID paling besar
                // dengan cara mengubah ID-nya menjadi max(id)+1 agar latestPhoto
                // (orderBy id desc) menunjuk ke foto ini.
                // Cara lebih bersih: simpan cover_photo_id di kolom 'uri' album.
                // Karena skema tidak berubah, kita gunakan pendekatan:
                // Duplikat record foto terpilih dengan ID baru (hapus duplikat lama jika ada)
                $maxId = Photo::max('id');

                // Buat record baru dengan data sama, ID menjadi terbesar
                Photo::create([
                    'sid'   => $selectedPhoto->sid,
                    'uri'   => $selectedPhoto->uri,
                    'des'   => $selectedPhoto->des,
                    'thumb' => $selectedPhoto->thumb,
                    'size'  => $selectedPhoto->size,
                ]);

                // Hapus record lama yang sudah diduplikat
                // (agar tidak ada duplikat gambar di halaman detail album)
                $selectedPhoto->delete();
            }

            // 4. Redirect ke index dengan pesan sukses
            return redirect()->route('foto.index')
                ->with('success', 'Album "' . $album->name . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Update gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Hapus Album beserta semua fotonya.
     * Route: DELETE /foto/album/{id}
     */
    public function destroyAlbum($id)
    {
        try {
            $album = Album::with('photos')->findOrFail($id);
            abort_if($album->gid != auth()->id(), 403, 'Anda tidak memiliki izin untuk menghapus album ini.');

            $albumName = $album->name;

            // Hapus semua file foto dari storage sebelum hapus record DB
            foreach ($album->photos as $photo) {
                $cleanUri = ltrim(trim($photo->uri), '/');
                $filename = basename($cleanUri);
                $pathsToDelete = [
                    $cleanUri,
                    'posts/' . $cleanUri,
                    'photos/' . $cleanUri,
                    'posts/' . $filename,
                    'photos/' . $filename,
                    str_replace(['storage/', 'uploads/'], '', $cleanUri),
                ];
                if ($photo->thumb) {
                    $cleanThumb = ltrim(trim($photo->thumb), '/');
                    $thumbName = basename($cleanThumb);
                    $pathsToDelete[] = $cleanThumb;
                    $pathsToDelete[] = 'posts/' . $cleanThumb;
                    $pathsToDelete[] = 'photos/' . $cleanThumb;
                    $pathsToDelete[] = 'posts/' . $thumbName;
                    $pathsToDelete[] = 'photos/' . $thumbName;
                    str_replace(['storage/', 'uploads/'], '', $cleanThumb);
                }
                foreach (array_unique($pathsToDelete) as $p) {
                    if (!empty($p) && Storage::disk('public')->exists($p)) {
                        Storage::disk('public')->delete($p);
                    }
                }
            }

            // Hapus semua record foto di DB (jcow_story_photos)
            $album->photos()->delete();

            // Hapus record postingan stream terkait album ini
            \App\Models\Stream::where('uid', auth()->id())
                ->where(function($q) use ($id) {
                    $q->where('aid', $id)
                      ->orWhere('attachment', 'LIKE', '%"album_id":' . $id . '%')
                      ->orWhere('attachment', 'LIKE', '%"album_id":"' . $id . '"%');
                })->delete();

            // Hapus record album di DB (jcow_story_categories)
            $album->delete();

            return redirect()->route('foto.index')
                ->with('success', 'Album "' . $albumName . '" beserta semua fotonya berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hapus gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Update deskripsi sebuah foto (dari inline edit di card).
     * Route: PUT /foto/photo/{id}
     * Input: des (name="des" di form inline)
     */
    public function updatePhoto(Request $request, $id)
    {
        try {
            $request->validate([
                // 'des' HARUS SAMA dengan name="des" di textarea form inline
                'des' => 'nullable|string|max:255',
            ]);

            $photo = Photo::with('album')->findOrFail($id);
            abort_if(!$photo->album || $photo->album->gid != auth()->id(), 403, 'Anda tidak memiliki izin untuk mengedit foto ini.');

            $photo->update(['des' => $request->des ?? '']);

            return redirect()->route('foto.show', $photo->sid)
                ->with('success', 'Deskripsi foto berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Update deskripsi gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus satu foto dari album.
     * Route: DELETE /foto/photo/{id}
     */
    public function destroyPhoto($id)
    {
        try {
            $photo = Photo::with('album')->findOrFail($id);
            abort_if(!$photo->album || $photo->album->gid != auth()->id(), 403, 'Anda tidak memiliki izin untuk menghapus foto ini.');

            $albumId = $photo->sid; // Simpan album_id untuk redirect kembali ke halaman detail album
            $photoUri = $photo->uri;
            $cleanUri = ltrim(trim($photo->uri), '/');
            $filename = basename($cleanUri);

            // Hapus file dari storage disk public
            $pathsToDelete = [
                $cleanUri,
                'posts/' . $cleanUri,
                'photos/' . $cleanUri,
                'posts/' . $filename,
                'photos/' . $filename,
                str_replace(['storage/', 'uploads/'], '', $cleanUri),
            ];
            if ($photo->thumb) {
                $cleanThumb = ltrim(trim($photo->thumb), '/');
                $thumbName = basename($cleanThumb);
                $pathsToDelete[] = $cleanThumb;
                $pathsToDelete[] = 'posts/' . $cleanThumb;
                $pathsToDelete[] = 'photos/' . $cleanThumb;
                $pathsToDelete[] = 'posts/' . $thumbName;
                $pathsToDelete[] = 'photos/' . $thumbName;
                str_replace(['storage/', 'uploads/'], '', $cleanThumb);
            }

            foreach (array_unique($pathsToDelete) as $p) {
                if (!empty($p) && Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }

            // Hapus record dari tabel jcow_story_photos
            $photo->delete();

            // Sinkronkan stream yang menggunakan foto ini
            $relatedStreams = \App\Models\Stream::where('uid', auth()->id())
                ->where(function($q) use ($photoUri, $filename, $cleanUri) {
                    $q->where('attachment', 'LIKE', '%' . $photoUri . '%')
                      ->orWhere('attachment', 'LIKE', '%' . $filename . '%')
                      ->orWhere('attachment', 'LIKE', '%' . $cleanUri . '%');
                })->get();

            foreach ($relatedStreams as $relStream) {
                $att = json_decode($relStream->attachment, true);
                if (isset($att['photos']) && is_array($att['photos'])) {
                    $att['photos'] = array_values(array_filter($att['photos'], function($p) use ($photoUri, $filename, $cleanUri) {
                        return $p !== $photoUri && $p !== $filename && $p !== $cleanUri && basename($p) !== $filename;
                    }));

                    if (empty($att['photos'])) {
                        if (empty(trim($relStream->message)) || str_starts_with($relStream->message, 'Mengunggah foto ke album')) {
                            $relStream->delete();
                            continue;
                        }
                    }
                    $relStream->attachment = json_encode($att);
                    $relStream->save();
                }
            }

            // Redirect kembali ke halaman detail album
            return redirect()->route('foto.show', $albumId)
                ->with('success', 'Foto berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hapus foto gagal: ' . $e->getMessage()]);
        }
    }
}
