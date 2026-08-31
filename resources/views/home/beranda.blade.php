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
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">BAGI CEPAT</h3>

                <!-- Tabs -->
                <div class="flex space-x-6 mb-4 border-b border-neutral-100 pb-2">
                    <button type="button" onclick="switchTab('status')" id="tab-btn-status" class="flex items-center text-xs font-bold text-red-700 pb-2 border-b-2 border-red-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Status
                    </button>
                    <button type="button" onclick="switchTab('unggah')" id="tab-btn-unggah" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Unggah
                    </button>
                    <button type="button" onclick="switchTab('video')" id="tab-btn-video" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Video
                    </button>
                </div>

                <!-- Form Bagi Cepat -->
                <form action="{{ route('stream.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- TAB CONTENT: STATUS -->
                    <div id="tab-content-status">
                        <textarea name="message" rows="3" placeholder="What's happening..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-red-700 transition resize-none"></textarea>
                    </div>

                    <!-- TAB CONTENT: UNGGAH -->
                    <div id="tab-content-unggah" class="hidden bg-neutral-50 p-4 border border-neutral-200 rounded-lg space-y-4">
                        {{-- Album ID akan terhubung ke sistem album yang dibuat Ipan --}}
                        <input type="hidden" name="album_id" id="photo-album-id" value="0">

                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                            <label class="text-xs text-neutral-600 w-24">Pilih Foto:</label>
                            <input type="file" name="photo" id="photo-input-home" accept="image/*" onchange="previewPhotoHome(event)" class="flex-1 text-sm text-neutral-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                        
                        <div class="flex justify-end items-center space-x-2 mt-2 pt-2 border-t border-neutral-200">
                            <label class="text-xs text-neutral-600">Privasi:</label>
                            <select name="privacy" class="bg-white border border-neutral-300 rounded px-2 py-1 text-xs focus:outline-none focus:border-red-700">
                                <option value="public">Siapapun</option>
                                <option value="friends">Teman</option>
                                <option value="private">Hanya Saya</option>
                            </select>
                        </div>
                    </div>

                    <!-- TAB CONTENT: VIDEO -->
                    <div id="tab-content-video" class="hidden bg-neutral-50 p-4 border border-neutral-200 rounded-lg space-y-4">
                        <div class="text-xs font-medium text-red-600 mb-2">please insert a video URL</div>
                        {{-- Album video akan terhubung ke sistem album yang dibuat Ipan --}}
                        <input type="hidden" name="video_album_id" id="video-album-id" value="0">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <label class="text-xs text-neutral-600 w-24">Judul Video:</label>
                            <input type="text" name="video_title" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-start space-y-2 sm:space-y-0 sm:space-x-4">
                            <label class="text-xs text-neutral-600 w-24 mt-1">Deskripsi:</label>
                            <textarea name="video_desc" rows="2" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700 resize-none"></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                            <label class="text-xs text-neutral-600 w-24">tanda:</label>
                            <input type="text" name="video_tags" placeholder="(Dipisahkan dengan koma)" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                            <label class="text-xs text-neutral-600 w-24">Sumber video:</label>
                            <input type="text" name="video_url" placeholder="http://www.youtube.com/watch?v=" class="flex-1 bg-white border border-neutral-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-700">
                        </div>
                        
                        <div class="flex justify-end items-center space-x-2 mt-2 pt-2 border-t border-neutral-200">
                            <label class="text-xs text-neutral-600">Privasi:</label>
                            <select name="video_privacy" class="bg-white border border-neutral-300 rounded px-2 py-1 text-xs focus:outline-none focus:border-red-700">
                                <option value="public">Siapapun</option>
                                <option value="friends">Teman</option>
                                <option value="private">Hanya Saya</option>
                            </select>
                        </div>
                    </div>

                    <div id="photo-preview-container-home" class="hidden mt-3 relative inline-block">
                        <img id="photo-preview-home" src="#" class="h-20 rounded-md border border-neutral-200 object-cover">
                        <button type="button" onclick="clearPhotoHome()" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    


                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-[#990000] text-white font-bold px-6 py-2 rounded shadow-sm text-sm hover:bg-red-800 transition">Bagikan</button>
                    </div>
                </form>
            </div>

            <!-- Feed Berita -->
            <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mt-8 mb-4">FEED BERITA</h3>

            @forelse ($streams as $stream)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden flex-shrink-0 border border-neutral-200">
                            <img src="{{ $stream->user->avatar ? asset('storage/avatars/'.$stream->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($stream->user->fullname ?? 'Unknown').'&background=E5E5E5' }}" class="w-full h-full object-cover">
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
                        @if(isset($att['photo']))
                        <div class="mb-4 ml-13 rounded-xl overflow-hidden border border-neutral-200">
                            <img src="{{ asset('storage/posts/' . $att['photo']) }}" class="w-full h-auto" alt="Post Photo">
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
                                        <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ $comment->user->username ?? 'user' }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex space-x-2 form-comment" data-stream-id="{{ $stream->id }}">
                            @csrf
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                <img src="{{ auth()->user()->avatar ? asset('storage/avatars/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->fullname).'&background=E5E5E5' }}" class="w-full h-full">
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
            <!-- Widget Review -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 text-center">
                <h4 class="text-xs font-bold text-neutral-600 mb-2">Google Reviews</h4>
                <div class="flex justify-center text-yellow-400 mb-1">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div class="text-3xl font-extrabold text-neutral-800 my-1">4.9</div>
                <a href="#" class="text-xs text-blue-600 hover:underline">532 Reviews</a>
            </div>

            <!-- Network Links -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <h3 class="text-xs font-bold text-neutral-800 uppercase mb-4">Network Links</h3>
                <div class="space-y-2">
                    <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                        <div class="flex items-center text-sm font-medium text-neutral-600">
                            <!-- LinkedIn SVG -->
                            <img src="{{ asset('assets/img/logo-linkedin.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> LinkedIn
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                        <div class="flex items-center text-sm font-medium text-neutral-600">
                            <!-- phpBB SVG -->
                            <img src="{{ asset('assets/img/logo-phpbb.png') }}" class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100 transition"> phpBB Group
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-neutral-50 border border-neutral-100 rounded-lg hover:bg-neutral-100 transition group">
                        <div class="flex items-center text-sm font-medium text-neutral-600">
                            <!-- Facebook SVG -->
                            <svg class="w-4 h-4 mr-3 text-neutral-400 group-hover:text-[#1877F2] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg> Facebook
                        </div>
                        <span class="text-blue-500 font-bold">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    // Sembunyikan semua konten tab
    document.getElementById('tab-content-status').classList.add('hidden');
    document.getElementById('tab-content-unggah').classList.add('hidden');
    document.getElementById('tab-content-video').classList.add('hidden');

    // Kembalikan gaya tombol tab ke default (abu-abu)
    const tabs = ['status', 'unggah', 'video'];
    tabs.forEach(t => {
        const btn = document.getElementById('tab-btn-' + t);
        btn.classList.remove('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
        btn.classList.add('text-neutral-500', 'font-medium');
    });

    // Tampilkan konten tab yang aktif
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');

    // Ubah gaya tombol tab yang aktif (merah dan bold)
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.remove('text-neutral-500', 'font-medium');
    activeBtn.classList.add('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
}

function previewPhotoHome(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview-home').src = e.target.result;
            document.getElementById('photo-preview-container-home').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function clearPhotoHome() {
    document.getElementById('photo-input-home').value = '';
    document.getElementById('photo-preview-container-home').classList.add('hidden');
    document.getElementById('photo-preview-home').src = '#';
}



// Function to handle Reply
function replyTo(streamId, username) {
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
@endsection
