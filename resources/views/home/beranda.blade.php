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
                        <textarea name="message" rows="3" placeholder="What's happening..." class="w-full bg-neutral-50 border border-neutral-200 rounded-2xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
                    </div>

                    <!-- TAB CONTENT: UNGGAH -->
                    <div id="tab-content-unggah" class="hidden mt-4">
                        <p id="photo-album-msg" class="text-[11px] text-green-600 mb-2 font-semibold hidden"></p>
                        
                        <!-- STEP 1: Form Utama (UNGGAH FOTO) -->
                        <div id="photo-album-select-mode">
                            <div class="space-y-5 bg-white p-1">
                                <!-- Foto Input -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Pilih Foto</label>
                                    <div class="relative group">
                                        <label for="photo-input-home" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-neutral-300 hover:border-red-400 rounded-2xl bg-neutral-50 hover:bg-red-50/30 transition cursor-pointer py-6 text-center">
                                            <svg class="w-8 h-8 text-neutral-400 group-hover:text-red-500 mb-2 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span class="text-sm font-semibold text-neutral-700 group-hover:text-red-600 transition">Klik untuk memilih foto</span>
                                            <span class="text-[11px] text-neutral-500 mt-1">PNG, JPG, GIF hingga 10MB</span>
                                            <input type="file" name="photos[]" id="photo-input-home" accept="image/*" onchange="previewPhotoHome(event)" class="hidden" multiple>
                                        </label>
                                    </div>
                                    <div id="photo-preview-container-home" class="hidden mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2"></div>
                                    <div class="mt-3 flex space-x-2">
                                        <button type="button" onclick="document.getElementById('photo-input-home').click()" class="inline-flex items-center bg-white border border-neutral-200 hover:border-red-200 hover:bg-red-50 text-neutral-700 hover:text-red-700 text-xs font-semibold px-4 py-2 rounded-full transition shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah foto lainnya
                                        </button>
                                        <button type="button" id="btn-clear-photo" onclick="clearPhotoHome()" class="hidden inline-flex items-center bg-white border border-neutral-200 hover:border-red-200 hover:bg-red-50 text-neutral-700 hover:text-red-700 text-xs font-semibold px-4 py-2 rounded-full transition shadow-sm">
                                            Hapus Semua
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- AREA ALBUM -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Pilih Album</label>
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                                        <div class="relative w-full sm:w-56">
                                            <select name="album_id" id="photo-album-select" class="appearance-none w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 pr-8 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition shadow-sm cursor-pointer">
                                                <option value="0">-- Pilih Album --</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        <span class="text-xs text-neutral-400 font-medium hidden sm:inline">atau</span>
                                        <button type="button" onclick="toggleAlbumMode('photos', 'create')" class="inline-flex justify-center items-center bg-white border border-neutral-200 hover:border-red-200 hover:bg-red-50 text-neutral-700 hover:text-red-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm w-full sm:w-auto">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Buat album baru
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Deskripsi Foto -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Deskripsi Foto</label>
                                    <textarea name="message" rows="4" placeholder="Ceritakan momen di balik foto ini..." class="w-full bg-neutral-50 border border-neutral-200 rounded-2xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Next Form (Buat Album) -->
                        <div id="photo-album-create-mode" class="hidden">
                            <div class="bg-white border border-neutral-200 shadow-sm rounded-2xl p-6 mt-2">
                                <div class="flex items-center mb-5 border-b border-neutral-100 pb-3">
                                    <div class="bg-red-50 text-red-600 p-2 rounded-lg mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-wide">Buat Album Foto Baru</h4>
                                </div>
                                <div class="space-y-5 max-w-md">
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Nama Album</label>
                                        <input type="text" id="photo-album-new-name" placeholder="Masukkan nama album..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Deskripsi Album</label>
                                        <textarea id="photo-album-new-desc" rows="3" placeholder="Tuliskan deskripsi album..." class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Privasi</label>
                                        <div class="relative">
                                            <select id="photo-album-new-privacy" class="appearance-none w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 pr-8 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition cursor-pointer">
                                                <option value="public">Siapapun (Publik)</option>
                                                <option value="friends">Hanya Teman</option>
                                                <option value="private">Hanya Saya</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-3 pt-3">
                                        <button type="button" onclick="toggleAlbumMode('photos', 'select')" class="flex-1 bg-white border border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 text-neutral-700 font-bold text-xs px-4 py-3 rounded-xl transition shadow-sm">Kembali</button>
                                        <button type="button" onclick="saveNewAlbum('photos')" class="flex-1 bg-[#990000] hover:bg-red-800 text-white font-bold text-xs px-4 py-3 rounded-xl transition shadow-md">Simpan Album</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB CONTENT: VIDEO -->
                    <div id="tab-content-video" class="hidden mt-4">
                        <div class="text-[11px] font-medium text-red-600 mb-3">please insert a video URL</div>
                        <p id="video-album-msg" class="text-[11px] text-green-600 mb-2 font-semibold hidden"></p>
                        
                        <!-- STEP 1: Form Utama (VIDEO) -->
                        <div id="video-album-select-mode">
                            <div class="space-y-5 bg-white p-1">
                                <!-- Sumber Video -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Tautan Video</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        </div>
                                        <input type="text" name="video_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl pl-10 pr-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Judul Video -->
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Judul Video</label>
                                        <input type="text" name="video_title" placeholder="Ketik judul menarik..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                    </div>
                                    
                                    <!-- Tanda (Tags) -->
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Tags (Tanda)</label>
                                        <input type="text" name="video_tags" placeholder="Musik, VLOG, Liburan..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                    </div>
                                </div>
                                
                                <!-- AREA ALBUM -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Pilih Album Video</label>
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                                        <div class="relative w-full sm:w-56">
                                            <select name="video_album_id" id="video-album-select" class="appearance-none w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 pr-8 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition shadow-sm cursor-pointer">
                                                <option value="0">-- Pilih Album --</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        <span class="text-xs text-neutral-400 font-medium hidden sm:inline">atau</span>
                                        <button type="button" onclick="toggleAlbumMode('videos', 'create')" class="inline-flex justify-center items-center bg-white border border-neutral-200 hover:border-red-200 hover:bg-red-50 text-neutral-700 hover:text-red-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm w-full sm:w-auto">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Buat album baru
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Deskripsi Video -->
                                <div>
                                    <label class="text-xs font-bold text-neutral-700 block mb-2">Deskripsi Singkat</label>
                                    <textarea name="video_desc" rows="4" placeholder="Ceritakan sedikit tentang video ini..." class="w-full bg-neutral-50 border border-neutral-200 rounded-2xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Next Form (Buat Album Video) -->
                        <div id="video-album-create-mode" class="hidden">
                            <div class="bg-white border border-neutral-200 shadow-sm rounded-2xl p-6 mt-2">
                                <div class="flex items-center mb-5 border-b border-neutral-100 pb-3">
                                    <div class="bg-red-50 text-red-600 p-2 rounded-lg mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-wide">Buat Album Video Baru</h4>
                                </div>
                                <div class="space-y-5 max-w-md">
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Nama Album</label>
                                        <input type="text" id="video-album-new-name" placeholder="Masukkan nama album..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Deskripsi Album</label>
                                        <textarea id="video-album-new-desc" rows="3" placeholder="Tuliskan deskripsi album..." class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-neutral-700 block mb-2">Privasi</label>
                                        <div class="relative">
                                            <select id="video-album-new-privacy" class="appearance-none w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 pr-8 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition cursor-pointer">
                                                <option value="public">Siapapun (Publik)</option>
                                                <option value="friends">Hanya Teman</option>
                                                <option value="private">Hanya Saya</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-neutral-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-3 pt-3">
                                        <button type="button" onclick="toggleAlbumMode('videos', 'select')" class="flex-1 bg-white border border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 text-neutral-700 font-bold text-xs px-4 py-3 rounded-xl transition shadow-sm">Kembali</button>
                                        <button type="button" onclick="saveNewAlbum('videos')" class="flex-1 bg-[#990000] hover:bg-red-800 text-white font-bold text-xs px-4 py-3 rounded-xl transition shadow-md">Simpan Album</button>
                                    </div>
                                </div>
                            </div>
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

