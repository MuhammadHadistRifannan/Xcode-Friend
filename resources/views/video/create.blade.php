@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6">

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
            <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">MY APPS &gt; VIDEO &gt; TAMBAH VIDEO</div>
            <h1 class="text-2xl font-black text-gray-900 uppercase">Tambah Video</h1>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">

        {{-- ===== MAIN FORM (col-span-9) ===== --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                {{-- ===== PESAN ERROR ===== --}}
                @if ($errors->any())
                    <div class="mx-8 mt-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-sm font-bold text-red-700 mb-2">Terdapat kesalahan:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ===== ALPINE WRAPPER ===== --}}
                <div x-data="{
                    step: 1,
                    albumSelection: '{{ old('album_id', '') }}',
                    youtubeUrl: '{{ old('youtube_url', '') }}',
                    previewId: null,
                    get embedUrl() {
                        if (!this.youtubeUrl) return null;
                        let m = this.youtubeUrl.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                        if (!m) m = this.youtubeUrl.match(/[?&v=|\/embed\/]([a-zA-Z0-9_-]{11})/);
                        if (m) return 'https://www.youtube.com/embed/' + m[1];
                        return null;
                    },
                    get thumbnailUrl() {
                        let url = this.youtubeUrl;
                        let m = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                        if (!m) m = url.match(/(?:v=|\/embed\/)([a-zA-Z0-9_-]{11})/);
                        return m ? 'https://img.youtube.com/vi/' + m[1] + '/mqdefault.jpg' : null;
                    }
                }">
                    <form action="{{ route('videos.store') }}" method="POST">
                        @csrf

                        {{-- ===== STEP INDICATOR ===== --}}
                        <div class="px-8 pt-8 pb-4 flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black"
                                     :class="step >= 1 ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-500'">1</div>
                                <span class="text-xs font-bold" :class="step >= 1 ? 'text-red-700' : 'text-gray-400'">Info Video</span>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-200 rounded mx-1">
                                <div class="h-full bg-red-700 rounded transition-all duration-300" :style="step >= 2 ? 'width:100%' : 'width:0%'"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black"
                                     :class="step >= 2 ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-500'">2</div>
                                <span class="text-xs font-bold" :class="step >= 2 ? 'text-red-700' : 'text-gray-400'">Playlist / Album</span>
                            </div>
                        </div>

                        <div class="px-8 pb-8 space-y-6">

                            {{-- ═══════════════════════════════════════════════════ --}}
                            {{-- STEP 1: Info Video                                 --}}
                            {{-- ═══════════════════════════════════════════════════ --}}
                            <div x-show="step === 1" class="space-y-5">

                                {{-- Judul --}}
                                <div>
                                    <label for="video-title" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                        JUDUL VIDEO <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="video-title"
                                        name="title"
                                        value="{{ old('title') }}"
                                        placeholder="Masukkan judul video..."
                                        class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                        required
                                    >
                                </div>

                                {{-- URL YouTube --}}
                                <div>
                                    <label for="video-url" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                        URL YOUTUBE <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        <input
                                            type="url"
                                            id="video-url"
                                            name="youtube_url"
                                            x-model="youtubeUrl"
                                            value="{{ old('youtube_url') }}"
                                            placeholder="https://www.youtube.com/watch?v=..."
                                            class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg pl-10 pr-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                            required
                                        >
                                    </div>
                                    {{-- Preview Thumbnail --}}
                                    <template x-if="thumbnailUrl">
                                        <div class="mt-3 flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <img :src="thumbnailUrl" class="w-28 aspect-video object-cover rounded-md border border-gray-200" onerror="this.parentElement.remove()">
                                            <div class="text-xs text-gray-500">
                                                <p class="font-semibold text-gray-700 mb-0.5">Pratinjau Thumbnail</p>
                                                <p>Thumbnail YouTube berhasil dideteksi.</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label for="video-description" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                        DESKRIPSI VIDEO
                                    </label>
                                    <textarea
                                        id="video-description"
                                        name="description"
                                        rows="4"
                                        placeholder="Ceritakan isi video ini..."
                                        class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors resize-none"
                                    >{{ old('description') }}</textarea>
                                </div>

                                {{-- Tags --}}
                                <div>
                                    <label for="video-tags" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                        TAGS
                                        <span class="ml-1 text-[10px] text-gray-400 normal-case font-normal tracking-normal">pisahkan dengan koma</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="video-tags"
                                        name="tags"
                                        value="{{ old('tags') }}"
                                        placeholder="contoh: cybersecurity, tutorial, network"
                                        class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                    >
                                </div>

                                {{-- Privacy --}}
                                <div>
                                    <label for="video-privacy" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                        PRIVASI
                                    </label>
                                    <div class="relative max-w-xs">
                                        <select
                                            id="video-privacy"
                                            name="privacy"
                                            class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg pl-4 pr-10 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white appearance-none transition-colors"
                                        >
                                            <option value="everyone" {{ old('privacy') == 'everyone' ? 'selected' : '' }}>🌐 Semua Orang</option>
                                            <option value="friends"  {{ old('privacy') == 'friends'  ? 'selected' : '' }}>👥 Teman Saja</option>
                                            <option value="only_me" {{ old('privacy') == 'only_me'  ? 'selected' : '' }}>🔒 Hanya Saya</option>
                                        </select>
                                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Footer Step 1 --}}
                                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                                    <a href="{{ route('video.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition-colors">
                                        Batal
                                    </a>
                                    <button
                                        type="button"
                                        @click="step = 2"
                                        class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-sm transition-colors"
                                    >
                                        Selanjutnya
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- ═══════════════════════════════════════════════════ --}}
                            {{-- STEP 2: Pilih / Buat Playlist                      --}}
                            {{-- ═══════════════════════════════════════════════════ --}}
                            <div x-show="step === 2" style="display: none;" class="space-y-6">

                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight border-b border-gray-100 pb-4">
                                    Tambahkan ke Playlist / Album
                                </h3>

                                <div class="space-y-5 max-w-lg">

                                    {{-- Pilih Playlist --}}
                                    <div>
                                        <label for="album-select" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                            PILIH PLAYLIST
                                        </label>
                                        <div class="relative">
                                            <select
                                                id="album-select"
                                                name="album_id"
                                                x-model="albumSelection"
                                                class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white appearance-none transition-colors"
                                            >
                                                <option value="">— Tanpa Playlist —</option>
                                                <option value="new" class="font-bold text-red-600">+ Buat Playlist Baru</option>
                                                @foreach ($albums as $album)
                                                    <option value="{{ $album->id }}"
                                                        {{ old('album_id') == $album->id ? 'selected' : '' }}>
                                                        {{ $album->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Input Nama Playlist Baru --}}
                                    <div x-show="albumSelection === 'new'" style="display: none;"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-5 bg-red-50 border border-red-200 rounded-xl">
                                        <label for="new-album-name" class="block text-xs font-bold text-red-800 uppercase tracking-wider mb-2">
                                            NAMA PLAYLIST BARU <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="new-album-name"
                                            name="new_album_name"
                                            value="{{ old('new_album_name') }}"
                                            placeholder="Masukkan nama playlist baru..."
                                            class="w-full border border-red-300 focus:border-red-600 focus:ring-1 focus:ring-red-600 rounded-lg px-4 py-3 text-sm text-gray-900 bg-white transition-colors"
                                            :required="albumSelection === 'new'"
                                        >
                                    </div>

                                </div>

                                {{-- Footer Step 2 --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <button
                                        type="button"
                                        @click="step = 1"
                                        class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-gray-900 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Kembali
                                    </button>
                                    <button
                                        type="submit"
                                        class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-sm transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Publikasikan Video
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== SIDEBAR KANAN (col-span-3) ===== --}}
        <div class="col-span-12 lg:col-span-3 space-y-5">

            {{-- Panduan Singkat --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-wider mb-4">Panduan</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[10px] font-black text-red-600">1</span>
                        </div>
                        <p class="text-xs text-gray-600">Isi judul dan URL YouTube video.</p>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[10px] font-black text-red-600">2</span>
                        </div>
                        <p class="text-xs text-gray-600">Pilih playlist yang ada, buat baru, atau lewati.</p>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[10px] font-black text-red-600">3</span>
                        </div>
                        <p class="text-xs text-gray-600">Tekan <strong>Publikasikan</strong> untuk menyimpan.</p>
                    </li>
                </ul>
            </div>

            {{-- Tips --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <h3 class="text-xs font-black text-amber-800 uppercase tracking-wider mb-2">💡 Tips</h3>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Gunakan URL YouTube yang valid, misalnya:<br>
                    <code class="text-[10px] bg-amber-100 px-1 py-0.5 rounded font-mono">https://www.youtube.com/watch?v=xxxxx</code><br>
                    atau format pendek:<br>
                    <code class="text-[10px] bg-amber-100 px-1 py-0.5 rounded font-mono">https://youtu.be/xxxxx</code>
                </p>
            </div>

            @php
                $sidebarData = [
                    'reviews'      => ['rating' => 4.9, 'count' => 532],
                    'networkLinks' => [
                        ['id' => 1, 'label' => 'LinkedIn', 'url' => '#'],
                        ['id' => 2, 'label' => 'phpBB Group', 'url' => '#'],
                        ['id' => 3, 'label' => 'Facebook', 'url' => '#'],
                    ],
                ];
            @endphp
            <x-sidebar-right :data="$sidebarData" />
        </div>

    </div>
</div>
</div>
@endsection
