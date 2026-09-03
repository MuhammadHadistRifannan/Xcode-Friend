@extends('layouts.app')

@section('content')
{{-- Penting: hide x-cloak by default --}}
<style>[x-cloak] { display: none !important; }</style>
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             x-transition.duration.500ms
             class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition.duration.500ms
             class="fixed bottom-4 right-4 z-50 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex flex-col gap-1">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Ada kesalahan:</span>
            </div>
            <ul class="list-disc pl-8 font-normal text-xs mt-1">
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
                                    <a href="{{ route('groups.invite', $group->id) }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                        Undang Pengguna
                                    </a>
                                    <a href="{{ route('groups.reports', $group->id) }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Laporan Grup
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
                        <div class="flex flex-wrap gap-2 w-full md:w-auto">
                            <form action="{{ route('groups.leave', $group->id) }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin keluar dari grup ini?')" class="inline-flex w-full justify-center items-center gap-2 bg-gray-100 text-gray-700 border border-gray-300 hover:bg-red-600 hover:text-white hover:border-red-600 px-6 py-2.5 rounded-lg font-bold transition-all shadow-sm text-sm group focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar dari Grup
                                </button>
                            </form>
                            <button type="button" onclick="openReportModal('{{ url('groups/' . $group->id) }}')" class="inline-flex w-full md:w-auto justify-center items-center gap-2 bg-yellow-100 text-yellow-700 border border-yellow-300 hover:bg-yellow-600 hover:text-white hover:border-yellow-600 px-6 py-2.5 rounded-lg font-bold transition-all shadow-sm text-sm group focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <svg class="w-4 h-4 text-yellow-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Laporkan
                            </button>
                        </div>
                    @else
                        @if($isPending)
                        <button disabled class="inline-flex w-full justify-center items-center gap-2 bg-gray-400 text-white border border-transparent px-8 py-2.5 rounded-lg font-bold shadow-sm text-sm cursor-not-allowed">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Menunggu Persetujuan
                        </button>
                    @else
                        <form action="{{ url('groups/' . $group->id . '/join') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center items-center gap-2 bg-blue-600 text-white border border-transparent hover:bg-blue-700 px-8 py-2.5 rounded-lg font-bold transition-colors shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Gabung Grup
                            </button>
                        </form>
                    @endif
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
            <x-feed-upload action="{{ route('groups.stream', $group->id) }}" app="group" aid="{{ $group->id }}" wallId="{{ $group->id }}" />
            @endif
            @if(isset($filter) && in_array($filter, ['photo', 'video']))
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3 bg-red-50 text-red-800 px-4 py-3 rounded-xl border border-red-100 shadow-sm">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Menampilkan unggahan dengan {{ $filter == 'photo' ? 'Foto' : 'Video' }} saja.
                    </span>
                    <a href="{{ route('groups.show', $group->id) }}" class="text-xs font-bold bg-white text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors shadow-sm">
                        Lihat Semua Postingan
                    </a>
                </div>
            @endif

            <!-- Feed Postingan / Empty State -->
            <div class="max-h-[600px] overflow-y-auto pr-2 pb-4 space-y-5 scrollbar-thin scrollbar-thumb-gray-200">
                @forelse($group->streams as $post)
                    <div id="post-{{ $post->id }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <!-- Post Header: Avatar + Nama + Waktu -->
                    <div class="flex items-start justify-between mb-3 relative" x-data="{ openOptions: false }">
                        <div class="flex items-start gap-3">
                            <img src="{{ $post->user->avatar_url }}"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0" alt="Avatar">
                            <div>
                                <span class="font-bold text-gray-900 text-sm">{{ $post->user?->fullname ?? $post->user?->username ?? 'Unknown User' }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::createFromTimestamp($post->created)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Tombol Opsi Postingan (Hanya untuk Admin Grup atau Pemilik Postingan) -->
                        <!-- Tombol Opsi Postingan -->
                        <div>
                            <button @click="openOptions = !openOptions" @click.away="openOptions = false" type="button" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100 focus:outline-none" title="Opsi Postingan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openOptions" style="display: none;"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 top-8 mt-1 w-36 bg-white border border-gray-200 rounded-md shadow-lg z-10 py-1">
                                
                                @if(Auth::id() === $group->uid || Auth::id() === $post->uid)
                                    @if(Auth::id() === $post->uid)
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 w-full text-left">Edit</a>
                                    @endif
                                    <form action="{{ route('stream.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus postingan ini?')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                                
                                <button type="button" onclick="openReportModal('{{ url('groups/' . $group->id . '#post-' . $post->id) }}')" class="block px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                    Laporkan
                                </button>
                            </div>
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
                                <div class="flex gap-2" id="comment-{{ $comment->id }}">
                                    <img src="{{ $comment->user->avatar_url }}"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200" alt="Avatar">
                                    <div x-data="{ editing: false, openCommentOptions: false }">
                                        <div class="flex items-center gap-2 group">
                                            <!-- Tampilan Komentar Biasa -->
                                            <div x-show="!editing" class="bg-white px-3 py-2 rounded-2xl border border-gray-100 shadow-sm text-sm">
                                                <span class="font-bold text-gray-900">{{ $comment->user?->fullname ?? $comment->user?->username ?? 'Unknown' }}</span>
                                                <span class="text-gray-700 ml-1">{{ $comment->message }}</span>
                                            </div>

                                            <!-- Form Edit Komentar -->
                                            <form x-show="editing" style="display: none;" action="{{ route('comment.update', $comment->id) }}" method="POST" class="flex gap-2 w-full max-w-sm">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="message" value="{{ $comment->message }}" class="flex-1 rounded-full bg-white border border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-3 py-1 outline-none" required>
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-3 py-1 text-xs font-bold transition-colors">Simpan</button>
                                                <button type="button" @click="editing = false" class="bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full px-3 py-1 text-xs font-bold transition-colors">Batal</button>
                                            </form>

                                            <div class="relative" x-show="!editing">
                                                <button @click="openCommentOptions = !openCommentOptions" @click.away="openCommentOptions = false" type="button" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-200 focus:outline-none" title="Opsi Komentar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>
                                                
                                                <div x-show="openCommentOptions" style="display: none;"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute left-0 top-6 mt-1 w-24 bg-white border border-gray-200 rounded-md shadow-lg z-20 py-1">
                                                    
                                                    @if(Auth::id() === $comment->uid)
                                                    <button type="button" @click="editing = true; openCommentOptions = false" class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 hover:text-gray-900 w-full text-left">Edit</button>
                                                    @endif
                                                    
                                                    @if(Auth::id() === $comment->uid || Auth::id() === $group->uid)
                                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus komentar ini?')" class="block px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 w-full text-left">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <button type="button" onclick="openReportModal('{{ url('groups/' . $group->id . '#comment-' . $comment->id) }}')" class="block px-3 py-1.5 text-xs text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                                        Laporkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 ml-2">
                                            <p class="text-[10px] text-gray-400">{{ $comment->createdAt->diffForHumans() }}</p>
                                            @if($isMember)
                                            <button type="button" onclick="const inp = document.getElementById('comment-input-{{ $post->id }}'); if(inp) { inp.value = '@' + '{{ addslashes($comment->user?->fullname ?? $comment->user?->username ?? 'Unknown') }}' + ' '; inp.focus(); }" class="text-[10px] text-gray-500 font-semibold hover:text-red-600 transition-colors">Balas</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form Komentar -->
                    @if($isMember)
                    <form action="{{ route('stream.comment', $post->id) }}" method="POST" class="mt-3 flex gap-2">
                        @csrf
                        <input type="text" name="message" id="comment-input-{{ $post->id }}" placeholder="Tulis komentar..." required
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
                                <img src="{{ $member->avatar_url }}" 
                                     alt="{{ $member->fullname ?? $member->username ?? $member->name }}" 
                                     title="{{ $member->fullname ?? $member->username ?? $member->name }}" 
                                     class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm bg-gray-100">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kotak Media Grup -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ 
                mediaTab: 'photo',
                videoUrl: '', 
                videoId: null,
                photoUrl: null,
                openVideo(url) {
                    this.videoUrl = url;
                    let match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&?\/\s]{11})/);
                    if (match && match[1]) {
                        this.videoId = match[1];
                    }
                }
            }">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-sm tracking-wide uppercase">Media Grup</h3>
                </div>
                
                <!-- Tabs Navigation -->
                <div class="flex border-b border-gray-100">
                    <button type="button" @click="mediaTab = 'photo'" :class="{ 'border-red-600 text-red-600': mediaTab === 'photo', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mediaTab !== 'photo' }" class="flex-1 py-3 text-sm font-bold border-b-2 transition-colors">
                        Foto
                    </button>
                    <button type="button" @click="mediaTab = 'video'" :class="{ 'border-red-600 text-red-600': mediaTab === 'video', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mediaTab !== 'video' }" class="flex-1 py-3 text-sm font-bold border-b-2 transition-colors">
                        Vidio
                    </button>
                </div>

                <!-- Photos Grid -->
                <div x-show="mediaTab === 'photo'" class="p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($recentPhotos->count() > 0)
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach($recentPhotos as $photo)
                                <div @click="photoUrl = '{{ asset('storage/'.$photo->attachment) }}'" class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer">
                                    <img src="{{ asset('storage/'.$photo->attachment) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-xs text-gray-400 bg-gray-50 rounded-lg mb-4 border border-gray-100">
                            Belum ada foto
                        </div>
                    @endif
                    <a href="{{ route('groups.media', ['group' => $group->id, 'type' => 'photo']) }}" class="block w-full py-2 text-center text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors hover:text-red-700">
                        Lihat semuanya
                    </a>
                </div>

                <!-- Videos Grid -->
                <div x-show="mediaTab === 'video'" class="p-4" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($recentVideos->count() > 0)
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach($recentVideos as $video)
                                @php $ytId = str_replace('youtube:', '', $video->attachment); @endphp
                                <div @click="openVideo('https://www.youtube.com/watch?v={{ $ytId }}')" class="aspect-square bg-gray-100 rounded-md overflow-hidden block relative group border border-gray-200 cursor-pointer">
                                    <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 opacity-90 group-hover:opacity-100">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-black bg-opacity-60 rounded-full p-1.5 backdrop-blur-sm group-hover:bg-red-600 transition-colors">
                                            <i data-lucide="play" class="w-3 h-3 text-white fill-white ml-0.5"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-xs text-gray-400 bg-gray-50 rounded-lg mb-4 border border-gray-100">
                            Belum ada vidio
                        </div>
                    @endif
                    <a href="{{ route('groups.media', ['group' => $group->id, 'type' => 'video']) }}" class="block w-full py-2 text-center text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors hover:text-red-700">
                        Lihat semuanya
                    </a>
                </div>

                <!-- Video Player Modal (Alpine.js) -->
                <div x-show="videoId" 
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm p-4"
                    style="display: none;"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    
                    <div class="relative w-full max-w-4xl mx-auto rounded-xl overflow-hidden shadow-2xl bg-black" @click.away="videoId = null">
                        <button type="button" @click="videoId = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        
                        <div class="relative pt-[56.25%] w-full bg-black rounded-xl overflow-hidden">
                            <template x-if="videoId">
                                <iframe 
                                    class="absolute top-0 left-0 w-full h-full"
                                    :src="'https://www.youtube.com/embed/' + videoId + '?autoplay=1'" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Photo Viewer Modal (Alpine.js) -->
                <div x-show="photoUrl" 
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm p-4"
                    style="display: none;"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    
                    <div class="relative w-full h-full max-w-5xl max-h-[90vh] flex items-center justify-center" @click.away="photoUrl = null">
                        <button type="button" @click="photoUrl = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md z-10">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        
                        <img :src="photoUrl" class="w-full h-full object-contain rounded-lg shadow-2xl">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Laporkan Konten</h3>
            <button type="button" onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <input type="hidden" name="url" id="reportUrl" value="">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Laporan</label>
                    <textarea name="message" rows="4" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="Jelaskan alasan mengapa Anda melaporkan konten ini..."></textarea>
                </div>
                <p class="text-xs text-gray-500">Laporan Anda bersifat anonim dan akan ditinjau oleh administrator.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-red-700 shadow-sm transition-colors">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal(url) {
        document.getElementById('reportUrl').value = url;
        document.getElementById('reportModal').classList.remove('hidden');
    }
    
    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
    }
</script>

@include('components.lightbox')
@include('components.feed-scripts')
@endsection
