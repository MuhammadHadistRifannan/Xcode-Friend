<nav class="bg-[#0A0A0A] text-white border-b border-neutral-800 relative z-50">
    <div class="max-w-[96%] mx-auto px-4 sm:px-6 h-14 flex items-center justify-between relative">

        <!-- BAGIAN KIRI: Logo -->
        <div class="flex-shrink-0 flex items-center">
            <a href="/" class="flex items-center space-x-3">
                <img src="{{ asset('assets/img/logo-xcode.png') }}" alt="XCODE Logo" class="h-8 w-auto">
                <span class="font-bold tracking-widest uppercase text-sm hidden lg:block mt-1">XCODE-FRIENDS</span>
            </a>
        </div>

        <!-- BAGIAN TENGAH: Menu Navigasi -->
        <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 items-center space-x-8 text-[13px] font-medium text-neutral-400 mt-1">
            <a href="{{ auth()->check() ? route('beranda') : '/' }}" class="{{ request()->routeIs('beranda') ? 'text-red-600' : 'hover:text-white' }} transition">Beranda</a>
            @if(\App\Helpers\ModuleHelper::isActive('BROWSE'))
            <a href="{{ route('telusur.index') }}" class="{{ request()->routeIs('telusur.*') ? 'text-red-600' : 'hover:text-white' }} transition">Telusur</a>
            @endif
            @if(\App\Helpers\ModuleHelper::isActive('VIDEOS'))
            <a href="{{ route('videos.public') }}" class="{{ request()->routeIs('videos.*') || request()->routeIs('video.*') ? 'text-red-600' : 'hover:text-white' }} transition">Video</a>
            @endif
            @if(\App\Helpers\ModuleHelper::isActive('PAGES'))
            <a href="{{ route('pages.index') }}" class="{{ request()->routeIs('pages.*') ? 'text-red-600' : 'hover:text-white' }} transition">Pages</a>
            @endif

            @auth
            <!-- Dropdown My Apps -->
            <div class="relative group py-4">
                @php
                    $isMyAppActive = request()->is('dasbor') || request()->routeIs('foto.*') || request()->routeIs('video.*') || request()->routeIs('undang.*') || request()->routeIs('desain-profil.*') || request()->routeIs('my-pages.*') || request()->routeIs('groups.*');
                @endphp
                <button class="flex items-center {{ $isMyAppActive ? 'text-red-600' : 'text-neutral-400' }} hover:text-red-500 transition font-semibold cursor-pointer outline-none">
                    My Apps
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Jembatan Hover -->
                <div class="absolute left-1/2 transform -translate-x-1/2 top-[80%] pt-4 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="bg-[#1A1A1A] border border-neutral-800 rounded-lg shadow-2xl p-1.5 flex flex-col gap-1">
                        <a href="{{ auth()->check() ? route('beranda') : '/' }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('dasbor') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->is('dasbor') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span class="text-xs font-semibold">Dasbor</span>
                        </a>
                        @if(\App\Helpers\ModuleHelper::isActive('PHOTOS'))
                        <a href="{{ route('foto.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('foto.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('foto.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs">Foto</span>
                        </a>
                        @endif
                        @if(\App\Helpers\ModuleHelper::isActive('VIDEOS'))
                        <a href="{{ route('video.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('video.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('video.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span class="text-xs">Video</span>
                        </a>
                        @endif
                        <a href="{{ route('undang.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('undang.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('undang.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span class="text-xs">Undang</span>
                        </a>
                        <a href="{{ route('desain-profil.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('desain-profil.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('desain-profil.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs">Desain Profil</span>
                        </a>
                        @if(\App\Helpers\ModuleHelper::isActive('PAGES'))
                        <a href="{{ route('my-pages.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('my-pages.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('my-pages.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs">My Pages</span>
                        </a>
                        @endif
                        @if(\App\Helpers\ModuleHelper::isActive('GROUPS'))
                        <a href="{{ route('groups.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('groups.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('groups.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-xs">Groups</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endauth
        </div>

        <!-- BAGIAN KANAN: Search & Ikon Aksi -->
        <div class="flex-shrink-0 flex items-center justify-end space-x-3 sm:space-x-4">
            
            {{-- Global Search with Autocomplete --}}
            <div class="relative hidden md:block" id="navbar-search-wrapper">
                <form method="GET" action="{{ route('search.index') }}" autocomplete="off" id="navbar-search-form">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input
                        type="text"
                        name="q"
                        id="navbar-search-input"
                        placeholder="Cari anggota, grup, halaman..."
                        value="{{ request('q') }}"
                        class="bg-[#1A1A1A] border border-neutral-800 rounded-md pl-9 pr-3 py-1.5 text-xs text-neutral-300 focus:outline-none focus:border-neutral-600 w-56 transition placeholder-neutral-600"
                    >
                </form>
                {{-- Dropdown Autocomplete --}}
                <div id="navbar-search-dropdown"
                     class="absolute top-full right-0 mt-1.5 w-80 bg-[#1A1A1A] border border-neutral-800 rounded-xl shadow-2xl z-50 overflow-hidden hidden">
                    <div id="navbar-search-results" class="py-1"></div>
                    <div id="navbar-search-footer" class="hidden border-t border-neutral-800 px-3 py-2">
                        <a id="navbar-search-all" href="#"
                           class="text-xs text-[#990000] hover:text-red-400 font-semibold transition">
                            Lihat semua hasil →
                        </a>
                    </div>
                </div>
            </div>

            @auth
                <!-- Ikon Teman, Setting, Notif, Chat -->
                <div class="hidden sm:flex items-center space-x-3 text-neutral-400">
                    <a href="{{ route('friends.index') }}" class="hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></a>
                    <a href="{{ route('profile.edit') }}" class="hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></a>
                    @php $notifCount = app(\App\Repositories\Contracts\NotificationRepositoryInterface::class)->countUnread(auth()->id()); @endphp
                    <!-- Notifikasi Dropdown Wrapper -->
                    <div class="relative" id="notif-dropdown-wrapper">
                        <button type="button" id="notif-badge-btn" class="hover:text-white transition relative focus:outline-none flex items-center p-1 rounded-full hover:bg-neutral-800" title="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span id="notif-badge-ping" class="absolute -top-1 -right-1 bg-red-600 rounded-full h-4 w-4 animate-ping pointer-events-none hidden"></span>
                            <span id="notif-badge" class="absolute -top-1 -right-1 bg-[#b71c1c] text-white text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center {{ $notifCount > 0 ? '' : 'hidden' }} transition-transform duration-200 shadow-sm">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                        </button>

                        <!-- Panel Dropdown Notifikasi -->
                        <div id="notif-dropdown-panel" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-[#1A1A1A] border border-neutral-800 rounded-2xl shadow-2xl z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                            <div class="px-4 py-3 border-b border-neutral-800 flex items-center justify-between bg-[#141414]">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Notifikasi</h4>
                                    <span id="notif-panel-badge" class="bg-[#b71c1c] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $notifCount > 0 ? '' : 'hidden' }}">{{ $notifCount }}</span>
                                </div>
                                <button type="button" id="notif-mark-all-btn" class="text-[10px] text-neutral-400 hover:text-white transition font-medium hover:underline">Tandai Semua Dibaca</button>
                            </div>
                            <div id="notif-dropdown-content" class="max-h-80 overflow-y-auto divide-y divide-neutral-800/60 scrollbar-thin scrollbar-thumb-neutral-700">
                                <div class="p-4 text-center text-xs text-neutral-500">Memuat notifikasi...</div>
                            </div>
                            <div class="border-t border-neutral-800 p-2.5 bg-[#141414] text-center">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-[#b71c1c] hover:text-red-400 transition block py-0.5">Lihat Semua Notifikasi →</a>
                            </div>
                        </div>
                    </div>

                    @php $msgCount = app(\App\Repositories\Contracts\MessageRepositoryInterface::class)->countUnread(auth()->id()); @endphp
                    <!-- Pesan Dropdown Wrapper -->
                    <div class="relative" id="msg-dropdown-wrapper">
                        <button type="button" id="msg-badge-btn" class="hover:text-white transition relative focus:outline-none flex items-center p-1 rounded-full hover:bg-neutral-800" title="Pesan Obrolan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span id="msg-badge-ping" class="absolute -top-1 -right-1 bg-red-600 rounded-full h-4 w-4 animate-ping pointer-events-none hidden"></span>
                            <span id="msg-badge" class="absolute -top-1 -right-1 bg-[#b71c1c] text-white text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center {{ $msgCount > 0 ? '' : 'hidden' }} transition-transform duration-200 shadow-sm">{{ $msgCount > 9 ? '9+' : $msgCount }}</span>
                        </button>

                        <!-- Panel Dropdown Pesan -->
                        <div id="msg-dropdown-panel" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-[#1A1A1A] border border-neutral-800 rounded-2xl shadow-2xl z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                            <div class="px-4 py-3 border-b border-neutral-800 flex items-center justify-between bg-[#141414]">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Pesan Masuk</h4>
                                    <span id="msg-panel-badge" class="bg-[#b71c1c] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $msgCount > 0 ? '' : 'hidden' }}">{{ $msgCount }}</span>
                                </div>
                                <a href="{{ route('messages.index') }}" class="text-[10px] text-neutral-400 hover:text-white transition font-medium hover:underline">Kotak Masuk</a>
                            </div>
                            <div id="msg-dropdown-content" class="max-h-80 overflow-y-auto divide-y divide-neutral-800/60 scrollbar-thin scrollbar-thumb-neutral-700">
                                <div class="p-4 text-center text-xs text-neutral-500">Memuat pesan...</div>
                            </div>
                            <div class="border-t border-neutral-800 p-2.5 bg-[#141414] text-center">
                                <a href="{{ route('messages.index') }}" class="text-xs font-bold text-[#b71c1c] hover:text-red-400 transition block py-0.5">Buka Semua Pesan →</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Tombol Profile & Dropdown (Sistem HOVER murni tanpa JS) -->
            <div class="relative ml-2 group">
                <!-- Tombol Avatar -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center cursor-pointer hover:opacity-80 transition shadow bg-neutral-800 overflow-hidden text-neutral-400">
                    @auth
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    @endauth
                </div>

                <!-- Pembungkus Dropdown dengan Jembatan Hover (pt-2) -->
                <div class="absolute right-0 top-full pt-2 w-40 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="bg-[#1A1A1A] border border-neutral-800 rounded-lg shadow-xl py-1">
                        
                        @guest
                            <div class="px-3 py-2 border-b border-neutral-800">
                                <p class="text-[10px] text-neutral-400 font-medium uppercase tracking-wider">Welcome Guest</p>
                            </div>
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-xs text-neutral-300 hover:bg-neutral-800 hover:text-white transition">Masuk (Login)</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-xs text-neutral-300 hover:bg-neutral-800 hover:text-white transition">Daftar Akun</a>
                        @endguest

                        @auth
                            <div class="px-3 py-2 border-b border-neutral-800">
                                <p class="text-xs text-white font-semibold truncate">{{ auth()->user()->fullname ?: auth()->user()->username }}</p>
                                <p class="text-[10px] text-neutral-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show', auth()->user()->username) }}" class="block px-4 py-2 text-xs text-neutral-300 hover:bg-neutral-800 hover:text-white transition mt-1">Profil Saya</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-neutral-300 hover:bg-neutral-800 hover:text-white transition">Pengaturan</a>
                            
                            @php
                                $isAdmin = auth()->check() && (auth()->user()->roles == 1 || in_array(strtolower(auth()->user()->roles), ['admin', 'administrator']));
                            @endphp
                            @if($isAdmin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-neutral-300 hover:bg-neutral-800 hover:text-white transition">Tampilan Admin</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-500 font-bold hover:bg-neutral-800 hover:text-red-400 transition mt-1 border-t border-neutral-800">
                                    Logout
                                </button>
                            </form>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>

<script>
(function () {
    const input      = document.getElementById('navbar-search-input');
    const dropdown   = document.getElementById('navbar-search-dropdown');
    const results    = document.getElementById('navbar-search-results');
    const footer     = document.getElementById('navbar-search-footer');
    const footerLink = document.getElementById('navbar-search-all');

    if (!input) return;

    let debounceTimer;

    const icons = {
        user:  `<svg class="w-3.5 h-3.5 text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`,
        group: `<svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`,
        page:  `<svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
    };

    const labels = { user: 'Anggota', group: 'Grup', page: 'Halaman' };

    function renderItems(data) {
        const all = [
            ...data.users.map(u => ({ ...u, type: 'user' })),
            ...data.groups.map(g => ({ ...g, type: 'group' })),
            ...data.pages.map(p => ({ ...p, type: 'page' })),
        ];

        if (!all.length) {
            results.innerHTML = `<div class="px-4 py-3 text-xs text-neutral-500">Tidak ada hasil ditemukan.</div>`;
            footer.classList.add('hidden');
            dropdown.classList.remove('hidden');
            return;
        }

        let html = '';
        let lastType = null;

        all.forEach(item => {
            if (item.type !== lastType) {
                html += `<div class="px-3 pt-2 pb-1 text-[9px] font-bold uppercase tracking-widest text-neutral-600">${labels[item.type]}</div>`;
                lastType = item.type;
            }
            const avatar = item.avatar || item.logo
                ? `<img src="${item.avatar || item.logo}" class="w-7 h-7 rounded-full object-cover border border-neutral-700 flex-shrink-0">`
                : `<div class="w-7 h-7 rounded-full bg-neutral-800 flex items-center justify-center flex-shrink-0">${icons[item.type]}</div>`;

            const sub = item.username ? `<span class="text-[10px] text-neutral-500">@${item.username}</span>` : '';

            html += `
                <a href="${item.url}" class="flex items-center gap-2.5 px-3 py-2 hover:bg-neutral-800 transition group">
                    ${avatar}
                    <div class="min-w-0">
                        <div class="text-xs text-neutral-200 font-medium truncate group-hover:text-white">${item.name}</div>
                        ${sub}
                    </div>
                </a>`;
        });

        results.innerHTML = html;
        footer.classList.remove('hidden');
        dropdown.classList.remove('hidden');
    }

    function fetchResults(q) {
        fetch(`{{ route('search.autocomplete') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => renderItems(data))
            .catch(() => {});
    }

    input.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        if (q.length < 2) {
            dropdown.classList.add('hidden');
            return;
        }
        debounceTimer = setTimeout(() => {
            fetchResults(q);
            const searchUrl = `{{ route('search.index') }}?q=${encodeURIComponent(q)}`;
            footerLink.href = searchUrl;
        }, 300);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const q = this.value.trim();
            if (q) window.location.href = `{{ route('search.index') }}?q=${encodeURIComponent(q)}`;
        }
        if (e.key === 'Escape') dropdown.classList.add('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('navbar-search-wrapper').contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    input.addEventListener('focus', function () {
        if (this.value.trim().length >= 2) dropdown.classList.remove('hidden');
    });
})();
</script>

<script>
(function () {
    var notifBtn = document.getElementById('notif-badge-btn');
    var notifPanel = document.getElementById('notif-dropdown-panel');
    var notifContent = document.getElementById('notif-dropdown-content');
    var notifBadge = document.getElementById('notif-badge');
    var notifPanelBadge = document.getElementById('notif-panel-badge');
    var notifMarkAllBtn = document.getElementById('notif-mark-all-btn');

    var msgBtn = document.getElementById('msg-badge-btn');
    var msgPanel = document.getElementById('msg-dropdown-panel');
    var msgContent = document.getElementById('msg-dropdown-content');
    var msgBadge = document.getElementById('msg-badge');
    var msgPanelBadge = document.getElementById('msg-panel-badge');

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function updateBadge(badge, count) {
        if (!badge) return;
        var num = parseInt(count, 10) || 0;
        if (num > 0) {
            badge.textContent = num > 9 ? '9+' : num;
            badge.classList.remove('hidden');
            // Micro bounce animation
            badge.classList.add('scale-125');
            setTimeout(function() { badge.classList.remove('scale-125'); }, 300);
        } else {
            badge.classList.add('hidden');
        }
    }

    // Toggle Dropdowns
    if (notifBtn && notifPanel) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (msgPanel) msgPanel.classList.add('hidden');
            var isHidden = notifPanel.classList.toggle('hidden');
            if (!isHidden) {
                loadNotifDropdown();
            }
        });
    }

    if (msgBtn && msgPanel) {
        msgBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (notifPanel) notifPanel.classList.add('hidden');
            var isHidden = msgPanel.classList.toggle('hidden');
            if (!isHidden) {
                loadMsgDropdown();
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (notifPanel && !notifPanel.contains(e.target) && e.target !== notifBtn && !notifBtn?.contains(e.target)) {
            notifPanel.classList.add('hidden');
        }
        if (msgPanel && !msgPanel.contains(e.target) && e.target !== msgBtn && !msgBtn?.contains(e.target)) {
            msgPanel.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (notifPanel) notifPanel.classList.add('hidden');
            if (msgPanel) msgPanel.classList.add('hidden');
        }
    });

    // Autoload Messages Dropdown
    function loadMsgDropdown() {
        if (!msgContent) return;
        msgContent.innerHTML = '<div class="p-6 text-center text-xs text-neutral-400 flex items-center justify-center gap-2">'
            + '<svg class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>'
            + 'Memuat pesan terbaru...</div>';

        fetch('{{ route("messages.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            updateBadge(msgBadge, data.total_unread);
            updateBadge(msgPanelBadge, data.total_unread);

            if (!data.conversations || data.conversations.length === 0) {
                msgContent.innerHTML = '<div class="p-8 text-center text-xs text-neutral-500 flex flex-col items-center gap-2">'
                    + '<svg class="w-8 h-8 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>'
                    + 'Belum ada percakapan.</div>';
                return;
            }

            var html = '';
            data.conversations.forEach(function(conv) {
                var avatarHtml = conv.user.avatar_url
                    ? '<img src="' + conv.user.avatar_url + '" class="w-9 h-9 rounded-full object-cover border border-neutral-700">'
                    : '<div class="w-9 h-9 rounded-full bg-red-950/60 text-red-400 font-bold flex items-center justify-center text-xs border border-red-900/50">' + (conv.user.initial || 'U') + '</div>';

                var unreadDot = conv.unread > 0
                    ? '<span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-[#b71c1c] rounded-full ring-2 ring-[#1A1A1A]"></span>'
                    : '';

                var snippet = conv.last_message
                    ? (conv.last_message.is_mine ? '<span class="text-neutral-500 font-normal">Kamu: </span>' : '') + escapeHtml(conv.last_message.message)
                    : '<span class="italic text-neutral-500">Belum ada pesan</span>';

                var timeStr = conv.last_message ? conv.last_message.time : '';
                var isUnread = conv.unread > 0;

                html += '<a href="' + conv.url + '" class="flex items-center gap-3 p-3 hover:bg-neutral-800/80 transition group ' + (isUnread ? 'bg-neutral-800/30' : '') + '">'
                    + '<div class="relative flex-shrink-0">' + avatarHtml + unreadDot + '</div>'
                    + '<div class="flex-1 min-w-0">'
                    + '<div class="flex items-center justify-between mb-0.5">'
                    + '<h5 class="text-xs font-bold text-neutral-200 truncate group-hover:text-white">' + escapeHtml(conv.user.name) + '</h5>'
                    + '<span class="text-[10px] text-neutral-500 flex-shrink-0 ml-1">' + timeStr + '</span>'
                    + '</div>'
                    + '<p class="text-[11px] ' + (isUnread ? 'text-neutral-200 font-semibold' : 'text-neutral-400') + ' truncate">' + snippet + '</p>'
                    + '</div>'
                    + (isUnread ? '<span class="bg-[#b71c1c] text-white text-[9px] font-bold px-1.5 py-0.2 rounded-full flex-shrink-0">' + conv.unread + '</span>' : '')
                    + '</a>';
            });
            msgContent.innerHTML = html;
        })
        .catch(function() {
            msgContent.innerHTML = '<div class="p-6 text-center text-xs text-red-400">Gagal memuat pesan.</div>';
        });
    }

    // Autoload Notifications Dropdown
    function loadNotifDropdown() {
        if (!notifContent) return;
        notifContent.innerHTML = '<div class="p-6 text-center text-xs text-neutral-400 flex items-center justify-center gap-2">'
            + '<svg class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>'
            + 'Memuat notifikasi...</div>';

        fetch('{{ route("notifications.index") }}?page=1', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.html || data.html.trim() === '') {
                notifContent.innerHTML = '<div class="p-8 text-center text-xs text-neutral-500 flex flex-col items-center gap-2">'
                    + '<svg class="w-8 h-8 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>'
                    + 'Tidak ada notifikasi baru.</div>';
                return;
            }
            notifContent.innerHTML = data.html;
        })
        .catch(function() {
            notifContent.innerHTML = '<div class="p-6 text-center text-xs text-red-400">Gagal memuat notifikasi.</div>';
        });
    }

    // Mark All Read Button AJAX
    if (notifMarkAllBtn) {
        notifMarkAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route("notifications.markAllRead") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function() {
                updateBadge(notifBadge, 0);
                updateBadge(notifPanelBadge, 0);
                loadNotifDropdown();
            })
            .catch(function() {});
        });
    }

    // Autoloader Heartbeat / Sync on Focus
    function syncUnreadCounts() {
        fetch('{{ route("messages.unreadCount") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                updateBadge(msgBadge, d.count);
                updateBadge(msgPanelBadge, d.count);
            }).catch(function() {});

        fetch('{{ route("notifications.unreadCount") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                updateBadge(notifBadge, d.count);
                updateBadge(notifPanelBadge, d.count);
            }).catch(function() {});
    }

    window.addEventListener('focus', syncUnreadCounts);
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') syncUnreadCounts();
    });
    window.addEventListener('sync-unread-badges', syncUnreadCounts);
    setInterval(syncUnreadCounts, 30000);

    @auth
    if (window.Echo) {
        var msgSoundSrc = '{{ asset("bereal.mp3") }}';

        function playNotifSound() {
            var s = new Audio(msgSoundSrc);
            s.volume = 1;
            s.play().catch(function() {});
        }

        function triggerPing(type) {
            var pingEl = document.getElementById(type + '-badge-ping');
            if (!pingEl) return;
            pingEl.classList.remove('hidden');
            setTimeout(function() {
                pingEl.classList.add('hidden');
            }, 1800);
        }

        function showFloatingToast(title, body, url, avatarImg) {
            var container = document.getElementById('realtime-toast-container');
            if (!container) return;

            var toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-white border border-neutral-200 rounded-2xl shadow-xl p-3 flex items-start gap-3 transform transition-all duration-300 translate-x-full opacity-0 cursor-pointer hover:shadow-2xl relative overflow-hidden';
            toast.onclick = function() { if (url) window.location.href = url; };

            var iconHtml = avatarImg ? '<img src="' + avatarImg + '" class="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-neutral-200">' : '<div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>';

            toast.innerHTML = iconHtml + '<div class="flex-1 min-w-0 pr-2">'
                + '<h5 class="text-xs font-bold text-neutral-900 truncate">' + title + '</h5>'
                + '<p class="text-[11px] text-neutral-600 line-clamp-2 mt-0.5">' + body + '</p>'
                + '</div>'
                + '<button onclick="event.stopPropagation(); this.parentElement.remove();" class="text-neutral-400 hover:text-neutral-600 font-bold px-1 text-sm">&times;</button>'
                + '<div class="toast-progress-bar h-1 bg-gradient-to-r from-red-500 to-[#b71c1c] absolute bottom-0 left-0 w-full transition-all duration-[4900ms] ease-linear"></div>';

            container.appendChild(toast);

            setTimeout(function() {
                toast.classList.remove('translate-x-full', 'opacity-0');
                var bar = toast.querySelector('.toast-progress-bar');
                if (bar) bar.style.width = '0%';
            }, 60);

            setTimeout(function() {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(function() { toast.remove(); }, 350);
            }, 5000);
        }

        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.message.sent', function(e) {
                updateBadge(msgBadge, e.totalUnread);
                updateBadge(msgPanelBadge, e.totalUnread);
                triggerPing('msg');
                playNotifSound();
                if (msgPanel && !msgPanel.classList.contains('hidden')) {
                    loadMsgDropdown();
                }
                // Jika sedang tidak membuka percakapan dengan pengirim, tampilkan toast
                if (!window.location.pathname.includes('/messages/conversation/' + e.sender.id)) {
                    showFloatingToast('Pesan dari ' + (e.sender.fullname || e.sender.username), e.message.message, '{{ url("/messages/conversation") }}/' + e.sender.id, e.sender.avatar_url);
                }
            })
            .listen('.message.read', function(e) {
                if (e.readerId == {{ Auth::id() }}) {
                    updateBadge(msgBadge, e.readerUnreadCount);
                    updateBadge(msgPanelBadge, e.readerUnreadCount);
                    if (msgPanel && !msgPanel.classList.contains('hidden')) {
                        loadMsgDropdown();
                    }
                }
            })
            .listen('.notification.created', function(e) {
                updateBadge(notifBadge, e.unreadNotificationCount);
                updateBadge(notifPanelBadge, e.unreadNotificationCount);
                triggerPing('notif');
                playNotifSound();
                if (notifPanel && !notifPanel.classList.contains('hidden')) {
                    loadNotifDropdown();
                }
                showFloatingToast('Notifikasi Baru', e.notification.message, '{{ route("notifications.index") }}');
            });
    }
    @endauth
})();
</script>

<!-- Realtime Floating Toast Container -->
<div id="realtime-toast-container" class="fixed top-20 right-5 z-[9999] flex flex-col gap-2 max-w-sm pointer-events-none"></div>