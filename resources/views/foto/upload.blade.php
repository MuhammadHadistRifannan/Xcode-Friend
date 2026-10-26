@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    {{-- ===== HEADER ===== --}}
    <div class="mb-5">
        @if(request('from') === 'profile' && auth()->check())
            <a href="{{ route('profile.show', [auth()->user()->username, 'tab' => 'foto']) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Profil
            </a>
        @else
            <a href="{{ route('foto.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Album
            </a>
        @endif
    </div>

    <div class="mb-8">
        <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-1">MY APPS &gt; FOTO &gt; TAMBAH FOTO</div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 uppercase tracking-tight">Tambah Foto</h1>
        <p class="text-sm text-gray-500 mt-1">Unggah beberapa foto sekaligus ke album Anda.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        {{-- ===== MAIN FORM (col-span-9) ===== --}}
        <div class="col-span-12 lg:col-span-9 space-y-6">

            {{-- ===== PESAN ERROR ===== --}}
            

            

            {{-- ===== COMPACT UPLOAD WIDGET ===== --}}
            <div x-data="{
                files: [],
                previewFiles: [],

                syncInput() {
                    const input = this.$refs.photoInput;
                    if (!input) return;
                    try {
                        const dt = new DataTransfer();
                        this.files.forEach(f => dt.items.add(f));
                        input.files = dt.files;
                    } catch (e) {
                        console.warn('DataTransfer sync warning:', e);
                    }
                },

                handleFiles(event) {
                    const input = event.target;
                    if (!input.files || input.files.length === 0) return;

                    const newFiles = Array.from(input.files);
                    newFiles.forEach(file => {
                        if (!file.type || !file.type.startsWith('image/')) return;

                        // Cek duplikasi file (nama, ukuran, waktu modifikasi)
                        const exists = this.files.some(f => 
                            f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                        );

                        if (!exists) {
                            this.files.push(file);
                            this.previewFiles.push({
                                name: file.name,
                                size: file.size,
                                url: URL.createObjectURL(file)
                            });
                        }
                    });

                    this.syncInput();
                },

                triggerAddMore() {
                    const input = this.$refs.photoInput;
                    if (!input) return;

                    input.value = '';

                    const onFocus = () => {
                        window.removeEventListener('focus', onFocus);
                        setTimeout(() => {
                            if (!input.files || input.files.length === 0) {
                                this.syncInput();
                            }
                        }, 300);
                    };
                    window.addEventListener('focus', onFocus);

                    input.click();
                },

                removeFile(index) {
                    if (index < 0 || index >= this.files.length) return;

                    if (this.previewFiles[index] && this.previewFiles[index].url) {
                        try {
                            URL.revokeObjectURL(this.previewFiles[index].url);
                        } catch (e) {}
                    }

                    this.files.splice(index, 1);
                    this.previewFiles.splice(index, 1);

                    this.syncInput();
                },

                clearFiles() {
                    this.previewFiles.forEach(p => {
                        if (p.url) {
                            try {
                                URL.revokeObjectURL(p.url);
                            } catch (e) {}
                        }
                    });
                    this.files = [];
                    this.previewFiles = [];
                    if (this.$refs.photoInput) {
                        this.$refs.photoInput.value = '';
                    }
                    this.syncInput();
                }
            }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="flex border-b border-gray-100">
                    <div class="flex-1 py-3 text-sm font-bold uppercase tracking-wide text-red-700 border-b-2 border-red-700 text-center">
                        Unggah Foto
                    </div>
                </div>

                <div class="p-5">
                    <form action="{{ url('/photos/upload') }}" method="POST" enctype="multipart/form-data" @submit="syncInput()">
                        @csrf
                        <input type="hidden" name="from" value="{{ request('from') }}">
                        
                        <!-- File Upload Input Area -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PILIH FOTO <span class="text-red-500">*</span></label>
                            
                            <label for="photoUpload" x-show="previewFiles.length === 0" class="border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-gray-500 hover:bg-gray-50 hover:border-red-300 transition-colors cursor-pointer group">
                                <div class="bg-red-50 text-red-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 mb-1">Klik untuk memilih gambar</span>
                                <span class="text-xs text-gray-400">JPG, PNG, GIF, WebP (Maks 10MB per foto)</span>
                            </label>
                            
                            <!-- Preview Grid -->
                            <div x-show="previewFiles.length > 0" class="bg-gray-50 border border-gray-200 rounded-xl p-4" style="display: none;">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-3 border-b border-gray-200/80">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            <span x-text="previewFiles.length + ' Foto Dipilih'"></span>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="triggerAddMore()" class="inline-flex items-center gap-1.5 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-700 hover:text-red-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah Foto Lainnya
                                        </button>
                                        <button type="button" @click="clearFiles()" class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                            Hapus Semua
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    <template x-for="(file, index) in previewFiles" :key="index">
                                        <div class="relative rounded-xl overflow-hidden border border-gray-200 aspect-square group shadow-sm bg-white select-none">
                                            <img :src="file.url" class="w-full h-full object-cover transition duration-200 group-hover:scale-105">
                                            
                                            <!-- Subtle dark gradient on hover -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/30 opacity-0 group-hover:opacity-100 transition duration-200 pointer-events-none"></div>

                                            <!-- File name & index badge (bottom) -->
                                            <div class="absolute bottom-1.5 inset-x-1.5 flex items-center justify-between pointer-events-none opacity-0 group-hover:opacity-100 transition duration-200">
                                                <span class="text-[10px] text-white font-medium truncate max-w-[75%] drop-shadow bg-black/50 px-1.5 py-0.5 rounded" x-text="file.name"></span>
                                                <span class="text-[9px] text-white/90 drop-shadow bg-black/50 px-1 py-0.5 rounded" x-text="'#' + (index + 1)"></span>
                                            </div>

                                            <!-- Tombol Silang (X) untuk menghapus foto tertentu -->
                                            <button type="button" 
                                                    @click.stop="removeFile(index)" 
                                                    class="absolute top-1.5 right-1.5 w-6 h-6 bg-neutral-900/80 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition shadow-md hover:scale-110 active:scale-95 focus:outline-none" 
                                                    title="Hapus foto ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
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
                            @if(request('from') === 'profile' && auth()->check())
                                <a href="{{ route('profile.show', [auth()->user()->username, 'tab' => 'foto']) }}" class="text-sm font-bold text-gray-500 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    Batal
                                </a>
                            @else
                                <a href="{{ route('foto.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    Batal
                                </a>
                            @endif
                            <button type="submit" :disabled="files.length === 0" :class="files.length === 0 ? 'opacity-50 cursor-not-allowed bg-red-700' : 'bg-red-700 hover:bg-red-800'" class="text-white px-6 py-2.5 rounded-lg font-bold text-sm flex items-center gap-2 shadow-sm transition-colors">
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
