@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-[95%] mx-auto w-full">
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
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden flex-shrink-0 border border-neutral-200">
                            <img src="{{ $stream->user->avatar ? asset('storage/avatars/'.$stream->user->avatar) : asset('assets/img/default.png') }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-neutral-900">{{ $stream->user->fullname ?? 'Unknown User' }} <span class="font-normal text-neutral-500">joined</span></h4>
                            <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($stream->message)
                        <p class="text-sm text-neutral-800 mb-4 ml-13">{{ $stream->message }}</p>
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
                        
                        <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex space-x-2 form-comment" data-stream-id="{{ $stream->id }}">
                            @csrf
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : asset('assets/img/default.png') }}" class="w-full h-full">
                            </div>
                            <input type="text" name="message" id="comment-input-{{ $stream->id }}" required placeholder="Tulis komentar..." class="flex-1 bg-neutral-50 border border-neutral-200 rounded-full px-4 text-sm focus:outline-none focus:border-red-700">
                            <button type="submit" class="text-red-700 font-bold text-sm px-2">Kirim</button>
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

@include('components.feed-scripts')
@endsection
