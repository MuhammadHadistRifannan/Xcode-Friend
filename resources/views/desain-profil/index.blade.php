@extends('layouts.app')
@section('title', 'Desain Profil - XCODE-FRIENDS')

@section('content')
@php
    $profileRaw = auth()->user()->profile;
    $theme = $profileRaw ? json_decode($profileRaw->custom_css ?? '{}', true) : [];
    $theme = is_array($theme) ? $theme : [];

    // Baca nilai tersimpan
    $bg_color    = $theme['bg_color']    ?? '#ffffff';
    $font_color  = $theme['font_color']  ?? '#111827';
    $link_color  = $theme['link_color']  ?? '#b91c1c';
    $block_bg    = $theme['block_bg']    ?? '#ffffff';
    $block_text  = $theme['block_text']  ?? '#111827';
    $repeat_x    = $theme['repeat_x']    ?? false;
    $repeat_y    = $theme['repeat_y']    ?? false;
    $transparent = $theme['transparent'] ?? false;
    $bg_position = $theme['bg_position'] ?? 'center';
    $bg_image    = $theme['bg_image']    ?? null;
    $musicplayer = $theme['musicplayer'] ?? false;
    $profile_music = $theme['profile_music'] ?? null;

    // Logika toggle:
    // - Jika 'wallpaper_enabled' sudah pernah disimpan user → gunakan nilainya langsung (hormati pilihan user)
    // - Jika belum pernah ada di DB (pertama kali buka) → auto-deteksi dari data yang ada
    if (array_key_exists('wallpaper_enabled', $theme)) {
        // User pernah eksplisit set → pakai nilai itu
        $wallpaper_enabled = (bool) $theme['wallpaper_enabled'];
    } else {
        // Belum pernah diset → auto-deteksi jika ada data
        $wallpaper_enabled = $bg_image || isset($theme['bg_color']) || isset($theme['font_color']) || isset($theme['link_color']);
    }

    if (array_key_exists('block_enabled', $theme)) {
        $block_enabled = (bool) $theme['block_enabled'];
    } else {
        $block_enabled = isset($theme['block_bg']) || isset($theme['block_text']);
    }
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">Pengaturan / Desain Profil</div>
        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Desain Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Personalisasi tampilan halaman profilmu agar tampil unik.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">

        {{-- ===== KOLOM KIRI: PREVIEW ===== --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="sticky top-6">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Preview Tampilan</div>

                {{-- Mock browser window --}}
                <div class="rounded-xl overflow-hidden border-2 border-gray-200 shadow-lg">
                    {{-- Browser chrome --}}
                    <div class="bg-gray-100 px-3 py-2 flex items-center gap-1.5 border-b border-gray-200">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <div class="flex-1 ml-2 bg-white rounded text-[10px] text-gray-400 px-2 py-0.5">xcode-friends.com/@{{ auth()->user()->username }}</div>
                    </div>

                    {{-- Preview content --}}
                    <div id="preview-wrapper" class="p-4 transition-all duration-300"
                         style="background-color: {{ $bg_color }}; color: {{ $font_color }};">
                        {{-- Mock cover --}}
                        <div class="w-full h-16 rounded-lg mb-3 relative overflow-hidden"
                             style="background: linear-gradient(135deg, #e5e7eb, #d1d5db);">
                            @if($bg_image)
                                <img src="{{ asset('storage/backgrounds/' . $bg_image) }}" class="w-full h-full object-cover" alt="">
                            @endif
                        </div>
                        {{-- Mock avatar & name --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full bg-red-700 flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr(auth()->user()->fullname ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div id="preview-name" class="text-xs font-bold" style="color: {{ $font_color }}">{{ auth()->user()->fullname }}</div>
                                <div id="preview-link" class="text-[10px]" style="color: {{ $link_color }}">@{{ auth()->user()->username }}</div>
                            </div>
                        </div>
                        {{-- Mock blocks --}}
                        <div id="preview-block" class="rounded-lg p-3 mb-2" style="background-color: {{ $block_bg }}; border: 1px solid rgba(0,0,0,0.08);">
                            <div class="text-[10px] font-bold uppercase mb-1" style="color: {{ $block_text }}">Tentang Saya</div>
                            <div class="space-y-1">
                                <div class="h-1.5 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-1.5 bg-gray-200 rounded w-1/2"></div>
                            </div>
                        </div>
                        <div class="rounded-lg p-3" style="background-color: {{ $block_bg }}; border: 1px solid rgba(0,0,0,0.08);">
                            <div class="text-[10px] font-bold uppercase mb-1" style="color: {{ $block_text }}">Teman</div>
                            <div class="flex gap-1">
                                <div class="w-7 h-7 rounded bg-gray-200"></div>
                                <div class="w-7 h-7 rounded bg-gray-200"></div>
                                <div class="w-7 h-7 rounded bg-gray-200"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current bg image --}}
                @if($bg_image)
                    <div class="mt-4 text-xs text-gray-500">
                        <span class="font-medium">Background saat ini:</span>
                        <img src="{{ asset('storage/backgrounds/' . $bg_image) }}" class="mt-2 w-full h-20 object-cover rounded-lg border border-gray-200" alt="">
                        {{-- Tombol hapus --}}
                        <form action="{{ route('desain-profil.destroy-bg') }}" method="POST" class="mt-2">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus gambar background?')" class="text-red-600 hover:text-red-800 text-xs font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Hapus Gambar Background
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== KOLOM KANAN: FORM ===== --}}
        <div class="col-span-12 lg:col-span-8">

            @if(session('success'))
                <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2 text-sm text-green-700 font-medium">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('desain-profil.save') }}" method="POST" enctype="multipart/form-data" id="design-form">
                @csrf

                {{-- ── 0. PEMUTAR MUSIK ── --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-sm font-bold text-gray-800">Pemutar Musik (Music Player)</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Mainkan musik yang kusukai secara otomatis di halaman profilku.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="musicplayer" id="musicplayer" value="1" class="sr-only peer" {{ $musicplayer ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-red-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <div id="music-section" class="{{ !$musicplayer ? 'hidden' : '' }} p-5 space-y-4 border-t border-gray-100">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unggah File Musik (.mp3)</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            @if($profile_music)
                                <div class="flex-1 w-full bg-gray-50 p-3 rounded-xl border border-gray-200">
                                    <p class="text-xs font-semibold text-gray-600 mb-2 truncate">Saat ini: {{ $profile_music }}</p>
                                    <audio controls class="w-full h-8 outline-none">
                                        <source src="{{ asset('storage/music/' . $profile_music) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                            <div class="flex-1 w-full">
                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden group">
                                    <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                        <svg class="w-6 h-6 text-gray-400 mb-2 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold text-red-600">Klik untuk upload</span></p>
                                        <p class="text-xs text-gray-400 mt-1" id="music-filename-preview">Maks 10MB (.mp3)</p>
                                    </div>
                                    <input type="file" name="profile_music" id="profile_music_input" class="hidden" accept=".mp3,audio/mpeg" onchange="document.getElementById('music-filename-preview').textContent = this.files[0] ? this.files[0].name : 'Maks 10MB (.mp3)';">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── 1. LATAR BELAKANG / WALLPAPER ── --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-sm font-bold text-gray-800">Latar Belakang (Wallpaper)</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Unggah gambar sebagai latar belakang halaman profilmu.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="wallpaper_enabled" id="wallpaper_enabled" value="1" class="sr-only peer" {{ $wallpaper_enabled ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-red-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <div id="wallpaper-section" class="{{ !$wallpaper_enabled ? 'hidden' : '' }} p-5 space-y-4">
                        {{-- Upload image --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Unggah Gambar Background</label>
                            <label class="flex items-center gap-3 cursor-pointer border-2 border-dashed border-gray-300 hover:border-red-400 rounded-lg px-4 py-3 transition-colors group">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700" id="bg-file-label">Pilih gambar (JPG, PNG, WEBP, maks 3MB)</span>
                                <input type="file" name="bg_image" id="bg-image-input" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden" onchange="previewBg(this)">
                            </label>
                        </div>

                        {{-- Warna latar --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Latar</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="bg_color" value="{{ $bg_color }}" class="w-10 h-9 rounded cursor-pointer border border-gray-200 p-0.5" oninput="syncColor(this, 'preview-wrapper', 'backgroundColor', 'bg_color_hex')">
                                    <input type="text" id="bg_color_hex" value="{{ $bg_color }}" maxlength="7" class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-2 font-mono" oninput="syncHexToColor(this, 'bg_color')" placeholder="#ffffff">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Teks</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="font_color" value="{{ $font_color }}" class="w-10 h-9 rounded cursor-pointer border border-gray-200 p-0.5" oninput="syncFontColor(this, 'font_color_hex')">
                                    <input type="text" id="font_color_hex" value="{{ $font_color }}" maxlength="7" class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-2 font-mono" oninput="syncHexToColor(this, 'font_color')" placeholder="#111827">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Tautan</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="link_color" value="{{ $link_color }}" class="w-10 h-9 rounded cursor-pointer border border-gray-200 p-0.5" oninput="syncLinkColor(this, 'link_color_hex')">
                                    <input type="text" id="link_color_hex" value="{{ $link_color }}" maxlength="7" class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-2 font-mono" oninput="syncHexToColor(this, 'link_color')" placeholder="#b91c1c">
                                </div>
                            </div>
                        </div>

                        {{-- Posisi & Pengulangan --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Posisi</label>
                                <select name="bg_position" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-2 bg-white">
                                    <option value="center" {{ $bg_position === 'center' ? 'selected' : '' }}>Tengah</option>
                                    <option value="left"   {{ $bg_position === 'left'   ? 'selected' : '' }}>Kiri</option>
                                    <option value="right"  {{ $bg_position === 'right'  ? 'selected' : '' }}>Kanan</option>
                                    <option value="top"    {{ $bg_position === 'top'    ? 'selected' : '' }}>Atas</option>
                                    <option value="bottom" {{ $bg_position === 'bottom' ? 'selected' : '' }}>Bawah</option>
                                </select>
                            </div>
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-700">
                                    <input type="checkbox" name="repeat_x" value="1" {{ $repeat_x ? 'checked' : '' }} class="w-4 h-4 text-red-600 rounded border-gray-300">
                                    Ulangi-X
                                </label>
                            </div>
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-700">
                                    <input type="checkbox" name="repeat_y" value="1" {{ $repeat_y ? 'checked' : '' }} class="w-4 h-4 text-red-600 rounded border-gray-300">
                                    Ulangi-Y
                                </label>
                            </div>
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-700">
                                    <input type="checkbox" name="transparent" value="1" {{ $transparent ? 'checked' : '' }} class="w-4 h-4 text-red-600 rounded border-gray-300">
                                    Transparan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── 2. JUDUL BLOK (Block Header) ── --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-sm font-bold text-gray-800">Judul Blok (Block Header)</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Warna khusus untuk header setiap widget/blok di profilmu.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="block_enabled" id="block_enabled" value="1" class="sr-only peer" {{ $block_enabled ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-red-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <div id="block-section" class="{{ !$block_enabled ? 'hidden' : '' }} p-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Latar Blok</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="block_bg" value="{{ $block_bg }}" class="w-10 h-9 rounded cursor-pointer border border-gray-200 p-0.5" oninput="syncBlockBg(this, 'block_bg_hex')">
                                    <input type="text" id="block_bg_hex" value="{{ $block_bg }}" maxlength="7" class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-2 font-mono" oninput="syncHexToColor(this, 'block_bg')" placeholder="#ffffff">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Teks Blok</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="block_text" value="{{ $block_text }}" class="w-10 h-9 rounded cursor-pointer border border-gray-200 p-0.5" oninput="syncBlockText(this, 'block_text_hex')">
                                    <input type="text" id="block_text_hex" value="{{ $block_text }}" maxlength="7" class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-2 font-mono" oninput="syncHexToColor(this, 'block_text')" placeholder="#111827">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── 3. TOMBOL SIMPAN ── --}}
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="text-sm text-gray-500 hover:text-gray-800 transition font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Lihat Profil
                    </a>
                    <button type="submit" id="save-btn"
                        class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-7 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Desain
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Toggle sections (manual klik) ──
document.getElementById('wallpaper_enabled').addEventListener('change', function () {
    document.getElementById('wallpaper-section').classList.toggle('hidden', !this.checked);
});
document.getElementById('musicplayer').addEventListener('change', function () {
    document.getElementById('music-section').classList.toggle('hidden', !this.checked);
});
document.getElementById('block_enabled').addEventListener('change', function () {
    document.getElementById('block-section').classList.toggle('hidden', !this.checked);
});

// ── Auto-aktifkan toggle saat user berinteraksi dengan input di dalam section ──
function autoEnableToggle(toggleId, sectionId) {
    const toggle = document.getElementById(toggleId);
    const section = document.getElementById(sectionId);
    if (toggle && !toggle.checked) {
        toggle.checked = true;
        if (section) section.classList.remove('hidden');
    }
}

// Pasang auto-enable ke semua input di dalam wallpaper-section
document.getElementById('wallpaper-section').querySelectorAll('input, select').forEach(function(el) {
    el.addEventListener('input', function() { autoEnableToggle('wallpaper_enabled', 'wallpaper-section'); });
    el.addEventListener('change', function() { autoEnableToggle('wallpaper_enabled', 'wallpaper-section'); });
});

// Pasang auto-enable ke file input music
document.getElementById('profile_music_input').addEventListener('change', function() {
    autoEnableToggle('musicplayer', 'music-section');
});

// Pasang auto-enable ke semua input di dalam block-section
document.getElementById('block-section').querySelectorAll('input, select').forEach(function(el) {
    el.addEventListener('input', function() { autoEnableToggle('block_enabled', 'block-section'); });
    el.addEventListener('change', function() { autoEnableToggle('block_enabled', 'block-section'); });
});

// ── Preview bg color ──
function syncColor(input, targetId, cssProp, hexId) {
    const el = document.getElementById(targetId);
    if (el) el.style[cssProp] = input.value;
    const hex = document.getElementById(hexId);
    if (hex) hex.value = input.value;
}

// ── Preview font color ──
function syncFontColor(input, hexId) {
    const wrapper = document.getElementById('preview-wrapper');
    if (wrapper) wrapper.style.color = input.value;
    const name = document.getElementById('preview-name');
    if (name) name.style.color = input.value;
    const hex = document.getElementById(hexId);
    if (hex) hex.value = input.value;
}

// ── Preview link color ──
function syncLinkColor(input, hexId) {
    const link = document.getElementById('preview-link');
    if (link) link.style.color = input.value;
    const hex = document.getElementById(hexId);
    if (hex) hex.value = input.value;
}

// ── Preview block bg ──
function syncBlockBg(input, hexId) {
    const block = document.getElementById('preview-block');
    if (block) block.style.backgroundColor = input.value;
    const hex = document.getElementById(hexId);
    if (hex) hex.value = input.value;
}

// ── Preview block text ──
function syncBlockText(input, hexId) {
    const block = document.getElementById('preview-block');
    if (block) block.style.color = input.value;
    const hex = document.getElementById(hexId);
    if (hex) hex.value = input.value;
}

// ── Sync hex text input → color input ──
function syncHexToColor(textInput, colorInputName) {
    const val = textInput.value.trim();
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        const colorInput = document.querySelector(`input[name="${colorInputName}"]`);
        if (colorInput) {
            colorInput.value = val;
            colorInput.dispatchEvent(new Event('input'));
        }
    }
}

// ── Preview uploaded bg image ──
function previewBg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrapper = document.getElementById('preview-wrapper');
            if (wrapper) wrapper.style.backgroundImage = `url('${e.target.result}')`;
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('bg-file-label').textContent = input.files[0].name;
        // Auto-aktifkan toggle wallpaper jika file dipilih
        autoEnableToggle('wallpaper_enabled', 'wallpaper-section');
    }
}
</script>
@endpush
@endsection
