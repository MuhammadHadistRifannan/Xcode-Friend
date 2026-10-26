@extends('layouts.app')
@section('title', $q ? 'Hasil Pencarian: "' . $q . '" — XCODE Friends' : 'Cari — XCODE Friends')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] dark:bg-neutral-900 -mt-10 -mb-10 pb-10 transition-colors">

    {{-- ===== SEARCH HEADER STICKY ===== --}}
    <div class="bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 sticky top-0 z-30 shadow-sm transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            {{-- Search Bar --}}
            <div class="py-4">
                <form method="GET" action="{{ route('search.index') }}" class="flex gap-3">
                    @if($tab !== 'semua')
                        <input type="hidden" name="tab" value="{{ $tab }}">
                    @endif
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-neutral-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="q"
                            value="{{ $q }}"
                            placeholder="Cari anggota, grup, halaman, foto, postingan..."
                            autofocus
                            class="w-full bg-gray-50 dark:bg-neutral-700 border border-gray-200 dark:border-neutral-600 rounded-xl pl-11 pr-4 py-2.5 text-sm text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#990000]/20 focus:border-[#990000] transition"
                        >
                    </div>
                    <button type="submit" class="bg-[#990000] hover:bg-[#7a0000] text-white font-semibold text-sm px-6 rounded-xl transition shadow-sm">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Filter Tabs --}}
            @if($q && strlen($q) >= 2)
            @php
                $tabDefs = [
                    ['key' => 'semua',     'label' => 'Semua',    'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'],
                    ['key' => 'anggota',   'label' => 'Anggota',  'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'],
                    ['key' => 'grup',      'label' => 'Grup',     'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'],
                    ['key' => 'halaman',   'label' => 'Halaman',  'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'],
                    ['key' => 'postingan', 'label' => 'Postingan','icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>'],
                    ['key' => 'foto',      'label' => 'Foto',     'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'],
                ];
            @endphp
            <div class="flex items-center gap-1 overflow-x-auto pb-0 scrollbar-hide">
                @foreach($tabDefs as $t)
                @php $count = $counts[$t['key']] ?? 0; @endphp
                <a href="{{ route('search.index', ['q' => $q, 'tab' => $t['key']]) }}"
                   class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors
                          {{ $tab === $t['key']
                              ? 'border-[#990000] text-[#990000] dark:text-red-400 dark:border-red-500'
                              : 'border-transparent text-gray-500 dark:text-neutral-400 hover:text-gray-800 dark:hover:text-neutral-200 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                    <span class="text-gray-500 dark:text-neutral-400 mr-1.5">{!! $t['icon'] !!}</span>
                    {{ $t['label'] }}
                    @if($count > 0)
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full
                                 {{ $tab === $t['key'] ? 'bg-[#990000] text-white' : 'bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-300' }}">
                        {{ $count > 999 ? '999+' : $count }}
                    </span>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ===== CONTENT AREA ===== --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

        @if(strlen($q) < 2 && $q !== '')
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700/50 text-yellow-700 dark:text-yellow-300 text-sm rounded-xl px-4 py-3">
                Kata kunci terlalu pendek. Masukkan minimal 2 karakter.
            </div>

        @elseif(!$q)
            {{-- Empty state --}}
            <div class="text-center py-24">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-neutral-600 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h2 class="text-lg font-bold text-gray-700 dark:text-neutral-200 mb-2">Cari apapun di XCODE</h2>
                <p class="text-sm text-gray-400 dark:text-neutral-400">Temukan anggota, grup, halaman, postingan, dan foto.</p>
                <div class="flex flex-wrap justify-center gap-2 mt-6">
                    @foreach(['Anggota', 'Grup', 'Halaman', 'Foto', 'Postingan'] as $hint)
                    <span class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-gray-500 dark:text-neutral-400 text-xs px-3 py-1.5 rounded-full">{{ $hint }}</span>
                    @endforeach
                </div>
            </div>

        @elseif(array_sum(array_filter($counts, fn($v,$k) => $k !== 'semua', ARRAY_FILTER_USE_BOTH)) === 0)
            {{-- No results overall --}}
            <div class="text-center py-24">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-neutral-600 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h2 class="text-lg font-bold text-gray-700 dark:text-neutral-200 mb-2">Tidak ada hasil untuk "{{ $q }}"</h2>
                <p class="text-sm text-gray-400 dark:text-neutral-400">Coba periksa ejaan atau gunakan kata kunci berbeda.</p>
            </div>

        @elseif($tab !== 'semua' && ($counts[$tab] ?? 0) === 0)
            {{-- No results for selected tab --}}
            <div class="text-center py-24">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-neutral-600 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h2 class="text-lg font-bold text-gray-700 dark:text-neutral-200 mb-2">Tidak ada hasil kategori {{ ucfirst($tab) }} untuk "{{ $q }}"</h2>
                <p class="text-sm text-gray-400 dark:text-neutral-400">Coba pilih tab "Semua" atau gunakan kata kunci lain.</p>
                <div class="mt-6">
                    <a href="{{ route('search.index', ['q' => $q, 'tab' => 'semua']) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#990000] hover:bg-[#7a0000] rounded-xl transition shadow-sm">
                        Lihat semua kategori
                    </a>
                </div>
            </div>

        @else

            <div class="space-y-10">

                {{-- ===== ANGGOTA ===== --}}
                @if($users->count() > 0)
                <section id="section-anggota">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 dark:text-neutral-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-neutral-100 uppercase tracking-widest">Anggota</h2>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $counts['anggota'] }})</span>
                        </div>
                        @if($tab === 'semua' && $counts['anggota'] > $users->count())
                        <a href="{{ route('search.index', ['q' => $q, 'tab' => 'anggota']) }}"
                           class="text-xs font-semibold text-[#990000] dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                            Lihat semua {{ $counts['anggota'] }} anggota →
                        </a>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($users as $user)
                        <a href="{{ route('profile.show', $user->username) }}"
                           class="bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-4 flex items-center gap-3 hover:border-[#990000]/30 hover:shadow-md transition group">
                            <img src="{{ $user->avatar_url }}"
                                 alt="{{ $user->username }}"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 dark:border-neutral-700 flex-shrink-0 group-hover:border-[#990000]/20 transition">
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-neutral-100 truncate group-hover:text-[#990000] dark:group-hover:text-red-400 transition">
                                    {{ $user->fullname ?: $user->username }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-neutral-400 truncate">{{ '@'.$user->username }}</div>
                                @if($user->country)
                                <div class="text-[10px] text-gray-400 dark:text-neutral-500 truncate mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $user->country }}
                                </div>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- ===== GRUP ===== --}}
                @if($groups->count() > 0)
                <section id="section-grup">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-blue-500 dark:text-blue-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-neutral-100 uppercase tracking-widest">Grup</h2>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $counts['grup'] }})</span>
                        </div>
                        @if($tab === 'semua' && $counts['grup'] > $groups->count())
                        <a href="{{ route('search.index', ['q' => $q, 'tab' => 'grup']) }}"
                           class="text-xs font-semibold text-[#990000] dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                            Lihat semua {{ $counts['grup'] }} grup →
                        </a>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group->id) }}"
                           class="bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-4 flex items-center gap-3 hover:border-blue-200 dark:hover:border-blue-500/50 hover:shadow-md transition group">
                            @if($group->logo_url)
                                <img src="{{ $group->logo_url }}" alt="{{ $group->name }}"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                     class="w-12 h-12 rounded-xl object-cover border border-gray-100 dark:border-neutral-700 flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-bold text-gray-900 dark:text-neutral-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                    {{ $group->name }}
                                </div>
                                @if($group->description)
                                <div class="text-xs text-gray-400 dark:text-neutral-400 line-clamp-1 mt-0.5">{{ $group->description }}</div>
                                @endif
                                <div class="text-[10px] text-gray-400 dark:text-neutral-500 mt-1">
                                    {{ $group->users ?? 0 }} anggota
                                    @if(in_array($group->type, ['private_group']))
                                        <span class="mx-1">·</span>
                                        <svg class="w-3 h-3 text-gray-400 dark:text-neutral-500 inline-block mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Private
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- ===== HALAMAN ===== --}}
                @if($pages->count() > 0)
                <section id="section-halaman">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-purple-500 dark:text-purple-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-neutral-100 uppercase tracking-widest">Halaman</h2>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $counts['halaman'] }})</span>
                        </div>
                        @if($tab === 'semua' && $counts['halaman'] > $pages->count())
                        <a href="{{ route('search.index', ['q' => $q, 'tab' => 'halaman']) }}"
                           class="text-xs font-semibold text-[#990000] dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                            Lihat semua {{ $counts['halaman'] }} halaman →
                        </a>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pages as $page)
                        <a href="{{ route('pages.show', $page->id) }}"
                           class="bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-4 flex items-center gap-3 hover:border-purple-200 dark:hover:border-purple-500/50 hover:shadow-md transition group">
                            @if($page->logo_url)
                                <img src="{{ $page->logo_url }}" alt="{{ $page->name }}"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                     class="w-12 h-12 rounded-xl object-cover border border-gray-100 dark:border-neutral-700 flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-bold text-gray-900 dark:text-neutral-100 truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                    {{ $page->name }}
                                </div>
                                @if($page->description)
                                <div class="text-xs text-gray-400 dark:text-neutral-400 line-clamp-1 mt-0.5">{{ $page->description }}</div>
                                @endif
                                <div class="text-[10px] text-gray-400 dark:text-neutral-500 mt-1">{{ $page->users ?? 0 }} pengikut</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- ===== FOTO ===== --}}
                @if($photos->count() > 0)
                <section id="section-foto">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-green-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-neutral-100 uppercase tracking-widest">Foto</h2>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $counts['foto'] }})</span>
                        </div>
                        @if($tab === 'semua' && $counts['foto'] > $photos->count())
                        <a href="{{ route('search.index', ['q' => $q, 'tab' => 'foto']) }}"
                           class="text-xs font-semibold text-[#990000] dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                            Lihat semua {{ $counts['foto'] }} foto →
                        </a>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        @foreach($photos as $photo)
                        @php $photoUrl = asset('storage/' . $photo->uri); @endphp
                        <a href="{{ $photoUrl }}" target="_blank"
                           class="aspect-square overflow-hidden rounded-xl border border-gray-100 dark:border-neutral-700 hover:border-gray-300 dark:hover:border-neutral-500 hover:shadow-md transition group block relative">
                            <img src="{{ $photo->thumb ? asset('storage/'.$photo->thumb) : $photoUrl }}"
                                 alt="{{ $photo->des }}"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @if($photo->des)
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-end opacity-0 group-hover:opacity-100">
                                <p class="text-white text-[10px] px-2 py-1.5 line-clamp-2">{{ $photo->des }}</p>
                            </div>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- ===== POSTINGAN ===== --}}
                @if($streams->count() > 0)
                <section id="section-postingan">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-neutral-100 uppercase tracking-widest">Postingan</h2>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $counts['postingan'] }})</span>
                        </div>
                        @if($tab === 'semua' && $counts['postingan'] > $streams->count())
                        <a href="{{ route('search.index', ['q' => $q, 'tab' => 'postingan']) }}"
                           class="text-xs font-semibold text-[#990000] dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                            Lihat semua {{ $counts['postingan'] }} postingan →
                        </a>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @foreach($streams as $stream)
                        @php
                            $targetGroup = ($stream->app === 'group') ? ($stream->targetPage ?? $stream->targetGroup) : null;
                            $targetPageObj = ($stream->app === 'page') ? $stream->targetPage : null;
                            $postUrl = '#';
                            if ($targetGroup) {
                                $postUrl = url('/groups/' . $targetGroup->id . '#stream-card-' . $stream->id);
                            } elseif ($targetPageObj) {
                                $postUrl = url('/pages/' . $targetPageObj->id . '#stream-card-' . $stream->id);
                            } elseif ($stream->user) {
                                $postUrl = url('/@' . $stream->user->username . '#stream-card-' . $stream->id);
                            }
                        @endphp
                        <div class="bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl px-5 py-4 flex gap-4 hover:shadow-sm transition">
                            <a href="{{ route('profile.show', optional($stream->user)->username ?? '#') }}" class="flex-shrink-0">
                                <img src="{{ optional($stream->user)->avatar_url ?? asset('assets/img/default.png') }}"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                     class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-neutral-700">
                            </a>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <a href="{{ route('profile.show', optional($stream->user)->username ?? '#') }}"
                                       class="text-sm font-bold text-gray-900 dark:text-neutral-100 hover:text-[#990000] dark:hover:text-red-400 transition">
                                        {{ optional($stream->user)->fullname ?: optional($stream->user)->username ?? 'Pengguna' }}
                                    </a>

                                    @if($targetGroup)
                                        <span class="text-gray-300 dark:text-neutral-600">·</span>
                                        <span class="text-xs text-gray-500 dark:text-neutral-400 flex items-center gap-1">
                                            di Grup
                                            <a href="{{ url('/groups/' . $targetGroup->id) }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                {{ $targetGroup->name }}
                                            </a>
                                        </span>
                                    @elseif($targetPageObj)
                                        <span class="text-gray-300 dark:text-neutral-600">·</span>
                                        <span class="text-xs text-gray-500 dark:text-neutral-400 flex items-center gap-1">
                                            di Halaman
                                            <a href="{{ url('/pages/' . $targetPageObj->id) }}" class="font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 hover:underline">
                                                {{ $targetPageObj->name }}
                                            </a>
                                        </span>
                                    @endif

                                    <span class="text-gray-300 dark:text-neutral-600">·</span>
                                    <span class="text-xs text-gray-400 dark:text-neutral-500 flex items-center gap-1">
                                        {{ date('d M Y', $stream->created) }}
                                        @if($stream->privacy === 'private')
                                        <svg class="w-3 h-3" title="Hanya Saya" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @elseif($stream->privacy === 'friends')
                                        <svg class="w-3 h-3" title="Teman" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        @else
                                        <svg class="w-3 h-3" title="Publik" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </span>
                                </div>
                                {{-- Highlight keyword & clickable post content --}}
                                @php
                                    $highlighted = preg_replace(
                                        '/(' . preg_quote($q, '/') . ')/i',
                                        '<mark class="bg-yellow-100 dark:bg-yellow-900/60 text-yellow-800 dark:text-yellow-200 rounded px-0.5">$1</mark>',
                                        e($stream->message)
                                    );
                                @endphp
                                <a href="{{ $postUrl }}" class="block group/msg">
                                    <p class="text-sm text-gray-700 dark:text-neutral-300 line-clamp-4 group-hover/msg:text-gray-900 dark:group-hover/msg:text-neutral-100 leading-relaxed">{!! $highlighted !!}</p>
                                </a>

                                @if($targetGroup)
                                    <div class="mt-2.5 flex items-center gap-2">
                                        <a href="{{ $postUrl }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 px-2.5 py-1 rounded-lg transition">
                                            <span>Lihat di grup {{ $targetGroup->name }}</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>
        @endif
    </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection
