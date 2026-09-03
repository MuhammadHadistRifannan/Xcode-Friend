@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('video.index') }}"
               class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">MY APPS &gt; VIDEO &gt; TAMBAH VIDEO</div>
                <h1 class="text-2xl font-black text-gray-900 uppercase">Tambah Video</h1>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            {{-- ===== MAIN FORM (col-span-9) ===== --}}
            <div class="col-span-12 lg:col-span-9 space-y-6">
                
                {{-- ===== PESAN ERROR ===== --}}
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                        <h4 class="text-sm font-bold text-red-700 mb-2">Terdapat kesalahan:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ===== COMPACT UPLOAD WIDGET ===== --}}
                <div x-data="{
                    videoUrl: '{{ old('youtube_url', '') }}',
                    videoId: null,
                    extractVideoId() {
                        let match = this.videoUrl.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/);
                        this.videoId = match ? match[1] : null;
                    },
                    init() {
                        if(this.videoUrl) this.extractVideoId();
                    }
                }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <div class="flex border-b border-gray-100">
                        <div class="flex-1 py-3 text-sm font-bold uppercase tracking-wide text-red-700 border-b-2 border-red-700 text-center">
                            Unggah Video
                        </div>
                    </div>

                    <div class="p-5">
                        <form action="{{ route('videos.store') }}" method="POST">
                            @csrf
                            
                            {{-- Judul Video --}}
                            <div class="mb-4">
                                <label for="video-title" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                    JUDUL VIDEO <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="video-title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Masukkan judul video..."
                                    class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                    required
                                >
                            </div>

                            <!-- Video YouTube URL Input -->
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                URL YOUTUBE <span class="text-red-500">*</span>
                            </label>
                            <div class="mb-4">
                                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-3">
                                    <svg class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    <input type="url" name="youtube_url" x-model="videoUrl" @input="extractVideoId()"
                                        class="flex-1 bg-transparent border-0 focus:ring-0 text-sm text-gray-700 placeholder-gray-400 p-0"
                                        placeholder="Tempel link YouTube di sini... (https://youtu.be/...)">
                                </div>
                                
                                <!-- Video Preview -->
                                <div x-show="videoId" class="rounded-xl overflow-hidden bg-black aspect-video relative" style="display: none;">
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
                            
                            {{-- Tags --}}
                            <div class="mb-4">
                                <label for="video-tags" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                    TAGS (TANDA)
                                </label>
                                <input
                                    type="text"
                                    id="video-tags"
                                    name="tags"
                                    value="{{ old('tags') }}"
                                    placeholder="Musik, VLOG, Liburan..."
                                    class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                >
                            </div>

                            <!-- Pilih Album -->
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PILIH ALBUM VIDEO</label>
                                <div x-data="{ albumMode: 'select', albumSelection: '{{ old('album_id', '') }}' }">
                                    <!-- Mode Select -->
                                    <div x-show="albumMode === 'select'" class="flex flex-col sm:flex-row gap-2">
                                        <select name="album_id" x-model="albumSelection" class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:ring-1 focus:ring-red-500 transition-colors">
                                            <option value="">-- Tidak dimasukkan ke album --</option>
                                            @foreach($albums as $album)
                                                <option value="{{ $album->id }}">{{ $album->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" @click="albumMode = 'new'; albumSelection = 'new'" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors shrink-0 whitespace-nowrap shadow-sm">
                                            + Buat Album Baru
                                        </button>
                                    </div>
                                    <!-- Mode Create New -->
                                    <div x-show="albumMode === 'new'" style="display: none;" class="flex flex-col sm:flex-row gap-2">
                                        <input type="hidden" name="album_id" value="new" :disabled="albumMode !== 'new'">
                                        <input type="text" name="new_album_name" placeholder="Masukkan nama album baru..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:ring-1 focus:ring-red-500 transition-colors">
                                        <button type="button" @click="albumMode = 'select'; albumSelection = ''" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors shrink-0 whitespace-nowrap">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Deskripsi --}}
                            <div class="mb-4">
                                <label for="video-description" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                    DESKRIPSI SINGKAT
                                </label>
                                <textarea
                                    id="video-description"
                                    name="description"
                                    rows="3"
                                    placeholder="Ceritakan isi video ini..."
                                    class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-2.5 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors resize-none"
                                >{{ old('description') }}</textarea>
                            </div>
                            
                            <!-- Bottom Actions -->
                            <div class="flex justify-end items-center mt-2 border-t border-gray-100 pt-4">
                                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded flex items-center gap-2 shadow-sm transition-colors">
                                    SIMPAN VIDEO
                                    <svg class="w-4 h-4 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- ===== RIGHT SIDEBAR (col-span-3) ===== --}}
            <div class="hidden lg:block lg:col-span-3">
                <x-sidebar-right />
            </div>

        </div>
    </div>
</div>
@endsection
