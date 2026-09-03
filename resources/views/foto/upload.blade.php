@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('foto.index') }}"
           class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <div>
            <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">MY APPS &gt; FOTO &gt; TAMBAH FOTO</div>
            <h1 class="text-2xl font-black text-gray-900 uppercase">Tambah Foto</h1>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        {{-- ===== MAIN FORM (col-span-9) ===== --}}
        <div class="col-span-12 lg:col-span-9 space-y-6">

            {{-- ===== PESAN ERROR ===== --}}
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <h4 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Upload gagal! Perbaiki kesalahan berikut:
                    </h4>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm font-bold text-green-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            {{-- ===== COMPACT UPLOAD WIDGET ===== --}}
            <div x-data="{
                fileCount: 0,
                previewFiles: [],
                handleFiles(event) {
                    const files = event.target.files;
                    this.fileCount = files.length;
                    this.previewFiles = [];
                    for (let i = 0; i < files.length; i++) {
                        this.previewFiles.push({
                            name: files[i].name,
                            url: URL.createObjectURL(files[i])
                        });
                    }
                },
                clearFiles() {
                    this.fileCount = 0;
                    this.previewFiles = [];
                    this.$refs.photoInput.value = '';
                }
            }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="flex border-b border-gray-100">
                    <div class="flex-1 py-3 text-sm font-bold uppercase tracking-wide text-red-700 border-b-2 border-red-700 text-center">
                        Unggah Foto
                    </div>
                </div>

                <div class="p-5">
                    <form action="{{ url('/photos/upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload Input Area -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PILIH FOTO <span class="text-red-500">*</span></label>
                            
                            <label for="photoUpload" x-show="previewFiles.length === 0" class="border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-gray-500 hover:bg-gray-50 hover:border-red-300 transition-colors cursor-pointer group">
                                <div class="bg-red-50 text-red-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 mb-1">Klik untuk memilih gambar</span>
                                <span class="text-xs text-gray-400">JPG, PNG, GIF, WebP (Maks 10MB)</span>
                            </label>
                            
                            <!-- Preview Grid -->
                            <div x-show="previewFiles.length > 0" class="bg-gray-50 border border-gray-200 rounded-lg p-4" style="display: none;">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-bold text-green-600" x-text="fileCount + ' File Dipilih'"></span>
                                    <button type="button" @click="clearFiles()" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Hapus Semua
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="(file, index) in previewFiles" :key="index">
                                        <div class="relative rounded-lg overflow-hidden border border-gray-200 aspect-square group shadow-sm bg-white">
                                            <img :src="file.url" class="w-full h-full object-cover">
                                            <div class="absolute bottom-0 w-full bg-black/60 text-white text-[10px] p-1.5 truncate text-center font-medium" x-text="file.name"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Input File Asli -->
                            <input id="photoUpload" type="file" name="photos[]" multiple x-ref="photoInput" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp" @change="handleFiles($event)">
                        </div>

                        <!-- Pilih Album -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PILIH ALBUM</label>
                            <div x-data="{ albumMode: 'select', albumSelection: '' }">
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

                        <!-- Deskripsi Global -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">DESKRIPSI FOTO</label>
                            <textarea
                                name="deskripsi[]"
                                rows="3"
                                placeholder="Ceritakan tentang foto-foto ini..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 resize-none bg-gray-50 focus:bg-white transition-colors"
                            ></textarea>
                        </div>

                        <!-- Action Footer -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('foto.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm transition-colors">
                                UNGGAH FOTO
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
@endsection
