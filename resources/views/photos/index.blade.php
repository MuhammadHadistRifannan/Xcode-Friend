@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-1">MY APPS &gt; FOTO</div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Album Foto</h1>
                <p class="text-sm text-gray-500 mt-1">Koleksi album foto Anda.</p>
            </div>
            <a href="{{ route('photos.upload') }}" class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-5 py-2.5 rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Unggah Foto
            </a>
        </div>

        {{-- ===== PESAN SUKSES ===== --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ===== GRID ALBUM ===== --}}
        @if ($albums->isEmpty())
            <div class="text-center py-24">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada album</h3>
                <p class="text-sm text-gray-500 mb-6">Mulai unggah foto pertama Anda untuk membuat album baru.</p>
                <a href="{{ route('photos.upload') }}" class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded-lg transition-colors">
                    + Unggah Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($albums as $album)
                    <div
                        class="group bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-md hover:-translate-y-1 transition-all duration-200"
                        x-data="{ open: false }"
                        @click.away="open = false"
                    >
                        {{-- Cover Album (link ke detail) --}}
                        <a href="{{ route('foto.show', $album->id) }}" class="block">
                            <div class="aspect-square overflow-hidden bg-gray-100 relative">
                                @if ($album->latestPhoto)
                                    <img
                                        src="{{ asset('storage/' . $album->latestPhoto->uri) }}"
                                        alt="{{ $album->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><span class=\'text-xs text-gray-400 font-medium\'>No Image</span></div>'"
                                    >
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-[10px] text-gray-400 font-semibold uppercase">No Image</span>
                                    </div>
                                @endif
                                {{-- Overlay --}}
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-semibold">Lihat Album &rarr;</span>
                                </div>
                            </div>
                        </a>

                        {{-- ===== AREA BAWAH: Nama Album + Tombol Titik Tiga ===== --}}
                        <div class="flex items-center justify-between px-3 py-2.5 gap-2">
                            {{-- Nama Album --}}
                            <a href="{{ route('foto.show', $album->id) }}" class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-gray-800 truncate group-hover:text-red-700 transition-colors">
                                    {{ $album->name ?: 'Tanpa Nama' }}
                                </h3>
                                @if ($album->description)
                                    <p class="text-[11px] text-gray-400 truncate">{{ $album->description }}</p>
                                @endif
                            </a>

                            {{-- Tombol Titik Tiga --}}
                            <div class="relative shrink-0">
                                <button
                                    type="button"
                                    @click.stop="open = !open"
                                    class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                    title="Opsi Album"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110 4 2 2 0 010-4zm0 4a2 2 0 110 4 2 2 0 010-4z" />
                                    </svg>
                                </button>

                                {{-- Dropdown --}}
                                <div
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    style="display: none;"
                                    class="absolute bottom-full right-0 mb-1 w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-30 overflow-hidden py-1"
                                >
                                    {{-- Edit Album --}}
                                    <a
                                        href="{{ route('foto.album.edit', $album->id) }}"
                                        class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors"
                                    >
                                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit Album
                                    </a>
                                    <div class="mx-3 border-t border-gray-100"></div>
                                    {{-- Hapus Album --}}
                                    <form
                                        action="{{ route('foto.album.destroy', $album->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus album \'{{ addslashes($album->name) }}\'? Semua foto akan ikut terhapus!')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus Album
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
