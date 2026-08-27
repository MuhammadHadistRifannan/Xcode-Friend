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
    public function index()
    {
        // Eager load relasi latestPhoto agar tidak terjadi N+1 query
        $albums = Album::with('latestPhoto')->get();

        return view('photos.index', compact('albums'));
    }

    /**
     * Halaman upload form.
     * Ambil daftar album dari DB untuk ditampilkan di dropdown pilih album.
     */
    public function create()
    {
        // Ambil semua album dari tabel jcow_story_categories
        $albums = Album::orderBy('name')->get();

        return view('foto.upload', compact('albums'));
    }

    /**
     * Halaman detail album: Menampilkan foto di dalam satu album (8 per halaman).
     */
    public function show($id)
    {
        $album = Album::findOrFail($id);
        // Paginate 8 foto per halaman, diurutkan dari terbaru
        $photos = $album->photos()->orderBy('id', 'desc')->paginate(8);

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
                    'gid'         => 0,
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

            // 3. Looping dan simpan tiap foto
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $file) {
                    // Simpan file ke storage/app/public/photos
                    $path = Storage::disk('public')->put('photos', $file);
                    $deskripsi = $request->deskripsi[$index] ?? '';

                    // 4. Simpan ke tabel jcow_story_photos
                    Photo::create([
                        'sid'   => $albumId,
                        'uri'   => $path,
                        'des'   => $deskripsi,
                        'thumb' => $path,
                        'size'  => $file->getSize(),
                    ]);
                }
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
            $albumName = $album->name;

            // Hapus semua file foto dari storage sebelum hapus record DB
            foreach ($album->photos as $photo) {
                if (Storage::disk('public')->exists($photo->uri)) {
                    Storage::disk('public')->delete($photo->uri);
                }
            }

            // Hapus semua record foto di DB (jcow_story_photos)
            $album->photos()->delete();

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

            $photo = Photo::findOrFail($id);
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
            $photo = Photo::findOrFail($id);
            $albumId = $photo->sid; // Simpan album_id untuk redirect kembali ke halaman detail album

            // Hapus file dari storage disk public
            if (Storage::disk('public')->exists($photo->uri)) {
                Storage::disk('public')->delete($photo->uri);
            }

            // Hapus juga thumbnail jika berbeda dari uri
            if ($photo->thumb && $photo->thumb !== $photo->uri) {
                if (Storage::disk('public')->exists($photo->thumb)) {
                    Storage::disk('public')->delete($photo->thumb);
                }
            }

            // Hapus record dari tabel jcow_story_photos
            $photo->delete();

            // Redirect kembali ke halaman detail album
            return redirect()->route('foto.show', $albumId)
                ->with('success', 'Foto berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hapus foto gagal: ' . $e->getMessage()]);
        }
    }
}
