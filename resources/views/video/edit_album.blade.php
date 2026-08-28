@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('video.index') }}"
               class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">VIDEO &gt; EDIT ALBUM</div>
                <h1 class="text-2xl font-black text-gray-900 uppercase">Edit Album</h1>
            </div>
        </div>

        {{-- ===== PESAN ERROR ===== --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h4 class="text-sm font-bold text-red-700 mb-2">Terdapat kesalahan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== PESAN SUKSES ===== --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ===== FORM EDIT ALBUM ===== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
             x-data="{
                 selectedVideoId: '{{ old('cover_video_id', $coverVideo?->id ?? '') }}',
                 selectedVideoThumb: '{{ $coverVideo?->youtube_thumbnail ?? '' }}'
             }"
        >
            {{-- ID dibutuhkan agar tombol Simpan di footer bisa di-associate --}}
            <form id="album-update-form" action="{{ route('video.album.update', $album->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-7">

                    {{-- ══════════════════════════════════════════════ --}}
                    {{-- BAGIAN 1: Sampul + Nama + Deskripsi            --}}
                    {{-- ══════════════════════════════════════════════ --}}
                    <div class="flex items-start gap-6">

                        {{-- Pratinjau Sampul Aktif --}}
                        <div class="shrink-0">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                SAMPUL AKTIF
                            </label>
                            <div class="w-44 rounded-xl overflow-hidden bg-gray-100 border-2 border-red-500 shadow-md aspect-video">
                                <template x-if="selectedVideoThumb">
                                    <img :src="selectedVideoThumb" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedVideoThumb">
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-8 h-8 text-gray-300 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[10px] text-gray-400 font-medium">No Thumbnail</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Nama + Deskripsi --}}
                        <div class="flex-1 space-y-4">
                            {{-- Nama Album --}}
                            <div>
                                <label for="album-name" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                    NAMA ALBUM <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="album-name"
                                    name="name"
                                    value="{{ old('name', $album->name) }}"
                                    placeholder="Masukkan nama album..."
                                    class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                    required
                                >
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi Album --}}
                            <div>
                                <label for="album-description" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                    DESKRIPSI ALBUM
                                </label>
                                <textarea
                                    id="album-description"
                                    name="description"
                                    rows="3"
                                    placeholder="Tuliskan deskripsi singkat album ini..."
                                    class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors resize-none"
                                >{{ old('description', $album->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    {{-- ══════════════════════════════════════════════ --}}
                    {{-- BAGIAN 2: Pilih Sampul + Kelola Video          --}}
                    {{-- ══════════════════════════════════════════════ --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider">
                                    PILIH SAMPUL &amp; KELOLA VIDEO
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Klik thumbnail untuk memilih sampul album. Arahkan mouse ke kartu untuk opsi hapus video.
                                </p>
                            </div>
                            <span x-show="selectedVideoId"
                                  style="display: none;"
                                  class="text-[10px] font-bold bg-red-100 text-red-600 px-2.5 py-1 rounded-full">
                                ✓ Sampul dipilih
                            </span>
                        </div>

                        {{-- Input hidden: ID sampul terpilih --}}
                        <input type="hidden" name="cover_video_id" :value="selectedVideoId">

                        @if ($videos->isEmpty())
                            {{-- State kosong --}}
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-10 text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500 font-medium">Album ini belum memiliki video.</p>
                                <a href="{{ route('videos.create') }}"
                                   class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Video
                                </a>
                            </div>
                        @else
                            {{-- Grid Video Picker --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach ($videos as $vid)
                                    @php
                                        $thumb = $vid->youtube_thumbnail ?? '';
                                        $vidDate = $vid->formatted_date;
                                    @endphp

                                    {{-- Card video --}}
                                    <div class="relative group rounded-xl overflow-hidden border-2 transition-all duration-150 bg-white shadow-sm"
                                         :class="selectedVideoId == '{{ $vid->id }}'
                                             ? 'border-red-500 shadow-md scale-[0.98]'
                                             : 'border-gray-200 hover:border-gray-300'">

                                        {{-- ── Thumbnail (klik = pilih sebagai sampul) ── --}}
                                        <button
                                            type="button"
                                            @click="
                                                selectedVideoId = '{{ $vid->id }}';
                                                selectedVideoThumb = '{{ $thumb }}';
                                            "
                                            class="block w-full aspect-video overflow-hidden bg-gray-100 relative focus:outline-none"
                                            title="Pilih sebagai sampul"
                                        >
                                            @if ($thumb)
                                                <img
                                                    src="{{ $thumb }}"
                                                    alt="{{ $vid->title }}"
                                                    class="w-full h-full object-cover"
                                                    onerror="this.src='https://placehold.co/320x180/111827/ffffff?text=No+Thumb'"
                                                >
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                    <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                              d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            {{-- Overlay centang saat terpilih --}}
                                            <div x-show="selectedVideoId == '{{ $vid->id }}'"
                                                 style="display: none;"
                                                 class="absolute inset-0 bg-red-600/20 flex items-center justify-center pointer-events-none">
                                                <div class="bg-red-600 text-white rounded-full p-1.5 shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Hover overlay: "Jadikan Sampul" --}}
                                            <div x-show="selectedVideoId != '{{ $vid->id }}'"
                                                 class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                <span class="text-white text-[10px] font-bold bg-black/50 px-2 py-1 rounded-full">Jadikan Sampul</span>
                                            </div>
                                        </button>

                                        {{-- ── Info: Judul + Tanggal ── --}}
                                        <div class="px-2.5 pt-2 pb-1.5">
                                            <p class="text-xs font-semibold text-gray-800 leading-snug line-clamp-1"
                                               :class="selectedVideoId == '{{ $vid->id }}' ? 'text-red-600' : ''">
                                                {{ $vid->title }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $vidDate }}</p>
                                        </div>

                                        {{-- ── Baris Aksi Bawah ── --}}
                                        <div class="flex items-center justify-between px-2.5 pb-2.5 gap-1.5">
                                            {{-- Tonton Video --}}
                                            <a href="{{ route('videos.watch', $vid->id) }}"
                                               target="_blank"
                                               class="flex items-center gap-1 text-[10px] font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Tonton
                                            </a>

                                            {{--
                                                Hapus Video: tombol ini TIDAK menggunakan nested form.
                                                Form DELETE yang sesungguhnya dirender di LUAR form utama
                                                (lihat blok setelah </form> utama di bawah).
                                                Atribut HTML5 form="delete-video-{id}" menghubungkan
                                                button ini ke form yang benar meskipun secara visual
                                                berada di dalam card.
                                            --}}
                                            <button
                                                type="submit"
                                                form="delete-video-{{ $vid->id }}"
                                                onclick="return confirm('Yakin hapus video \'{{ addslashes(Str::limit($vid->title, 40)) }}\'?')"
                                                class="flex items-center gap-1 text-[10px] font-semibold text-red-500 hover:text-red-700 transition-colors"
                                                title="Hapus video ini dari album"
                                            >
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            {{-- ===== PAGINATION PICKER ===== --}}
                            @if ($videos->lastPage() > 1)
                                <div class="mt-5 flex items-center justify-center gap-1">
                                    {{-- Prev --}}
                                    @if ($videos->onFirstPage())
                                        <span class="px-3 py-1.5 rounded-lg text-xs text-gray-300 bg-white border border-gray-200 cursor-not-allowed">&laquo;</span>
                                    @else
                                        <a href="{{ $videos->previousPageUrl() }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">&laquo;</a>
                                    @endif

                                    {{-- Nomor halaman --}}
                                    @foreach ($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                                        @if ($page == $videos->currentPage())
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-red-600 border border-red-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}"
                                               class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($videos->hasMorePages())
                                        <a href="{{ $videos->nextPageUrl() }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">&raquo;</a>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs text-gray-300 bg-white border border-gray-200 cursor-not-allowed">&raquo;</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-400 text-center mt-2">
                                    Navigasi halaman untuk melihat semua video di album.
                                </p>
                            @endif

                        @endif
                    </div>

                </div>

                {{-- ===== FOOTER FORM ===== --}}
                <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <a href="{{ route('video.index') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        Batal
                    </a>
                    {{-- form="album-update-form" memastikan tombol ini selalu submit form utama,
                         bahkan ketika secara DOM tombol ini berada di luar tag <form>. --}}
                    <button
                        type="submit"
                        form="album-update-form"
                        class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-sm transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

        {{-- ================================================================
             FORM-FORM DELETE VIDEO — dirender DI LUAR form utama agar tidak
             bersarang (nested forms tidak valid di HTML).
             Tombol "Hapus" di dalam card di atas dihubungkan via atribut
             HTML5  form="delete-video-{id}".
             ================================================================ --}}
        @if ($videos->isNotEmpty())
            @foreach ($videos as $vid)
                <form
                    id="delete-video-{{ $vid->id }}"
                    action="{{ route('video.video.destroy', $vid->id) }}"
                    method="POST"
                    style="display: none;"
                >
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif

    </div>
</div>
@endsection
