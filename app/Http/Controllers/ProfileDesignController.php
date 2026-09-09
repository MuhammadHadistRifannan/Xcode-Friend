<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileDesignController extends Controller
{
    public function index()
    {
        return view('desain-profil.index');
    }

    public function save(Request $request)
    {
        $request->validate([
            'bg_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
            'bg_color'    => 'nullable|string|max:7',
            'font_color'  => 'nullable|string|max:7',
            'link_color'  => 'nullable|string|max:7',
            'block_bg'    => 'nullable|string|max:7',
            'block_text'  => 'nullable|string|max:7',
            'bg_position' => 'nullable|in:center,left,right,top,bottom',
            'profile_music' => 'nullable|file|mimes:mp3|max:10240',
        ]);

        $user = auth()->user();

        // Ambil data profil atau buat baru
        $profile = Profile::firstOrCreate(
            ['id' => $user->id],
            [
                'style_ids'  => '',
                'custom_css' => '{}',
                'background' => '',
                'videoid'    => 0,
                'favorites'  => 0,
                'views'      => 0,
            ]
        );

        // Ambil tema yang sudah ada (PENTING: merge, bukan overwrite)
        $theme = json_decode($profile->custom_css ?? '{}', true);
        if (!is_array($theme)) $theme = [];

        // ── Music Player Section ──
        $theme['musicplayer'] = $request->boolean('musicplayer');

        if ($request->hasFile('profile_music') && $request->file('profile_music')->isValid()) {
            $musicFile = $request->file('profile_music');
            $musicName = 'PROFILE-MUSIC-' . $user->username . '-' . time() . '.' . $musicFile->getClientOriginalExtension();
            $musicFile->storeAs('music', $musicName, 'public');

            if (!empty($theme['profile_music'])) {
                $oldMusicPath = storage_path('app/public/music/' . $theme['profile_music']);
                if (file_exists($oldMusicPath)) {
                    @unlink($oldMusicPath);
                }
            }
            $theme['profile_music'] = $musicName;
        }

        // ── Wallpaper Section ──
        // Simpan status toggle
        $theme['wallpaper_enabled'] = $request->boolean('wallpaper_enabled');

        // Hanya update warna jika input valid (tidak kosong & format hex benar)
        $newBgColor   = $this->sanitizeColor($request->input('bg_color'),   null);
        $newFontColor = $this->sanitizeColor($request->input('font_color'), null);
        $newLinkColor = $this->sanitizeColor($request->input('link_color'), null);
        if ($newBgColor)   $theme['bg_color']   = $newBgColor;
        if ($newFontColor) $theme['font_color']  = $newFontColor;
        if ($newLinkColor) $theme['link_color']  = $newLinkColor;

        // Selalu simpan pengaturan posisi & repeat
        $theme['bg_position'] = $request->input('bg_position', $theme['bg_position'] ?? 'center');
        $theme['repeat_x']    = $request->boolean('repeat_x');
        $theme['repeat_y']    = $request->boolean('repeat_y');
        $theme['transparent'] = $request->boolean('transparent');

        // Auto-aktifkan wallpaper_enabled jika ada data warna atau gambar tersimpan
        if (!empty($theme['bg_color']) || !empty($theme['font_color']) || !empty($theme['link_color']) || !empty($theme['bg_image'])) {
            if ($request->boolean('wallpaper_enabled')) {
                $theme['wallpaper_enabled'] = true;
            }
        }

        // ── Block Section ──
        $theme['block_enabled'] = $request->boolean('block_enabled');

        $newBlockBg   = $this->sanitizeColor($request->input('block_bg'),   null);
        $newBlockText = $this->sanitizeColor($request->input('block_text'),  null);
        if ($newBlockBg)   $theme['block_bg']   = $newBlockBg;
        if ($newBlockText) $theme['block_text']  = $newBlockText;

        // Proses upload gambar background
        if ($request->hasFile('bg_image') && $request->file('bg_image')->isValid()) {
            $file     = $request->file('bg_image');
            $filename = 'BG-' . $user->username . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('backgrounds', $filename, 'public');

            // Hapus file lama jika ada
            if (!empty($theme['bg_image'])) {
                $oldPath = storage_path('app/public/backgrounds/' . $theme['bg_image']);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Simpan hanya ke custom_css (BUKAN ke profile->background)
            // profile->background digunakan untuk banner cover profil — harus tetap terpisah
            $theme['bg_image'] = $filename;
        }

        // Simpan JSON ke kolom custom_css
        $profile->custom_css = json_encode($theme);
        $profile->save();

        return back()->with('success', 'Desain profil berhasil disimpan!');
    }

    public function destroyBackground()
    {
        $user    = auth()->user();
        $profile = Profile::where('id', $user->id)->first();

        if ($profile) {
            $theme = json_decode($profile->custom_css ?? '{}', true);
            if (!is_array($theme)) $theme = [];

            // Hapus file lama
            if (!empty($theme['bg_image'])) {
                $oldPath = storage_path('app/public/backgrounds/' . $theme['bg_image']);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $theme['bg_image']  = null;
            $profile->custom_css = json_encode($theme);
            $profile->background = '';
            $profile->save();
        }

        return back()->with('success', 'Gambar background berhasil dihapus.');
    }

    /**
     * Sanitasi input warna hex.
     * Return null jika nilai tidak valid (agar tidak menimpa data lama).
     */
    private function sanitizeColor(?string $value, ?string $default): ?string
    {
        if ($value && preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            return $value;
        }
        return $default;
    }
}
