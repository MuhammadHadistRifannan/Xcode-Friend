@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-[95%] lg:max-w-5xl mx-auto w-full">
    <!-- Header Page -->
    <h2 class="text-xs font-bold text-neutral-800 uppercase tracking-widest mb-6">DASHBOARD</h2>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- KOLOM KIRI: Menu Navigasi Samping -->
        <div class="lg:col-span-3 space-y-6">

            <!-- My Apps Block -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">MY APPS</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">FOTO</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">VIDEO</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">UNDANG</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-center">DESAIN PROFIL</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">MY PAGES</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 rounded-lg hover:bg-neutral-50 text-neutral-500 hover:text-red-700 transition group">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">GROUPS</span>
                    </a>
                </div>
            </div>

            <!-- Profile Info Menu -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 py-3">
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
                    <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-sm font-semibold text-neutral-700">Profilku</span>
                </a>
                <a href="#" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="text-sm font-semibold text-neutral-700">Pengikutku</span>
                    </div>
                    <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followerCount }}</span>
                </a>
                <a href="#" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        <span class="text-sm font-semibold text-neutral-700">Yang aku ikuti</span>
                    </div>
                    <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followingCount }}</span>
                </a>
                <a href="#" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition group">
                    <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-sm font-semibold text-neutral-700">Opsi</span>
                </a>
            </div>

            <!-- Media X-CODE (Hardcoded sesuai desain) -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">MEDIA X-CODE</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg> Forum X-code
                    </a>
                    <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> X-code Training
                    </a>
                    <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> Toko X-code
                    </a>
                    <a href="#" class="flex items-center text-sm font-medium text-red-700 hover:underline hover:translate-x-1 transition transform duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Kumpulan tulisan
                    </a>
                </div>
            </div>
        </div>

        <!-- KOLOM TENGAH: BAGI CEPAT & FEED BERITA -->
        <div class="lg:col-span-6 space-y-6">

            <!-- Buat Post (Bagi Cepat) -->
            <x-feed-upload action="{{ route('stream.store') }}" app="feed" aid="0" wallId="0" />

            <!-- Feed Berita -->
            <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mt-8 mb-4">FEED BERITA</h3>

            @forelse ($streams as $stream)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5" x-data="{ editingStream: false, openOptions: false }">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-3">
                        <a href="/@{{ $stream->user->username ?? '#' }}" class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden flex-shrink-0 border border-neutral-200 hover:ring-2 hover:ring-red-700 transition">
                            <img src="{{ $stream->user->avatar_url }}" class="w-full h-full object-cover">
                        </a>
                        <div>
                            <h4 class="text-sm font-bold text-neutral-900">
                                <a href="/@{{ $stream->user->username ?? '#' }}" class="hover:text-red-700 transition">{{ $stream->user->fullname ?? 'Unknown User' }}</a> 
                                @if($stream->type == 1 && !$stream->attachment)
                                <span class="font-normal text-neutral-500">memperbarui status</span>
                                @elseif($stream->type == 2)
                                <span class="font-normal text-neutral-500">mengunggah foto</span>
                                @elseif($stream->type == 3)
                                <span class="font-normal text-neutral-500">membagikan video</span>
                                @else
                                <span class="font-normal text-neutral-500">memposting</span>
                                @endif
                            </h4>
                            <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                        </div>
                        
                        <!-- 3-dots dropdown -->
                        <div class="relative">
                            <button @click="openOptions = !openOptions" @click.away="openOptions = false" class="text-neutral-400 hover:text-neutral-600 focus:outline-none mt-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                            </button>
                            <div x-show="openOptions" style="display: none;" class="absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg border border-neutral-100 z-50 overflow-hidden">
                                @if(auth()->check() && (auth()->id() === $stream->uid))
                                    <button @click="openOptions = false; editingStream = true" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition">Edit</button>
                                    <form action="{{ route('stream.destroy', $stream->id) }}" method="POST" class="block w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Hapus</button>
                                    </form>
                                    <button type="button" @click="openOptions = false; openReportModal('{{ url('/stream/'.$stream->id) }}')" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition border-t border-neutral-100">Lapor</button>
                                @else
                                    <button type="button" @click="openOptions = false; openReportModal('{{ url('/stream/'.$stream->id) }}')" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition">Lapor</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($stream->message)
                        <p x-show="!editingStream" class="text-sm text-neutral-800 mb-4 ml-13 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>
                        <form x-show="editingStream" style="display: none;" action="{{ route('stream.update', $stream->id) }}" method="POST" class="mb-4 ml-13">
                            @csrf
                            @method('PUT')
                            <textarea name="message" rows="3" class="w-full text-sm p-3 border border-neutral-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-2">{{ $stream->message }}</textarea>
                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="editingStream = false" class="px-3 py-1.5 text-xs font-semibold text-neutral-600 hover:bg-neutral-100 rounded-md transition">Batal</button>
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md transition">Simpan</button>
                            </div>
                        </form>
                    @else
                        <div x-show="editingStream" style="display: none;" class="mb-4 ml-13">
                            <form action="{{ route('stream.update', $stream->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="message" rows="3" class="w-full text-sm p-3 border border-neutral-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-2" placeholder="Tambahkan caption..."></textarea>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" @click="editingStream = false" class="px-3 py-1.5 text-xs font-semibold text-neutral-600 hover:bg-neutral-100 rounded-md transition">Batal</button>
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md transition">Simpan</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if($stream->type == 2 && $stream->attachment)
                        @php $att = json_decode($stream->attachment, true); @endphp
                        @if(isset($att['photos']) && is_array($att['photos']))
                            @php 
                                $ptCount = count($att['photos']); 
                                $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
                                $jsonPhotos = json_encode($photoUrls);
                            @endphp
                            <div class="mb-4 ml-13 rounded-xl overflow-hidden border border-neutral-200">
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
                                    <div class="grid grid-cols-2 grid-rows-2 gap-1 h-72 sm:h-96">
                                        <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
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
                                @endif
                            </div>
                        @elseif(isset($att['photo']))
                            <div class="mb-4 ml-13 rounded-xl overflow-hidden border border-neutral-200">
                                @php $singlePhoto = json_encode([asset('storage/posts/' . $att['photo'])]); @endphp
                                <img src="{{ asset('storage/posts/' . $att['photo']) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
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
                            <div class="mb-4 ml-13 rounded-xl overflow-hidden border border-neutral-200">
                                @if($embedUrl)
                                    <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                    <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Tonton Video</a>
                                @endif
                            </div>
                        @endif
                    @endif

                    <div class="flex items-center space-x-4 ml-13 mt-3">
                        <button type="button" onclick="document.getElementById('comment-form-home-{{ $stream->id }}').classList.toggle('hidden')" class="flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> <span id="comments-count-{{ $stream->id }}">{{ $stream->comments->count() ?? 0 }}</span>&nbsp;Komentar
                        </button>
                        <form action="{{ route('like.toggle', $stream->id) }}" method="POST" class="form-like">
                            @csrf
                            <button type="submit" class="flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> <span id="like-count-{{ $stream->id }}">{{ $stream->likes }}</span>&nbsp;Suka
                            </button>
                        </form>
                    </div>

                    <!-- Comment Section -->
                    <div id="comment-form-home-{{ $stream->id }}" class="hidden mt-3 pt-3 border-t border-neutral-100 ml-13">
                        <div id="comments-list-{{ $stream->id }}" class="bg-neutral-50 rounded-lg p-4 mb-3 space-y-3 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-neutral-200">
                            @foreach($stream->comments as $comment)
                                <div class="flex gap-2" id="comment-{{ $comment->id }}">
                                    <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0 border border-neutral-200">
                                        <img src="{{ $comment->user->avatar_url }}" class="w-full h-full object-cover">
                                    </div>
                                    <div x-data="{ editing: false, openCommentOptions: false }" class="flex-1">
                                        <div class="flex items-start gap-2 group">
                                            <!-- Comment Bubble -->
                                            <div x-show="!editing" class="bg-white px-3 py-2 rounded-2xl border border-neutral-100 shadow-sm text-sm break-words max-w-[85%]">
                                                <span class="font-bold text-neutral-900 mr-1">{{ $comment->user->fullname ?? 'Unknown' }}</span>
                                                @php 
                                                    $parsedMessage = htmlspecialchars($comment->message);
                                                    $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);
                                                @endphp
                                                <span class="text-neutral-700">{!! $parsedMessage !!}</span>
                                            </div>

                                            <!-- Form Edit Komentar (Placeholder for future or logic) -->
                                            <form x-show="editing" style="display: none;" action="{{ route('comment.update', $comment->id) }}" method="POST" class="flex gap-2 w-full max-w-sm">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="message" value="{{ $comment->message }}" class="flex-1 rounded-full bg-white border border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-3 py-1 outline-none" required>
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-3 py-1 text-xs font-bold transition-colors">Simpan</button>
                                                <button type="button" @click="editing = false" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-600 rounded-full px-3 py-1 text-xs font-bold transition-colors">Batal</button>
                                            </form>

                                            <!-- Options Dropdown -->
                                            <div class="relative mt-1" x-show="!editing">
                                                <button @click="openCommentOptions = !openCommentOptions" @click.away="openCommentOptions = false" type="button" class="text-neutral-400 hover:text-neutral-600 transition p-1 rounded-full hover:bg-neutral-200 focus:outline-none" title="Opsi Komentar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>
                                                
                                                <div x-show="openCommentOptions" style="display: none;"
                                                     class="absolute left-0 top-6 mt-1 w-24 bg-white border border-neutral-200 rounded-md shadow-lg z-20 py-1">
                                                    
                                                    @if(auth()->check() && auth()->id() === $comment->uid)
                                                    <button type="button" @click="editing = true; openCommentOptions = false" class="block px-3 py-1.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 w-full text-left">Edit</button>
                                                    @endif
                                                    
                                                    @if(auth()->check() && (auth()->id() === $comment->uid))
                                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus komentar ini?')" class="block px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 w-full text-left">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <button type="button" onclick="openReportModal('{{ url('/beranda#' . $comment->id) }}')" class="block px-3 py-1.5 text-xs text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                                        Laporkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bawah Bubble -->
                                        <div class="flex items-center gap-3 mt-1 ml-2">
                                            <p class="text-[10px] text-neutral-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Baru saja' }}</p>
                                            @if(auth()->check())
                                            <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ addslashes($comment->user->fullname ?? $comment->user->username ?? 'user') }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 transition-colors uppercase tracking-wider">Balas</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex gap-2 form-comment" data-stream-id="{{ $stream->id }}">
                            @csrf
                            <input type="text" name="message" id="comment-input-{{ $stream->id }}" required placeholder="Tulis komentar..." class="flex-1 rounded-full bg-neutral-50 border border-neutral-200 focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-4 py-2 transition-all outline-none">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shrink-0 transition-colors flex items-center justify-center w-9 h-9 shadow-sm">
                                <svg class="w-4 h-4 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Tidak ada feed berita terbaru.</div>
            @endforelse

            <div class="mt-4">{{ $streams->links() }}</div>

            <!-- Custom Image Bawah (Sesuai Desain) -->
            <div class="w-full h-72 bg-neutral-200 rounded-xl overflow-hidden shadow-sm mt-6">
                <img src="{{ asset('assets/img/hero-banner.jpg') }}" alt="Community" class="w-full h-full object-cover">
            </div>

        </div>

        <!-- KOLOM KANAN: Review & Links -->
        <div class="lg:col-span-3 space-y-6">
            <x-sidebar-right />
        </div>

    </div>
</div>

@include('components.lightbox')

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
            <input type="hidden" name="url" id="reportUrl" value="">
            <div class="p-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pelaporan</label>
                <textarea name="message" rows="4" class="w-full text-sm p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 resize-none mb-3" placeholder="Jelaskan mengapa konten ini tidak pantas..." required></textarea>
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

@include('components.feed-scripts')
@endsection
