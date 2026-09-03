@props(['action', 'app' => 'feed', 'aid' => 0, 'wallId' => 0])
@php
    $photoAlbums = \App\Models\Album::where('app', 'photos')->where('gid', auth()->id())->get();
    $videoAlbums = \App\Models\Album::where('app', 'videos')->where('gid', auth()->id())->get();
@endphp
<div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
    <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mb-4">BAGI CEPAT</h3>

    <!-- Tabs -->
    <div class="flex space-x-6 mb-4 border-b border-neutral-100 pb-2">
        <button type="button" onclick="switchTab('status')" id="tab-btn-status" class="flex items-center text-xs font-bold text-red-700 pb-2 border-b-2 border-red-700 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Status
        </button>
        <button type="button" onclick="switchTab('unggah')" id="tab-btn-unggah" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Foto
        </button>
        <button type="button" onclick="switchTab('video')" id="tab-btn-video" class="flex items-center text-xs font-medium text-neutral-500 hover:text-red-700 pb-2 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Video
        </button>
    </div>

    <!-- Form Bagi Cepat -->
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="app" value="{{ $app }}">
        <input type="hidden" name="aid" value="{{ $aid }}">
        <input type="hidden" name="wall_id" value="{{ $wallId }}">
        
        <!-- TAB CONTENT: STATUS -->
        <div id="tab-content-status">
            <textarea name="message" rows="3" placeholder="What's happening..." class="w-full bg-neutral-50 border border-neutral-200 rounded-2xl px-4 py-3 text-sm text-neutral-700 placeholder-neutral-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
        </div>

        <!-- TAB CONTENT: UNGGAH -->
        <div id="tab-content-unggah" class="hidden mt-4">
            <p id="photo-album-msg" class="text-[11px] text-green-600 mb-2 font-semibold hidden"></p>
            
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
                                    @foreach($photoAlbums as $al)
                                        <option value="{{ $al->id }}">{{ $al->name }}</option>
                                    @endforeach
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
                            <input type="text" name="new_album_name" id="photo-album-new-name" placeholder="Masukkan nama album..." class="w-full bg-neutral-50 border border-neutral-200 text-neutral-700 text-sm rounded-xl px-4 py-2.5 shadow-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
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
                                    @foreach($videoAlbums as $al)
                                        <option value="{{ $al->id }}">{{ $al->name }}</option>
                                    @endforeach
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

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-[#990000] text-white font-bold px-6 py-2 rounded shadow-sm text-sm hover:bg-red-800 transition">Bagikan</button>
        </div>
    </form>
</div>
