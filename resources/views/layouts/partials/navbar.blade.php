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
            <a href="{{ route('telusur.index') }}" class="{{ request()->routeIs('telusur.*') ? 'text-red-600' : 'hover:text-white' }} transition">Telusur</a>
            <a href="{{ route('videos.public') }}" class="{{ request()->routeIs('videos.*') || request()->routeIs('video.*') ? 'text-red-600' : 'hover:text-white' }} transition">Video</a>
            <a href="{{ route('pages.index') }}" class="{{ request()->routeIs('pages.*') ? 'text-red-600' : 'hover:text-white' }} transition">Pages</a>

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
                        <a href="{{ route('foto.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('foto.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('foto.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs">Foto</span>
                        </a>
                        <a href="{{ route('video.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('video.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('video.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span class="text-xs">Video</span>
                        </a>
                        <a href="{{ route('undang.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('undang.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('undang.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span class="text-xs">Undang</span>
                        </a>
                        <a href="{{ route('desain-profil.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('desain-profil.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('desain-profil.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs">Desain Profil</span>
                        </a>
                        <a href="{{ route('my-pages.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('my-pages.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('my-pages.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs">My Pages</span>
                        </a>
                        <a href="{{ route('groups.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('groups.*') ? 'bg-[#990000] text-white' : 'text-neutral-300 hover:text-white hover:bg-neutral-800' }} rounded-md transition group/item">
                            <svg class="w-4 h-4 {{ request()->routeIs('groups.*') ? 'opacity-90' : 'opacity-70 group-hover/item:opacity-100 transition' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-xs">Groups</span>
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </div>

        <!-- BAGIAN KANAN: Search & Ikon Aksi -->
        <div class="flex-shrink-0 flex items-center justify-end space-x-3 sm:space-x-4">
            
            <div class="relative hidden xl:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Search Network" class="bg-[#1A1A1A] border border-neutral-800 rounded-md pl-9 pr-3 py-1.5 text-xs text-neutral-300 focus:outline-none focus:border-neutral-600 w-52 transition">
            </div>

            @auth
                <!-- Ikon Teman, Setting, Notif, Chat -->
                <div class="hidden sm:flex items-center space-x-3 text-neutral-400">
                    <a href="{{ route('friends.index') }}" class="hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></a>
                    <a href="#" class="hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></a>
                    @php $notifCount = app(\App\Repositories\Contracts\NotificationRepositoryInterface::class)->countUnread(auth()->id()); @endphp
                    <a href="{{ route('notifications.index') }}" class="hover:text-white transition relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($notifCount > 0)
                            <span class="absolute -top-1 -right-1 bg-[#b71c1c] text-white text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('messages.index') }}" class="hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></a>
                </div>
            @endauth

            <!-- Tombol Profile & Dropdown (Sistem HOVER murni tanpa JS) -->
            <div class="relative ml-2 group">
                <!-- Tombol Avatar -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center cursor-pointer hover:opacity-80 transition shadow bg-neutral-800 overflow-hidden">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
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