<!-- LIGHTBOX MODAL -->
<div id="lightbox-modal" class="fixed inset-0 z-[100] hidden bg-black/95 flex flex-col justify-center items-center backdrop-blur-sm">
    <!-- Close -->
    <button type="button" onclick="closeLightbox()" class="absolute top-5 right-5 text-neutral-400 hover:text-white transition cursor-pointer p-2 bg-neutral-900/50 rounded-full">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <!-- Prev -->
    <button type="button" onclick="prevLightboxImage()" class="absolute left-4 sm:left-10 text-neutral-400 hover:text-white transition p-3 bg-neutral-900/50 rounded-full hover:scale-110">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    
    <!-- Next -->
    <button type="button" onclick="nextLightboxImage()" class="absolute right-4 sm:right-10 text-neutral-400 hover:text-white transition p-3 bg-neutral-900/50 rounded-full hover:scale-110">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
    
    <!-- Main Image Container -->
    <div class="relative max-w-5xl w-full px-16 flex justify-center items-center h-[85vh]">
        <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain transition-opacity duration-200 shadow-2xl rounded-sm opacity-0">
    </div>
    
    <!-- Counter -->
    <div id="lightbox-counter" class="absolute bottom-6 bg-black/50 px-4 py-1.5 rounded-full text-white text-xs font-semibold tracking-wider"></div>
