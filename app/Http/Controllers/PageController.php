<?php

namespace App\Http\Controllers;

use App\Models\Page;
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
        // Fallback user ID 1 jika tidak ada session/user
        $userId = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

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
        return view('pages.create');
    }

    // =========================================================================
    // STORE — Simpan page baru ke database
    // =========================================================================

    public function store(Request $request)
    {
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
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'uri.regex'  => 'URL hanya boleh mengandung huruf kecil (a-z) dan angka (0-9).',
            'uri.unique' => 'URL halaman ini sudah digunakan, pilih yang lain.',
            'uri.min'    => 'URL minimal 6 karakter.',
        ]);

        // TODO: Ganti dengan Auth::id() setelah middleware Auth aktif
        $uid = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

        $page = Page::create([
            'uri'         => $validated['uri'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? '',
            'logo'        => '',   // logo diupload terpisah via updateLogo()
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
        $page = Page::withCount('followers')->findOrFail($id);

        // Fallback ke user ID 1 jika Auth belum aktif untuk keperluan testing
        $userId = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

        // Cek apakah user sudah menyukai page ini
        $isLiked = $page->followers()->where('uid', $userId)->exists();

        // Cek apakah user adalah pemilik page
        $isOwner = ($page->uid == $userId);

        // Mock data untuk posts & sidebar (ganti dengan query real nanti)
        $pageData = [
            'profile' => [
                'id'          => $page->id,
                'name'        => $page->name,
                'isVerified'  => false,
                'description' => $page->description,
                'likesCount'  => $page->followers_count,
                'coverUrl'    => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=1000',
                'avatarUrl'   => $page->logo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($page->name) . '&background=ff0000&color=fff',
                'isLiked'     => $isLiked,
                'isOwner'     => $isOwner,
            ],
            'posts'        => [],
            'reviews'      => ['rating' => 0, 'count' => 0],
            'networkLinks' => [],
        ];

        return view('pages.show', compact('page', 'pageData', 'isLiked'));
    }

    // =========================================================================
    // EDIT — Tampilkan form edit (hanya pemilik)
    // =========================================================================

    public function edit($id)
    {
        $page = Page::findOrFail($id);

        // TODO: Aktifkan pengecekan kepemilikan setelah Auth middleware aktif
        // abort_if($page->uid !== Auth::id(), 403, 'Anda tidak memiliki izin untuk mengedit page ini.');

        return view('pages.edit', compact('page'));
    }

    // =========================================================================
    // UPDATE — Simpan perubahan page
    // =========================================================================

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

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

        // TODO: Aktifkan setelah Auth middleware aktif
        // abort_if($page->uid !== Auth::id(), 403);

        // TODO: Hapus relasi yang tergantung sebelum menghapus page:
        //   - Hapus semua stories milik page ini: Story::where('page_id', $id)->delete();
        //   - Hapus semua photos album page: PagePhoto::where('page_id', $id)->delete();
        //   - Hapus pivot followers:          $page->followers()->detach();
        //   - Hapus logo dari storage jika ada: Storage::disk('public')->delete($page->logo);

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

        // TODO: Ganti 1 dengan Auth::id() setelah middleware Auth aktif
        $userId = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

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

        $userId = Auth::id() ?? (optional(\App\Models\User::first())->id ?? 1);

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
}
