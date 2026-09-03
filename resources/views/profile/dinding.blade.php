@extends('layouts.app')
@section('title', $profileUser->fullname . ' - Profile')

@section('content')
<div class="max-w-[95%] xl:max-w-7xl mx-auto w-full pb-10">
    <!-- Area Cover / Background Profil -->
    <div class="relative w-full h-48 md:h-64 bg-neutral-200 rounded-xl overflow-hidden shadow-sm mb-6 group">
        @if($profileUser->profile && $profileUser->profile->background)
            <!-- Menampilkan Cover Jika Ada -->
            <img src="{{ asset('storage/backgrounds/' . $profileUser->profile->background) }}" class="w-full h-full object-cover" alt="Cover Profile">
        @else
            <!-- Gambar Default Cover Jika Belum Punya -->
            <div class="w-full h-full bg-gradient-to-r from-neutral-300 to-neutral-200"></div>
        @endif

        <!-- Tombol Upload (Hanya Muncul Jika Profil Milik User yang Login) -->
        @if(auth()->check() && auth()->user()->id === $profileUser->id)
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition duration-300">
                <form action="{{ route('profile.background.update') }}" method="POST" enctype="multipart/form-data" id="form-cover-upload">
                    @csrf
                    <!-- Input file disembunyikan -->
                    <input type="file" name="background" id="background-input" class="hidden" accept="image/*" onchange="document.getElementById('form-cover-upload').submit();">
                    
                    <!-- Tombol visual yang dapat di-klik -->
                    <button type="button" onclick="document.getElementById('background-input').click();" class="bg-black bg-opacity-50 hover:bg-opacity-70 text-white text-xs font-bold px-4 py-2 rounded-md backdrop-blur-sm transition shadow-md flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Ganti Sampul
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Bagian Atas: Nama & Tabs -->
    <div class="mb-6">
        <h1 class="text-sm font-extrabold text-neutral-900 uppercase tracking-widest mb-4">{{ $profileUser->fullname }}</h1>
        
        <div class="flex items-center space-x-8 border-b border-neutral-200">
            <a href="?tab=dinding" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'dinding' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Dinding</a>
            <a href="?tab=menyukai" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'menyukai' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Menyukai</a>
            <a href="?tab=foto" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'foto' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Foto</a>
            <a href="?tab=video" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'video' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Video</a>
        </div>
    </div>

    <!-- Grid 3 Kolom Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI (Sidebar Profil) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Kartu Identitas -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5 text-center relative">
                <!-- Avatar -->
                <div class="w-24 h-24 mx-auto rounded-full bg-neutral-100 border-2 border-neutral-200 mb-3 overflow-hidden shadow">
                    <img src="{{ $profileUser->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                </div>

                <!-- Username (Muncul untuk semua profil) -->
                <p class="text-xs text-red-700 font-semibold mt-0.5 mb-3">{{ '@' . $profileUser->username }}</p>

                @if(auth()->check() && auth()->user()->id !== $profileUser->id)
                    {{-- Profil Teman: Tampilkan jabatan --}}
                    @if($profileUser->about_me)
                        <p class="text-[11px] text-neutral-500 mt-0.5 mb-3">{{ Str::limit($profileUser->about_me, 40) }}</p>
                    @else
                        <p class="text-[11px] text-neutral-500 mt-0.5 mb-3">Anggota Xcode-Friends</p>
                    @endif

                    <p class="text-[10px] text-neutral-400 mb-4">
                        Terakhir dilihat: {{ $profileUser->last_active ? \Carbon\Carbon::createFromTimestamp($profileUser->last_active)->diffForHumans() : 'Baru saja' }}
                        <br>
                        <span class="font-semibold text-neutral-600">{{ $profileUser->followers ?? 1 }}</span> Followers
                    </p>

                    {{-- Tombol Aksi Teman --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <button id="btn-ikuti" class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Ikuti
                        </button>
                        <a href="/pesan/{{ $profileUser->username }}" class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            Pesan
                        </a>
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Teman
                        </button>
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            Blokir
                        </button>
                    </div>

                @else
                    {{-- Profil Sendiri: Hanya tampilkan status --}}
                    <p class="text-xs text-neutral-500 mb-4">Terakhir dilihat: Today, 01:00 am<br>{{ $profileUser->followers ?? 1 }} Followers</p>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Ikuti
                        </button>
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            Pesan
                        </button>
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Teman
                        </button>
                        <button class="flex items-center justify-center text-xs font-semibold text-neutral-700 bg-neutral-50 border border-neutral-200 py-1.5 rounded hover:bg-neutral-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            Blokir
                        </button>
                    </div>
                @endif

                @if(auth()->check() && auth()->user()->id === $profileUser->id)
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center w-full text-xs font-bold text-neutral-700 bg-white border border-neutral-200 py-2 rounded shadow-sm hover:bg-neutral-50 transition mb-4">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        EDIT PROFIL
                    </a>
                @endif


            </div>

            <!-- Tentang Saya -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider mb-4">TENTANG SAYA</h4>
                
                @if($profileUser->about_me)
                <p class="text-xs text-neutral-600 mb-4 pb-4 border-b border-neutral-100 leading-relaxed">
                    {{ $profileUser->about_me }}
                </p>
                @endif

                <div class="space-y-3">
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Nama Lengkap</span> <span class="font-bold text-neutral-900">{{ $profileUser->fullname }}</span></div>
                    
                    @if($profileUser->country || $profileUser->location)
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Lokasi</span> <span class="font-bold text-neutral-900">{{ $profileUser->location ? $profileUser->location . ', ' : '' }}{{ $profileUser->country }}</span></div>
                    @endif
                    
                    <div class="flex justify-between text-xs">
                        <span class="text-neutral-500">Jenis Kelamin</span> 
                        <span class="font-bold text-neutral-900">
                            @if($profileUser->gender == 1) Laki-Laki @elseif($profileUser->gender == 2) Perempuan @else Tidak Disebutkan @endif
                        </span>
                    </div>
                    
                    @if(!$profileUser->hide_age && $profileUser->birthyear)
                    <div class="flex justify-between text-xs"><span class="text-neutral-500">Umur</span> <span class="font-bold text-neutral-900">{{ date('Y') - $profileUser->birthyear }} Tahun</span></div>
                    @endif
                </div>
            </div>
            
            <!-- Mengikuti & Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="#" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No following yet</div>
            </div>

            <!-- Teman -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">TEMAN</h4>
                    <a href="{{ route('telusur.index') }}" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($profileUser->friends && $profileUser->friends->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profileUser->friends->take(6) as $friend)
                            <a href="{{ url('/profile/'.$friend->username) }}" class="block">
                                <img src="{{ $friend->avatar_url }}" alt="{{ $friend->fullname ?: $friend->username }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200" title="{{ $friend->fullname ?: $friend->username }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No friends yet</div>
                @endif
            </div>

            @php
                $allGroups = $profileUser->likedGroups->merge($profileUser->createdGroups)->unique('id');
                $allPages = $profileUser->likedPages->merge($profileUser->createdPages)->unique('id');
            @endphp

            <!-- Groups -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">GROUPS</h4>
                    <a href="{{ route('groups.index') }}" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($allGroups && $allGroups->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($allGroups->take(6) as $group)
                            <a href="{{ url('/groups/'.$group->id) }}" class="block">
                                <img src="{{ $group->logo_url ?: asset('assets/img/default.png') }}" alt="{{ $group->name }}" class="w-10 h-10 rounded-md object-cover border border-neutral-200" title="{{ $group->name }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No groups yet</div>
                @endif
            </div>

            <!-- Pages -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">PAGES</h4>
                    <a href="{{ route('pages.index') }}" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
                @if($allPages && $allPages->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($allPages->take(6) as $page)
                            <a href="{{ url('/pages/'.$page->id) }}" class="block">
                                <img src="{{ $page->logo_url ?: asset('assets/img/default.png') }}" alt="{{ $page->name }}" class="w-10 h-10 rounded-md object-cover border border-neutral-200" title="{{ $page->name }}">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-100 rounded text-center py-4 text-xs text-neutral-400">No pages yet</div>
                @endif
            </div>
        </div>

        <!-- KOLOM TENGAH (Feed Dinding / Menyukai / Foto / Video) -->
        <div class="lg:col-span-6 space-y-6">
            
            @if($tab === 'dinding')
                @if(auth()->check() && auth()->user()->id === $profileUser->id)
                <!-- Form Bagi Cepat -->
                <x-feed-upload action="{{ route('stream.store') }}" app="feed" aid="{{ $profileUser->id }}" wallId="{{ $profileUser->id }}" />
                @endif

                <!-- Loop Postingan (Dinding) -->
                @forelse ($streams as $stream)
                    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3">
                                <a href="/@{{ $stream->user->username ?? '#' }}" class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden border border-neutral-200 hover:ring-2 hover:ring-red-700 transition flex-shrink-0">
                                    <img src="{{ $stream->user->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-900">
                                        <a href="/@{{ $stream->user->username ?? '#' }}" class="hover:text-red-700 transition">{{ $stream->user->fullname ?? 'Unknown User' }}</a>
                                    </h4>
                                    <p class="text-[11px] text-neutral-400">
                                        {{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}
                                        @if($stream->app === 'group' && $stream->targetPage)
                                            &bull; Mengunggah di Grup <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                                        @elseif($stream->app === 'page' && $stream->targetPage)
                                            &bull; Mengunggah di Halaman <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <button class="text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg></button>
                        </div>
                        
                        <p class="text-sm text-neutral-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>

                        @if($stream->type == 2 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['photos']) && is_array($att['photos']))
                                @php 
                                    $ptCount = count($att['photos']); 
                                    $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
                                    $jsonPhotos = json_encode($photoUrls);
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($ptCount == 1)
                                        <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-auto max-h-[500px] object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                    @elseif($ptCount == 2)
                                        <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                        </div>
                                    @elseif($ptCount == 3)
                                        <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <div class="grid grid-rows-2 gap-1 h-full">
                                                <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            </div>
                                        </div>
                                    @elseif($ptCount >= 4)
                                        <div class="grid grid-cols-2 gap-1 h-72 sm:h-96">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <div class="grid grid-rows-3 gap-1 h-full">
                                                <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <div class="relative w-full h-full" onclick='openLightbox({!! $jsonPhotos !!}, 3)'>
                                                    <img src="{{ $photoUrls[3] }}" class="w-full h-full object-cover cursor-pointer" alt="Post Photo">
                                                    @if($ptCount > 4)
                                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center cursor-pointer hover:bg-black/50 transition">
                                                        <span class="text-white text-3xl font-bold">+{{ $ptCount - 4 }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif(isset($att['photo']))
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @php $singlePhoto = json_encode([asset('storage/posts/' . $att['photo'])]); @endphp
                                    <img src="{{ asset('storage/posts/' . $att['photo']) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                </div>
                            @endif
                        @endif
                        
                        @if(in_array($stream->app, ['group', 'page']) && $stream->attachment)
                            @if(strpos($stream->attachment, 'youtube:') === 0)
                                @php 
                                    $videoId = substr($stream->attachment, 8); 
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @php $singlePhoto = json_encode([asset('storage/' . $stream->attachment)]); @endphp
                                    <img src="{{ asset('storage/' . $stream->attachment) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                </div>
                            @endif
                        @endif
                        
                        @if($stream->type == 3 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['video_url']))
                                @php
                                    $videoUrl = $att['video_url'];
                                    $embedUrl = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($embedUrl)
                                        <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Tonton Video</a>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-between items-center border-t border-neutral-100 pt-3">
                            <div class="flex space-x-4">
                                <form action="{{ route('like.toggle', $stream->id) }}" method="POST" class="form-like">
                                    @csrf
                                    <button type="submit" class="flex items-center text-xs font-semibold text-neutral-500 hover:text-red-700 transition">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> <span id="like-count-{{ $stream->id }}">{{ $stream->likes }}</span>&nbsp;Suka
                                    </button>
                                </form>
                                <button type="button" onclick="document.getElementById('comment-form-{{ $stream->id }}').classList.toggle('hidden')" class="flex items-center text-xs font-semibold text-neutral-500 hover:text-red-700 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> <span id="comments-count-{{ $stream->id }}">{{ $stream->comments->count() ?? 0 }}</span>&nbsp;Komentar
                                </button>
                            </div>
                            <button class="text-neutral-400 hover:text-neutral-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg></button>
                        </div>

                        <!-- Comment Section -->
                        <div id="comment-form-{{ $stream->id }}" class="hidden mt-3 pt-3 border-t border-neutral-100">
                            <!-- Loop Comments -->
                            <div id="comments-list-{{ $stream->id }}">
                                @foreach($stream->comments as $comment)
                                    <div class="flex items-start space-x-2 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->fullname ?? 'Unknown') }}&background=E5E5E5" class="w-full h-full">
                                        </div>
                                        <div class="bg-neutral-50 p-2.5 rounded-lg flex-1 text-sm">
                                            <span class="font-bold text-neutral-900">{{ $comment->user->fullname ?? 'Unknown' }}</span>
                                            @php 
                                                // Parse Mentions
                                                $parsedMessage = htmlspecialchars($comment->message);
                                                $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);
                                            @endphp
                                            <p class="text-neutral-700 mt-1">{!! $parsedMessage !!}</p>
                                            <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ addslashes($comment->user->fullname ?? $comment->user->username ?? 'user') }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Comment Form -->
                            @if(auth()->check())
                            <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex space-x-2 form-comment" data-stream-id="{{ $stream->id }}">
                                @csrf
                                <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full">
                                </div>
                                <input type="text" name="message" id="comment-input-{{ $stream->id }}" required placeholder="Tulis komentar..." class="flex-1 bg-neutral-50 border border-neutral-200 rounded-full px-4 text-sm focus:outline-none focus:border-red-700">
                                <button type="submit" class="text-red-700 font-bold text-sm px-2">Kirim</button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Tidak ada postingan di dinding.</div>
                @endforelse
                
                <div class="mt-4">{{ $streams->links() }}</div>

            @elseif($tab === 'menyukai')
                <div class="bg-neutral-100 rounded-xl p-4 border border-neutral-200 mb-4">
                    <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">MENYUKAI</h3>
                </div>

                <!-- Loop Postingan (Menyukai) -->
                @forelse ($streams as $stream)
                    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3">
                                <a href="/@{{ $stream->user->username ?? '#' }}" class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden border border-neutral-200 hover:ring-2 hover:ring-red-700 transition flex-shrink-0">
                                    <img src="{{ $stream->user->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div>
                                    <h4 class="text-sm font-bold text-neutral-900">
                                        <a href="/@{{ $stream->user->username ?? '#' }}" class="hover:text-red-700 transition">{{ $stream->user->fullname ?? 'Unknown User' }}</a>
                                        <span class="font-normal text-neutral-500">Mendaftar / Menyukai</span>
                                    </h4>
                                    <p class="text-[11px] text-neutral-400">
                                        <svg class="w-3 h-3 inline pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ \Carbon\Carbon::createFromTimestamp($stream->created)->format('M jS Y, g:i a') }}
                                        @if($stream->app === 'group' && $stream->targetPage)
                                            &bull; Mengunggah di Grup <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                                        @elseif($stream->app === 'page' && $stream->targetPage)
                                            &bull; Mengunggah di Halaman <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-neutral-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>

                        @if($stream->type == 2 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['photos']) && is_array($att['photos']))
                                @php 
                                    $ptCount = count($att['photos']); 
                                    $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
                                    $jsonPhotos = json_encode($photoUrls);
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($ptCount == 1)
                                        <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-auto max-h-[500px] object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                    @elseif($ptCount == 2)
                                        <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                        </div>
                                    @elseif($ptCount == 3)
                                        <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <div class="grid grid-rows-2 gap-1 h-full">
                                                <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            </div>
                                        </div>
                                    @elseif($ptCount >= 4)
                                        <div class="grid grid-cols-2 gap-1 h-72 sm:h-96">
                                            <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                            <div class="grid grid-rows-3 gap-1 h-full">
                                                <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                                <div class="relative w-full h-full" onclick='openLightbox({!! $jsonPhotos !!}, 3)'>
                                                    <img src="{{ $photoUrls[3] }}" class="w-full h-full object-cover cursor-pointer" alt="Post Photo">
                                                    @if($ptCount > 4)
                                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center cursor-pointer hover:bg-black/50 transition">
                                                        <span class="text-white text-3xl font-bold">+{{ $ptCount - 4 }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif(isset($att['photo']))
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @php $singlePhoto = json_encode([asset('storage/posts/' . $att['photo'])]); @endphp
                                    <img src="{{ asset('storage/posts/' . $att['photo']) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                </div>
                            @endif
                        @endif
                        
                        @if(in_array($stream->app, ['group', 'page']) && $stream->attachment)
                            @if(strpos($stream->attachment, 'youtube:') === 0)
                                @php 
                                    $videoId = substr($stream->attachment, 8); 
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @php $singlePhoto = json_encode([asset('storage/' . $stream->attachment)]); @endphp
                                    <img src="{{ asset('storage/' . $stream->attachment) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                                </div>
                            @endif
                        @endif
                        
                        @if($stream->type == 3 && $stream->attachment)
                            @php $att = json_decode($stream->attachment, true); @endphp
                            @if(isset($att['video_url']))
                                @php
                                    $videoUrl = $att['video_url'];
                                    $embedUrl = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                @endphp
                                <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($embedUrl)
                                        <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Tonton Video</a>
                                    @endif
                                </div>
                            @endif
                        @endif


                        <div class="text-[11px] text-neutral-500 border-t border-neutral-100 pt-3">
                            <button class="text-red-700 hover:underline font-semibold">+Komentar</button> <span class="mx-1">|</span> <button class="text-red-700 hover:underline font-semibold">Suka</button>
                            <div class="mt-2 text-neutral-400">
                                <svg class="w-3 h-3 inline text-red-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> {{ $stream->likes }} orang menyukai ini
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Belum ada aktivitas yang disukai.</div>
                @endforelse
                
                <div class="mt-4">{{ $streams->links() }}</div>

            @elseif($tab === 'foto')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">FOTO</h3>
                        @if(auth()->check() && auth()->user()->id === $profileUser->id)
                            <a href="#" class="bg-[#990000] text-white text-[10px] font-bold px-4 py-2 rounded uppercase tracking-wider hover:bg-red-800 transition">ADD NEW PHOTO</a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($photos as $photo)
                            @php 
                                $photoUrls = [];
                                if ($photo->type == 2) {
                                    $att = json_decode($photo->attachment, true); 
                                    if (isset($att['photos']) && is_array($att['photos'])) {
                                        $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
                                    } elseif (isset($att['photo'])) {
                                        $photoUrls = [asset('storage/posts/' . $att['photo'])];
                                    }
                                } else {
                                    $photoUrls = [asset('storage/' . $photo->attachment)];
                                }
                            @endphp
                            @if(count($photoUrls) > 0)
                                <div class="group">
                                    <div class="w-full h-40 bg-neutral-100 rounded-lg overflow-hidden border border-neutral-200 mb-3 relative cursor-pointer" onclick='openLightbox({!! json_encode($photoUrls) !!}, 0)'>
                                        <img src="{{ $photoUrls[0] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @if(count($photoUrls) > 1)
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <span class="text-white font-bold text-lg">+{{ count($photoUrls) - 1 }} Foto</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="text-sm font-bold text-neutral-900 mb-1 truncate">{{ $photo->message ?: 'Unggahan Foto' }}</h4>
                                    <p class="text-xs text-neutral-500">{{ $profileUser->fullname }} &bull; {{ \Carbon\Carbon::createFromTimestamp($photo->created)->diffForHumans() }}</p>
                                </div>
                            @endif
                        @empty
                        <div class="col-span-2 text-center text-sm text-neutral-500 py-10">Belum ada foto yang diunggah.</div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $photos->links() }}</div>
                </div>

            @elseif($tab === 'video')
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-bold text-neutral-700 uppercase tracking-wider">VIDEO</h3>
                        @if(auth()->check() && auth()->user()->id === $profileUser->id)
                            <a href="#" class="bg-[#990000] text-white text-[10px] font-bold px-4 py-2 rounded uppercase tracking-wider hover:bg-red-800 transition">ADD NEW VIDEO</a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($videos as $video)
                            @php 
                                $videoUrl = '';
                                $embedUrl = '';
                                $title = $video->message ?: 'Unggahan Video';
                                $desc = '';
                                
                                if ($video->type == 3) {
                                    $att = json_decode($video->attachment, true); 
                                    $videoUrl = $att['video_url'] ?? '';
                                    $title = $att['title'] ?? $title;
                                    $desc = $att['desc'] ?? '';
                                    
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                } else {
                                    // Group / Page Youtube post
                                    if (strpos($video->attachment, 'youtube:') === 0) {
                                        $videoId = substr($video->attachment, 8);
                                        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                        $videoUrl = 'https://www.youtube.com/watch?v=' . $videoId;
                                    }
                                }
                            @endphp
                            @if($videoUrl || $embedUrl)
                                <div class="bg-neutral-50 rounded-xl overflow-hidden border border-neutral-200">
                                    @if($embedUrl)
                                        <iframe src="{{ $embedUrl }}" class="w-full h-40" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $videoUrl }}" target="_blank" class="block w-full h-40 bg-neutral-200 flex items-center justify-center group-hover:opacity-90 transition relative">
                                            <svg class="w-8 h-8 text-neutral-400 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 hover:opacity-100 transition duration-300">
                                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-red-700 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="p-4 bg-white">
                                        <h4 class="text-sm font-bold text-neutral-900 mb-1 truncate">{{ $title }}</h4>
                                        <p class="text-[11px] text-neutral-500 mb-2">{{ $profileUser->fullname }} &bull; {{ \Carbon\Carbon::createFromTimestamp($video->created)->diffForHumans() }}</p>
                                        @if($desc)
                                            <p class="text-xs text-neutral-700 line-clamp-2">{{ $desc }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                        <div class="col-span-2 text-center text-sm text-neutral-500 py-10">Belum ada video yang diunggah.</div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $videos->links() }}</div>
                </div>

            @endif

        </div>

        <!-- KOLOM KANAN (Widget) -->
        <div class="lg:col-span-3 space-y-6">
            <x-sidebar-right />
        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    const tabs = ['status', 'unggah', 'video'];
    
    tabs.forEach(t => {
        const content = document.getElementById('tab-content-' + t);
        if(content) {
            content.classList.add('hidden');
            const inputs = content.querySelectorAll('input, textarea, select');
            inputs.forEach(input => input.disabled = true);
        }

        const btn = document.getElementById('tab-btn-' + t);
        if(btn) {
            btn.classList.remove('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
            btn.classList.add('text-neutral-500', 'font-medium');
        }
    });

    const activeContent = document.getElementById('tab-content-' + tabName);
    if(activeContent) {
        activeContent.classList.remove('hidden');
        const activeInputs = activeContent.querySelectorAll('input, textarea, select');
        activeInputs.forEach(input => input.disabled = false);
    }

    const activeBtn = document.getElementById('tab-btn-' + tabName);
    if(activeBtn) {
        activeBtn.classList.remove('text-neutral-500', 'font-medium');
        activeBtn.classList.add('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    switchTab('status');
});





// Function to handle Reply
function replyTo(streamId, username) {
    // Show comment form if hidden
    const commentForm = document.getElementById('comment-form-' + streamId);
    if(commentForm && commentForm.classList.contains('hidden')) {
        commentForm.classList.remove('hidden');
    }
    const input = document.getElementById('comment-input-' + streamId);
    input.value = '@' + username + ' ';
    input.focus();
}

// AJAX Handling
document.addEventListener('DOMContentLoaded', function() {
    
    // AJAX for Likes
    document.querySelectorAll('.form-like').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = url.split('/').pop();

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('like-count-' + streamId).textContent = data.likes;
                const btn = this.querySelector('button');
                if(data.status === 'liked') {
                    btn.classList.add('text-red-700');
                } else {
                    btn.classList.remove('text-red-700');
                }
            });
        });
    });

    // AJAX for Comments
    document.querySelectorAll('.form-comment').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = this.dataset.streamId;
            const inputField = this.querySelector('input[name="message"]');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update comment count
                    document.getElementById('comments-count-' + streamId).textContent = data.comments_count;
                    
                    // Parse Mentions in JS
                    let parsedMessage = data.comment.message;
                    // Escape HTML basic
                    parsedMessage = parsedMessage.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                    // Regex for Mention
                    parsedMessage = parsedMessage.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>');

                    // Append new comment HTML
                    const commentHtml = `
                        <div class="flex items-start space-x-2 mb-3">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                <img src="${data.comment.user.avatar}" class="w-full h-full">
                            </div>
                            <div class="bg-neutral-50 p-2.5 rounded-lg flex-1 text-sm">
                                <span class="font-bold text-neutral-900">${data.comment.user.fullname}</span>
                                <p class="text-neutral-700 mt-1">${parsedMessage}</p>
                                <button type="button" onclick="replyTo('${streamId}', '${data.comment.user.username}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                            </div>
                        </div>
                    `;
                    document.getElementById('comments-list-' + streamId).insertAdjacentHTML('beforeend', commentHtml);
                    
                    // Clear input
                    inputField.value = '';
                }
            });
        });
    });
});

</script>

@include('components.lightbox')

@include('components.feed-scripts')
@endsection