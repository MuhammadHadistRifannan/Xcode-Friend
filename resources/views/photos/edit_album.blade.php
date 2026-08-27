@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('foto.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">FOTO &gt; EDIT ALBUM</div>
                <h1 class="text-2xl font-black text-gray-900 uppercase">Edit Album</h1>
            </div>
        </div>

        {{-- ===== PESAN ERROR ===== --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h4 class="text-sm font-bold text-red-700 mb-2">Terdapat kesalahan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== FORM EDIT ALBUM ===== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
             x-data="{
                selectedPhotoId: '{{ old('cover_photo_id', $album->latestPhoto?->id ?? '') }}',
                selectedPhotoUrl: '{{ $album->latestPhoto ? asset('storage/' . $album->latestPhoto->uri) : '' }}'
             }"
        >
            <form action="{{ route('foto.album.update', $album->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-7">

                    {{-- ===== SAMPUL AKTIF + NAMA ALBUM ===== --}}
                    <div class="flex items-start gap-5">
                        <div class="shrink-0">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">SAMPUL AKTIF</label>
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 border-2 border-red-500 shadow-md">
                                <template x-if="selectedPhotoUrl">
                                    <img :src="selectedPhotoUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selectedPhotoUrl">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label for="album-name" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                                NAMA ALBUM <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text" id="album-name" name="name"
                                value="{{ old('name', $album->name) }}"
                                placeholder="Masukkan nama album..."
                                class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white transition-colors"
                                required
                            >
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    {{-- ===== PILIH SAMPUL DARI FOTO ALBUM ===== --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider">PILIH SAMPUL ALBUM</label>
                                <p class="text-xs text-gray-400 mt-0.5">Pilih salah satu foto di bawah sebagai sampul album.</p>
                            </div>
                            <span x-show="selectedPhotoId" class="text-[10px] font-bold bg-red-100 text-red-600 px-2.5 py-1 rounded-full">
                                ✓ Foto dipilih
                            </span>
                        </div>

                        {{-- Input hidden — HARUS SAMA dengan $request->cover_photo_id di Controller --}}
                        <input type="hidden" name="cover_photo_id" :value="selectedPhotoId">

                        @if ($photos->isEmpty())
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-10 text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p class="text-sm text-gray-500 font-medium">Album ini belum memiliki foto.</p>
                                <a href="{{ route('photos.upload') }}" class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Unggah Foto
                                </a>
                            </div>
                        @else
                            {{-- Grid Photo Picker --}}
                            <div class="grid grid-cols-4 gap-2.5">
                                @foreach ($photos as $photo)
                                    <button
                                        type="button"
                                        @click="
                                            selectedPhotoId = '{{ $photo->id }}';
                                            selectedPhotoUrl = '{{ asset('storage/' . $photo->uri) }}';
                                        "
                                        class="relative aspect-square rounded-xl overflow-hidden border-2 transition-all duration-150 focus:outline-none"
                                        :class="selectedPhotoId == '{{ $photo->id }}'
                                            ? 'border-red-500 shadow-lg scale-[0.97]'
                                            : 'border-gray-200 hover:border-gray-400'"
                                    >
                                        <img
                                            src="{{ asset('storage/' . $photo->uri) }}"
                                            alt="{{ $photo->des ?: 'Foto #' . $photo->id }}"
                                            class="w-full h-full object-cover"
                                        >
                                        {{-- Centang jika terpilih --}}
                                        <div x-show="selectedPhotoId == '{{ $photo->id }}'" class="absolute inset-0 bg-red-600/20 flex items-center justify-center">
                                            <div class="bg-red-600 text-white rounded-full p-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </div>
                                        @if ($photo->des)
                                            <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-[9px] px-1.5 py-1 truncate">{{ $photo->des }}</div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            {{-- ===== PAGINATION PHOTO PICKER ===== --}}
                            @if ($photos->lastPage() > 1)
                                <div class="mt-4 flex items-center justify-center gap-1">
                                    {{-- Prev --}}
                                    @if ($photos->onFirstPage())
                                        <span class="px-3 py-1.5 rounded-lg text-xs text-gray-300 bg-white border border-gray-200 cursor-not-allowed">&laquo;</span>
                                    @else
                                        <a href="{{ $photos->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">&laquo;</a>
                                    @endif

                                    {{-- Nomor --}}
                                    @foreach ($photos->getUrlRange(1, $photos->lastPage()) as $page => $url)
                                        @if ($page == $photos->currentPage())
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-red-600 border border-red-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($photos->hasMorePages())
                                        <a href="{{ $photos->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">&raquo;</a>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs text-gray-300 bg-white border border-gray-200 cursor-not-allowed">&raquo;</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-400 text-center mt-2">
                                    Navigasi halaman untuk melihat semua foto album.
                                </p>
                            @endif

                        @endif
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <a href="{{ route('foto.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Batal</a>
                    <button type="submit" class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