</div>

<script>
    // Lightbox Logic
    let lightboxImages = [];
    let lightboxCurrentIndex = 0;

    window.openLightbox = function(images, index) {
        lightboxImages = images;
        lightboxCurrentIndex = index;
        updateLightbox();
        document.getElementById('lightbox-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // cegah scroll background
    }
    window.closeLightbox = function() {
        document.getElementById('lightbox-modal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // kembalikan scroll
    }
    window.prevLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex > 0) ? lightboxCurrentIndex - 1 : lightboxImages.length - 1;
        updateLightbox();
    }
    window.nextLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex < lightboxImages.length - 1) ? lightboxCurrentIndex + 1 : 0;
        updateLightbox();
    }
    window.updateLightbox = function() {
        const img = document.getElementById('lightbox-img');
        img.style.opacity = '0'; // fade out
        setTimeout(() => {
            img.src = lightboxImages[lightboxCurrentIndex];
            img.style.opacity = '1'; // fade in
            
            const counter = document.getElementById('lightbox-counter');
            if (lightboxImages.length > 1) {
                counter.textContent = (lightboxCurrentIndex + 1) + ' / ' + lightboxImages.length;
                counter.classList.remove('hidden');
            } else {
                counter.classList.add('hidden'); // Sembunyikan counter kalau cuma 1 foto
            }
        }, 150);
    }

    // Keyboard support (Escape, Left, Right arrow)
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightbox-modal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevLightboxImage();
            if (e.key === 'ArrowRight') nextLightboxImage();
        }
    });

