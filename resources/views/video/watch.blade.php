@extends('layouts.app')

@section('content')
{{-- ===== PAGE WRAPPER ===== --}}
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ===== BREADCRUMB / BACK BUTTON ===== --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('videos.public') }}"
               class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Video
            </a>
            @if ($album)
                <span class="text-gray-400 text-sm">/</span>
                <span class="text-sm font-medium text-gray-500 truncate max-w-xs">{{ $album->name }}</span>
            @endif
        </div>

        {{-- ===== MAIN GRID: 12 KOLOM ===== --}}
        <div class="grid grid-cols-12 gap-6">

            {{-- ─────────────────────────────────────────────────────────── --}}
            {{-- KOLOM KIRI — Video Player (col-span-12 di mobile, 8 di lg) --}}
            {{-- ─────────────────────────────────────────────────────────── --}}
            <div class="col-span-12 lg:col-span-8">

                {{-- ===== VIDEO PLAYER CONTAINER ===== --}}
                <div class="w-full aspect-video bg-black rounded-xl overflow-hidden shadow-lg border border-gray-800 relative">

                    @if ($video->youtube_embed_url)
                        {{-- ── Real YouTube Embed (jika video memiliki URL YouTube) ── --}}
                        <iframe
                            src="{{ $video->youtube_embed_url }}"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            title="{{ $video->title }}"
                        ></iframe>
                    @else
                        {{-- ── Fallback: Simulasi Terkunci (jika belum ada URL YouTube) ── --}}
                        <div class="w-full h-full flex flex-col items-center justify-center gap-4 bg-black">
                            {{-- Ikon Gembok --}}
                            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-2">
                                <svg class="w-8 h-8 text-white/60" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <h2 class="text-white font-bold text-xl tracking-tight">Video terkunci</h2>
                            <p class="text-white/50 text-sm text-center max-w-xs">
                                Masuk ke akun Anda untuk mengakses video ini secara gratis.
                            </p>
                            <button class="mt-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold px-6 py-2.5 rounded-md transition-colors shadow-md">
                                Login untuk menonton
                            </button>
                        </div>
                    @endif
                </div>

                {{-- ===== INFO VIDEO (di bawah player) ===== --}}
                <div class="mt-6">
                    {{-- Judul --}}
                    <h1 class="text-2xl font-bold text-gray-900 leading-snug">
                        {{ $video->title }}
                    </h1>

                    {{-- Meta info: tanggal & jumlah views --}}
                    <div class="flex items-center gap-4 mt-2 flex-wrap">
                        @if ($video->created)
                            <span class="flex items-center gap-1.5 text-sm text-gray-500">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $video->formatted_date }}
                            </span>
                        @endif
                        @if ($video->views)
                            <span class="flex items-center gap-1.5 text-sm text-gray-500">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($video->views) }} penonton
                            </span>
                        @endif
                        @if ($album)
                            <a href="{{ route('video.index') }}"
                               class="flex items-center gap-1.5 text-sm text-red-600 hover:text-red-700 font-medium">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                {{ $album->name }}
                            </a>
                        @endif
                    </div>

                    {{-- Deskripsi Video --}}
                    @if ($video->content)
                        <div class="mt-5 bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                            <h3 class="text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Deskripsi</h3>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $video->content }}</p>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if ($video->tags)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach (explode(',', $video->tags) as $tag)
                                @php $tag = trim($tag); @endphp
                                @if ($tag)
                                    <span class="text-xs font-medium bg-gray-100 text-gray-600 px-3 py-1 rounded-full border border-gray-200">
                                        #{{ $tag }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>{{-- /KOLOM KIRI --}}

            {{-- ─────────────────────────────────────────────────────────── --}}
            {{-- KOLOM KANAN — Sidebar Playlist Album (col-span-12 / lg:4)  --}}
            {{-- ─────────────────────────────────────────────────────────── --}}
            <div class="col-span-12 lg:col-span-4">

                @if ($album)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col"
                     style="max-height: 85vh;"
                     x-data="{ search: '' }">

                    {{-- ── Header Sidebar (sticky) ── --}}
                    <div class="sticky top-0 z-10 bg-white rounded-t-xl border-b border-gray-100 px-4 pt-4 pb-3">

                        {{-- Nama Album --}}
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">PLAYLIST ALBUM</p>
                                <h2 class="font-bold text-gray-900 text-sm leading-tight mt-0.5">{{ $album->name }}</h2>
                                @if ($album->description)
                                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $album->description }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-full">
                                {{ $albumVideos->count() }} video
                            </span>
                        </div>

                        {{-- Search Bar --}}
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                x-model="search"
                                placeholder="Cari episode..."
                                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent bg-gray-50 focus:bg-white transition-colors"
                            >
                        </div>
                    </div>

                    {{-- ── Daftar Video (scrollable) ── --}}
                    <div class="overflow-y-auto custom-scrollbar flex flex-col gap-2.5 p-3">

                        @forelse ($albumVideos as $ep)
                            @php
                                $epNumber  = $loop->index + 1;
                                $isActive  = ($ep->id === $video->id);
                                $epThumb   = $ep->youtube_thumbnail ?? 'https://img.youtube.com/vi/default/mqdefault.jpg';
                                $epDate    = $ep->formatted_date;
                                $epTitle   = $ep->title;
                                $epDesc    = $ep->content ? \Illuminate\Support\Str::limit($ep->content, 80) : '';
                            @endphp

                            <div
                                x-show="search === '' || '{{ strtolower($epTitle) }}'.includes(search.toLowerCase())"
                                x-cloak
                            >
                                @if ($isActive)
                                    {{-- ════ ACTIVE CARD (video yang sedang diputar) ════ --}}
                                    <div class="flex gap-3 p-2 rounded-xl border-2 border-red-500 bg-red-50/60 cursor-default relative overflow-hidden">

                                        {{-- Thumbnail --}}
                                        <div class="relative w-28 shrink-0 aspect-video rounded-lg overflow-hidden ring-2 ring-red-400 ring-offset-1">
                                            <img src="{{ $epThumb }}"
                                                 alt="{{ $epTitle }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.src='https://placehold.co/320x180/111827/ffffff?text=Video'">

                                            {{-- Badge Part (abu-abu) --}}
                                            <div class="absolute top-1 left-1 bg-gray-800/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                                Part {{ $epNumber }}
                                            </div>

                                            {{-- Overlay + ikon Play merah transparan --}}
                                            <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                                                <div class="w-10 h-10 rounded-full bg-red-600/80 flex items-center justify-center shadow-lg">
                                                    <svg class="w-5 h-5 text-white fill-current ml-0.5" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info Kanan --}}
                                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                            <div>
                                                <h4 class="font-bold text-xs text-red-600 leading-snug line-clamp-2">
                                                    {{ $epTitle }}
                                                </h4>
                                                @if ($epDesc)
                                                    <p class="text-[10px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                                        {{ $epDesc }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between mt-1.5">
                                                <span class="text-[10px] text-gray-400 font-medium">{{ $epDate }}</span>
                                                {{-- "Now Playing" badge di pojok kanan bawah --}}
                                                <span class="text-[10px] font-bold text-red-600 flex items-center gap-0.5">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                    Now Playing
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    {{-- ════ INACTIVE CARD (video lain di album) ════ --}}
                                    <a href="{{ route('videos.watch', $ep->id) }}"
                                       class="flex gap-3 p-2 rounded-xl border border-gray-200 bg-white hover:border-red-300 hover:bg-red-50/30 hover:shadow-sm transition-all duration-150 cursor-pointer group">

                                        {{-- Thumbnail --}}
                                        <div class="relative w-28 shrink-0 aspect-video rounded-lg overflow-hidden bg-gray-100">
                                            <img src="{{ $epThumb }}"
                                                 alt="{{ $epTitle }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                 onerror="this.src='https://placehold.co/320x180/111827/ffffff?text=Video'">

                                            {{-- Badge Part (abu-abu gelap) --}}
                                            <div class="absolute top-1 left-1 bg-gray-900/75 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                                Part {{ $epNumber }}
                                            </div>

                                            {{-- Hover overlay + ikon Play --}}
                                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <div class="w-9 h-9 rounded-full bg-white/80 flex items-center justify-center shadow">
                                                    <svg class="w-5 h-5 text-red-600 fill-current ml-0.5" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info Kanan --}}
                                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                            <div>
                                                <h4 class="font-semibold text-xs text-gray-800 group-hover:text-red-600 transition-colors leading-snug line-clamp-2">
                                                    {{ $epTitle }}
                                                </h4>
                                                @if ($epDesc)
                                                    <p class="text-[10px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                                        {{ $epDesc }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-gray-400 font-medium mt-1.5 block">{{ $epDate }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>

                        @empty
                            {{-- Kosong --}}
                            <div class="text-center py-10 px-4">
                                <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-full mb-3">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">Tidak ada video lain</p>
                                <p class="text-xs text-gray-400 mt-1">Album ini hanya berisi video ini.</p>
                            </div>
                        @endforelse

                    </div>

                </div>
                @else
                    {{-- Sidebar fallback jika video tidak punya album --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-full mb-3">
                            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Video Mandiri</p>
                        <p class="text-xs text-gray-400 mt-1">Video ini tidak terdaftar dalam album manapun.</p>
                    </div>
                @endif

            </div>{{-- /KOLOM KANAN --}}

        </div>{{-- /GRID --}}

    </div>
</div>

<style>
/* Custom scrollbar untuk sidebar playlist */
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
/* x-cloak: sembunyikan elemen Alpine sebelum init */
[x-cloak] { display: none !important; }
</style>
@endsection
