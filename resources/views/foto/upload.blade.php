@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">PHOTOS > UPLOAD</div>
        <h1 class="text-3xl font-bold text-gray-900">Upload Photos</h1>
        <p class="text-sm text-gray-600 mt-1">Add photos to one of your albums.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        <!-- Left Column: Main Form (col-span-9) -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">

                {{-- ============================================================ --}}
                {{-- BLOK PESAN ERROR VALIDASI DARI LARAVEL                        --}}
                {{-- ============================================================ --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            Upload gagal! Perbaiki kesalahan berikut:
                        </h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ============================================================ --}}
                {{-- BLOK PESAN SUKSES                                             --}}
                {{-- ============================================================ --}}
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm font-bold text-green-700 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ session('success') }}
                        </p>
                    </div>
                @endif

                <!-- Wrapper Alpine.js -->
                <div x-data="{
                    step: 1,
                    albumSelection: '',
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
                    }
                }">
                    {{-- ============================================================ --}}
                    {{-- FORM UTAMA                                                    --}}
                    {{-- PENTING: action, method POST, enctype multipart/form-data     --}}
                    {{-- ============================================================ --}}
                    <form
                        action="{{ url('/photos/upload') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        {{-- ================= STEP 1 ================= --}}
                        <div x-show="step === 1" class="space-y-8">
                            
                            <!-- Photos Drag & Drop Area -->
                            <div>
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PHOTOS</label>
                                
                                {{-- INPUT FILE SEBENARNYA (tersembunyi) --}}
                                {{-- PENTING: name="photos[]" HARUS SAMA dengan $request->file('photos') di Controller --}}
                                <input
                                    type="file"
                                    id="photo-file-input"
                                    name="photos[]"
                                    multiple
                                    accept="image/jpeg,image/png,image/gif"
                                    class="hidden"
                                    @change="handleFiles($event)"
                                >

                                <!-- Drag & Drop Visual Area - klik untuk trigger input file -->
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-xl p-10 flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors cursor-pointer group"
                                    @click="$refs.fileInput.click()"
                                >
                                    <div class="bg-red-50 text-red-600 p-3 rounded-full mb-4 group-hover:scale-110 transition-transform">
                                        <i data-lucide="cloud-upload" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">Drag & drop photos here</h4>
                                    <p class="text-sm text-gray-500 mb-4">or click to browse files</p>
                                    <p class="text-xs text-gray-400 mb-4">JPG, PNG or GIF (Max 10MB)</p>
                                    <button
                                        type="button"
                                        class="bg-gray-900 hover:bg-black text-white px-6 py-2 rounded text-sm font-medium transition-colors"
                                        @click.stop="document.getElementById('photo-file-input').click()"
                                    >
                                        Choose Files
                                    </button>
                                    <!-- Counter file yang dipilih -->
                                    <p x-show="fileCount > 0" class="mt-3 text-sm text-green-600 font-semibold" x-text="fileCount + ' file dipilih'"></p>
                                </div>
                            </div>

                            <!-- Preview Grid (ditampilkan setelah file dipilih) -->
                            <div x-show="previewFiles.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <template x-for="(file, index) in previewFiles" :key="index">
                                    <div class="relative rounded-lg overflow-hidden border border-gray-200 aspect-square group">
                                        <img :src="file.url" class="w-full h-full object-cover">
                                        <div class="absolute bottom-0 w-full bg-black/50 text-white text-[10px] p-1 truncate" x-text="file.name"></div>
                                    </div>
                                </template>
                            </div>

                            <!-- Global Description -->
                            <div>
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">GLOBAL DESCRIPTION</label>
                                {{-- name="deskripsi[]" agar bisa diterima sebagai array di Controller --}}
                                <textarea
                                    name="deskripsi[]"
                                    rows="3"
                                    placeholder="Add a description for these photos..."
                                    class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 resize-none bg-gray-50 focus:bg-white"
                                ></textarea>
                            </div>

                            <!-- Action Footer Step 1 -->
                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('foto.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                    Cancel
                                </a>
                                <button type="button" @click="step = 2" class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2.5 rounded-md font-bold text-sm flex items-center gap-2 shadow-sm transition-colors">
                                    Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        
                        {{-- ================= STEP 2 ================= --}}
                        <div x-show="step === 2" style="display: none;" class="space-y-8">
                            
                            <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">Select or Create Album</h3>

                            <div class="space-y-6 max-w-lg">
                                <!-- Select Album -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">CHOOSE ALBUM</label>
                                    {{-- 
                                        PENTING: name="album_id" HARUS SAMA dengan $request->album_id di Controller
                                        Jika pilih "new", album_id = "new" (akan diabaikan Controller, pakai new_album_name)
                                        Jika pilih album existing, album_id = id angka
                                    --}}
                                    <select
                                        name="album_id"
                                        x-model="albumSelection"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white"
                                    >
                                        <option value="">Pilih album...</option>
                                        <option value="new" class="font-bold text-red-600">+ Buat Album Baru</option>
                                        {{-- Ambil dari database (tabel jcow_story_categories) --}}
                                        @foreach ($albums as $album)
                                            <option value="{{ $album->id }}">{{ $album->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Create New Album Input -->
                                <div x-show="albumSelection === 'new'" style="display: none;" class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">NEW ALBUM NAME</label>
                                    {{-- 
                                        PENTING: name="new_album_name" HARUS SAMA dengan $request->new_album_name di Controller
                                    --}}
                                    <input
                                        type="text"
                                        name="new_album_name"
                                        placeholder="Enter new album name"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white"
                                    >
                                </div>
                            </div>

                            <!-- Action Footer Step 2 -->
                            <div class="flex items-center justify-between pt-8 border-t border-gray-100">
                                <button type="button" @click="step = 1" class="text-sm font-bold text-gray-600 hover:text-gray-900 flex items-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                                </button>
                                {{-- PENTING: type="submit" untuk mengirimkan form --}}
                                <button type="submit" class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2.5 rounded-md font-bold text-sm flex items-center gap-2 shadow-sm transition-colors">
                                    <i data-lucide="cloud-upload" class="w-4 h-4"></i> Upload Photos
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Info (col-span-3) -->
        <div class="col-span-12 lg:col-span-3 space-y-6">
            <x-sidebar-right />
        </div>    

    </div>
</div>
@endsection