function switchTab(tabName) {
    const tabs = ['status', 'unggah', 'video'];
    
    tabs.forEach(t => {
        // Sembunyikan konten tab
        const content = document.getElementById('tab-content-' + t);
        content.classList.add('hidden');
        
        // Nonaktifkan input agar tidak menimpa name yang sama saat disubmit
        const inputs = content.querySelectorAll('input, textarea, select');
        inputs.forEach(input => input.disabled = true);

        // Kembalikan gaya tombol tab ke default (abu-abu)
        const btn = document.getElementById('tab-btn-' + t);
        btn.classList.remove('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
        btn.classList.add('text-neutral-500', 'font-medium');
    });

    // Tampilkan konten tab yang aktif
    const activeContent = document.getElementById('tab-content-' + tabName);
    activeContent.classList.remove('hidden');
    
    // Aktifkan kembali input di tab yang aktif
    const activeInputs = activeContent.querySelectorAll('input, textarea, select');
    activeInputs.forEach(input => input.disabled = false);

    // Ubah gaya tombol tab yang aktif (merah dan bold)
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.remove('text-neutral-500', 'font-medium');
    activeBtn.classList.add('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
}

// Inisialisasi tab saat halaman pertama dimuat
document.addEventListener('DOMContentLoaded', function() {
    switchTab('status');
});

function previewPhotoHome(event) {
    const input = event.target;
    const container = document.getElementById('photo-preview-container-home');
    const clearBtn = document.getElementById('btn-clear-photo');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        container.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
        
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover rounded-xl border border-neutral-200 shadow-sm';
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}
function clearPhotoHome() {
    document.getElementById('photo-input-home').value = '';
    const container = document.getElementById('photo-preview-container-home');
    container.innerHTML = '';
    container.classList.add('hidden');
    document.getElementById('btn-clear-photo').classList.add('hidden');
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
    // ==========================================
    // ALBUM MANAGEMENT (Fetch, Toggle Mode, Create)
    // ==========================================

    function fetchAlbums(type, selectId) {
        fetch(`/api/albums?type=${type}`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="0">-- Pilih Album --</option>';
                data.forEach(album => {
                    const option = document.createElement('option');
                    option.value = album.id;
                    option.textContent = album.name;
                    select.appendChild(option);
                });
            });
    }

    // Load album saat pertama kali
    fetchAlbums('photos', 'photo-album-select');
    fetchAlbums('videos', 'video-album-select');

    // Fungsi toggle mode (Pilih <-> Buat Baru)
    window.toggleAlbumMode = function(type, mode) {
        const prefix = type === 'photos' ? 'photo' : 'video';
        const selectMode = document.getElementById(`${prefix}-album-select-mode`);
        const createMode = document.getElementById(`${prefix}-album-create-mode`);
        
        if (mode === 'create') {
            selectMode.classList.add('hidden');
            createMode.classList.remove('hidden');
            document.getElementById(`${prefix}-album-new-name`).focus();
        } else {
            createMode.classList.add('hidden');
            selectMode.classList.remove('hidden');
            document.getElementById(`${prefix}-album-new-name`).value = '';
            document.getElementById(`${prefix}-album-new-desc`).value = '';
            document.getElementById(`${prefix}-album-new-privacy`).value = 'public';
        }
    }

    // Fungsi simpan album baru via AJAX
    window.saveNewAlbum = function(type) {
        const prefix = type === 'photos' ? 'photo' : 'video';
        const inputName = document.getElementById(`${prefix}-album-new-name`);
        const inputDesc = document.getElementById(`${prefix}-album-new-desc`);
        const inputPrivacy = document.getElementById(`${prefix}-album-new-privacy`);
        const msgEl = document.getElementById(`${prefix}-album-msg`);

        const valName = inputName.value.trim();
        if(!valName) {
            msgEl.textContent = "Nama album tidak boleh kosong!";
            msgEl.classList.remove('hidden', 'text-green-600');
            msgEl.classList.add('text-red-600');
            return;
        }

        const formData = new FormData();
        formData.append('name', valName);
        formData.append('description', inputDesc.value.trim());
        formData.append('privacy', inputPrivacy.value);
        formData.append('type', type);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("album.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Tampilkan sukses
                msgEl.textContent = `Album '${data.album.name}' berhasil dibuat!`;
                msgEl.classList.remove('hidden', 'text-red-600');
                msgEl.classList.add('text-green-600');
                
                // Fetch ulang dropdown & kembali ke mode select
                fetchAlbums(type, `${prefix}-album-select`);
                
                setTimeout(() => {
                    toggleAlbumMode(type, 'select');
                    msgEl.classList.add('hidden');
                    // Pilih album yang baru dibuat (opsional, karena fetchAlbums asinkron)
                    setTimeout(() => {
                        const select = document.getElementById(`${prefix}-album-select`);
                        if(select) select.value = data.album.id;
                    }, 500);
                }, 1500);
            } else {
                msgEl.textContent = "Gagal membuat album.";
                msgEl.classList.remove('hidden', 'text-green-600');
                msgEl.classList.add('text-red-600');
            }
        });
    }

});
</script>
@endsection
