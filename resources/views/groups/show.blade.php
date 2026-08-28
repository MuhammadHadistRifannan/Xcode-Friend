@extends('layouts.app')

@section('content')
{{-- Penting: hide x-cloak by default --}}
<style>[x-cloak] { display: none !important; }</style>
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif
    


    <!-- Header Grup & Banner -->
    <!-- Dihilangkan overflow-hidden agar dropdown tidak terpotong -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        
        <!-- Banner -->
        @if($group->background)
            <div class="h-48 sm:h-64 md:h-80 lg:h-[350px] w-full bg-cover bg-center relative rounded-t-xl" style="background-image: url('{{ asset('storage/'.$group->background) }}');"></div>
        @else
            <div class="h-48 sm:h-64 md:h-80 lg:h-[350px] w-full bg-gradient-to-r from-red-700 to-red-900 relative rounded-t-xl"></div>
        @endif
        
        <!-- Info Grup & Tombol -->
        <div class="relative px-6 pb-6">
            <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-4">
                
                <!-- Logo & Info -->
                <div class="flex flex-col md:flex-row items-center md:items-end gap-5">
                    <!-- Logo overlap banner -->
                    <div class="-mt-16 z-10 shrink-0">
                        <img src="{{ $group->logo ? asset('storage/'.$group->logo) : asset('img/default-group.png') }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-white bg-white shadow-md" 
                             alt="Logo Grup">
                    </div>
                    <!-- Judul (sejajar dengan logo di md up) -->
                    <div class="pb-2 text-center md:text-left">
                        <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-1">{{ $group->name }}</h1>
                        <p class="text-sm text-gray-500 font-medium">/groups/{{ $group->uri }}</p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pb-2 flex justify-center w-full md:w-auto">
                    @if($group->uid === Auth::id())
                        <div x-data="{ open: false }" class="relative w-full md:w-auto">
                            <!-- Trigger Button -->
                            <button @click="open = !open" type="button" class="inline-flex w-full justify-center items-center gap-2 bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-5 py-2.5 rounded-lg font-bold transition-colors shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Pengaturan Grup
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 border border-gray-100 divide-y divide-gray-100 focus:outline-none z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <a href="{{ route('groups.edit', $group->id) }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit Grup & Logo
                                    </a>
                                    <a href="{{ route('groups.members', $group->id) }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        Kelola Anggota
                                    </a>
                                    <a href="{{ url('groups/' . $group->id . '/pending') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu Persetujuan
                                    </a>
                                </div>
                                <div class="py-1">
                                    <form action="{{ url('groups/' . $group->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus grup ini beserta isinya?')" class="group flex w-full items-center px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 hover:text-red-700">
                                            <svg class="mr-3 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus Grup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($group->members->contains(Auth::id()))
                        <form action="{{ route('groups.leave', $group->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin keluar dari grup ini?')" class="inline-flex w-full justify-center items-center gap-2 bg-gray-100 text-gray-700 border border-gray-300 hover:bg-red-600 hover:text-white hover:border-red-600 px-6 py-2.5 rounded-lg font-bold transition-all shadow-sm text-sm group focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar dari Grup
                            </button>
                        </form>
                    @else
                        <form action="{{ route('groups.join', $group->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center items-center gap-2 bg-blue-600 text-white border border-transparent hover:bg-blue-700 px-8 py-2.5 rounded-lg font-bold transition-colors shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Gabung Grup
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Container Utama (Kiri & Kanan) -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- KOLOM KIRI (Utama - Wall) -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Upload Box (Tampil jika user adalah member grup) -->
            @if($isMember)
            <div x-data="{ 
                tab: 'status', 
                photoName: null, 
                photoPreview: null,
                videoUrl: '',
                videoId: null,
                extractVideoId() {
                    let match = this.videoUrl.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/);
                    this.videoId = match ? match[1] : null;
                }
            }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Top Tabs -->
                <div class="flex border-b border-gray-100">
                    <button type="button" @click="tab = 'status'" :class="{'text-red-700 border-b-2 border-red-700': tab === 'status', 'text-gray-500 hover:text-gray-700': tab !== 'status'}" class="flex-1 py-3 text-sm font-bold uppercase tracking-wide">Dinding</button>
                    <button type="button" @click="tab = 'photo'" :class="{'text-red-700 border-b-2 border-red-700': tab === 'photo', 'text-gray-500 hover:text-gray-700': tab !== 'photo'}" class="flex-1 py-3 text-sm font-bold uppercase tracking-wide">Foto</button>
                    <button type="button" @click="tab = 'video'" :class="{'text-red-700 border-b-2 border-red-700': tab === 'video', 'text-gray-500 hover:text-gray-700': tab !== 'video'}" class="flex-1 py-3 text-sm font-bold uppercase tracking-wide">Video</button>
                </div>

                <!-- Inner content -->
                <div class="p-5">
                    <!-- Sub Tabs -->
                    <div class="flex items-center gap-6 mb-4 border-b border-gray-50 pb-4">
                        <button type="button" @click="tab = 'status'" :class="{'text-red-700': tab === 'status', 'text-gray-500 hover:text-gray-700': tab !== 'status'}" class="flex items-center gap-2 text-sm font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            STATUS
                        </button>
                        <button type="button" @click="tab = 'photo'" :class="{'text-red-700': tab === 'photo', 'text-gray-500 hover:text-gray-700': tab !== 'photo'}" class="flex items-center gap-2 text-sm font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            UNGGAH
                        </button>
                        <button type="button" @click="tab = 'video'" :class="{'text-red-700': tab === 'video', 'text-gray-500 hover:text-gray-700': tab !== 'video'}" class="flex items-center gap-2 text-sm font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            VIDEO
                        </button>
                    </div>

                    <form action="{{ route('groups.stream', $group->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Textarea (Always Visible as caption) -->
                        <textarea name="message" rows="2" class="w-full resize-none border-0 focus:ring-0 text-sm text-gray-700 font-mono placeholder-gray-400 p-0 mb-4 bg-transparent" placeholder="> Awaiting command sequence..."></textarea>
                        
                        <!-- File Upload Input (Shown for Photo) -->
                        <div x-show="tab === 'photo'" x-cloak class="mb-4">
                            <!-- Input Area -->
                            <label x-show="!photoPreview" class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center text-gray-500 hover:bg-gray-50 hover:border-red-300 transition-colors cursor-pointer">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span class="text-sm font-medium">Klik untuk memilih gambar</span>
                                <span class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WebP (max 20MB)</span>
                                <input type="file" name="file" class="hidden" accept="image/*"
                                    @change="
                                        photoName = $event.target.files[0]?.name ?? null; 
                                        if ($event.target.files[0]) {
                                            photoPreview = URL.createObjectURL($event.target.files[0]);
                                        } else {
                                            photoPreview = null;
                                        }
                                    ">
                            </label>
                            
                            <!-- Preview Area -->
                            <div x-show="photoPreview" class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex justify-center items-center h-48">
                                <img :src="photoPreview" class="max-h-full object-contain" alt="Preview">
                                <button type="button" @click="photoPreview = null; photoName = null; $refs.photoInput.value = ''" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-md hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <!-- Kita butuh ref ini untuk mengosongkan input file -->
                                <input type="file" name="file" x-ref="photoInput" class="hidden" accept="image/*"
                                    @change="
                                        photoName = $event.target.files[0]?.name ?? null; 
                                        if ($event.target.files[0]) {
                                            photoPreview = URL.createObjectURL($event.target.files[0]);
                                        } else {
                                            photoPreview = null;
                                        }
                                    ">
                            </div>
                        </div>

                        <!-- Video YouTube URL Input (Shown for Video) -->
                        <div x-show="tab === 'video'" x-cloak class="mb-4">
                            <!-- Input Box -->
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-3">
                                <svg class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <input type="text" name="youtube_url" x-model="videoUrl" @input="extractVideoId()"
                                    class="flex-1 bg-transparent border-0 focus:ring-0 text-sm text-gray-700 placeholder-gray-400 p-0"
                                    placeholder="Tempel link YouTube di sini... (https://youtu.be/...)">
                            </div>
                            
                            <!-- Video Preview -->
                            <div x-show="videoId" class="rounded-xl overflow-hidden bg-black aspect-video relative">
                                <iframe class="w-full h-full"
                                    :src="'https://www.youtube.com/embed/' + videoId"
                                    title="YouTube video preview"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                                <button type="button" @click="videoUrl = ''; videoId = null" class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-md hover:bg-red-50 text-gray-800 hover:text-red-600 transition-colors z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bottom Actions -->
                        <div class="flex justify-end items-center mt-2">
                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded flex items-center gap-2 shadow-sm transition-colors">
                                UNGGAH
                                <svg class="w-4 h-4 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Feed Postingan / Empty State -->
            <div class="max-h-[600px] overflow-y-auto pr-2 pb-4 space-y-5 scrollbar-thin scrollbar-thumb-gray-200">
                @forelse($group->streams as $post)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <!-- Post Header: Avatar + Nama + Waktu -->
                    <div class="flex items-start gap-3 mb-3">
                        <img src="{{ $post->user?->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user?->username ?? 'User').'&background=random&color=fff' }}"
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0" alt="Avatar">
                        <div>
                            <span class="font-bold text-gray-900 text-sm">{{ $post->user?->username ?? 'Unknown User' }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::createFromTimestamp($post->created)->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Isi Postingan -->
                    @if($post->message)
                        <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ $post->message }}</p>
                    @endif

                    <!-- Attachment (Foto atau YouTube Embed) -->
                    @if($post->attachment)
                        @php
                            $isYoutube = str_starts_with($post->attachment, 'youtube:');
                        @endphp
                        @if($isYoutube)
                            @php $ytId = str_replace('youtube:', '', $post->attachment); @endphp
                            <div class="rounded-xl overflow-hidden mt-2 bg-black aspect-video">
                                <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $ytId }}"
                                    title="YouTube video"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <img src="{{ asset('storage/'.$post->attachment) }}"
                                 class="w-full rounded-xl max-h-96 object-contain bg-gray-50 border border-gray-100 mt-2"
                                 alt="Attachment">
                        @endif
                    @endif

                    <!-- Aksi Post (Like & Comment) -->
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-3 mt-3 text-xs font-bold text-gray-500">
                        @php
                            $isLiked = $post->likedBy->where('uid', Auth::id())->count() > 0;
                        @endphp
                        <form action="{{ route('stream.like', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 transition-colors {{ $isLiked ? 'text-red-600' : 'hover:text-red-600' }}">
                                <svg class="w-4 h-4 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                {{ $post->likedBy->count() }} Suka
                            </button>
                        </form>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            {{ $post->comments->count() }} Komentar
                        </div>
                    </div>

                    <!-- List Komentar -->
                    @if($post->comments->count() > 0)
                        <div class="bg-gray-50 rounded-lg p-4 mt-3 space-y-3 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200">
                            @foreach($post->comments as $comment)
                                <div class="flex gap-2">
                                    <img src="{{ $comment->user?->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user?->username ?? 'User').'&background=random&color=fff' }}"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200" alt="Avatar">
                                    <div>
                                        <div class="bg-white px-3 py-2 rounded-2xl border border-gray-100 shadow-sm text-sm">
                                            <span class="font-bold text-gray-900">{{ $comment->user?->username ?? 'Unknown' }}</span>
                                            <span class="text-gray-700 ml-1">{{ $comment->message }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1 ml-2">{{ $comment->createdAt->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form Komentar -->
                    @if($isMember)
                    <form action="{{ route('stream.comment', $post->id) }}" method="POST" class="mt-3 flex gap-2">
                        @csrf
                        <input type="text" name="message" placeholder="Tulis komentar..." required
                               class="flex-1 rounded-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-4 py-2 transition-all outline-none">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shrink-0 transition-colors flex items-center justify-center w-10 h-10 shadow-sm">
                            <svg class="w-4 h-4 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-20 px-4 flex flex-col items-center justify-center text-center">
                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <p class="text-gray-500 font-bold">Belum ada postingan</p>
                    <p class="text-sm text-gray-400 mt-1">Jadilah yang pertama memulai percakapan di grup ini!</p>
                </div>
            @endforelse
            </div>
        </div>

        <!-- KOLOM KANAN (Sidebar) -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Kotak Tentang Grup -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm tracking-wide uppercase">Tentang Grup</h3>
                </div>
                <div class="p-5">
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $group->description ?: 'Tidak ada deskripsi yang ditambahkan untuk grup ini.' }}</p>
                </div>
            </div>

            <!-- Kotak Anggota -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-sm tracking-wide uppercase">Anggota ({{ $group->members_count ?? $group->members()->count() }})</h3>
                    <a href="{{ route('groups.members', $group->id) }}" class="text-xs font-bold text-red-600 hover:text-red-800">Lihat Semua</a>
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-3">
                        @foreach($group->members->take(6) as $member)
                            <a href="#" class="block hover:opacity-80 transition-opacity">
                                <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($member->username ?? $member->name) . '&background=random&color=fff' }}" 
                                     alt="{{ $member->username ?? $member->name }}" 
                                     title="{{ $member->username ?? $member->name }}" 
                                     class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm bg-gray-100">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
