<div
    class="group bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200"
    x-data="{ open: false, editMode: false, editDes: '{{ addslashes($photo->des) }}', showPopup: false }"
    @click.away="open = false"
>
    {{-- Gambar Foto --}}
    <div
        class="aspect-square overflow-hidden bg-gray-100 relative cursor-pointer"
        @click="showPopup = true"
    >
        <img
            src="{{ asset('storage/' . $photo->uri) }}"
            alt="{{ $photo->des ?: $album->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><span class=\'text-xs text-gray-400\'>Tidak tersedia</span></div>'"
        >
        {{-- Badge ukuran --}}
        <div class="absolute top-2 left-2 bg-black/50 text-white text-[9px] font-bold px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
            {{ number_format($photo->size / 1024, 1) }} KB
        </div>
        {{-- Icon zoom hint --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="bg-black/40 rounded-full p-2.5">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Area Bawah: Deskripsi + Titik Tiga --}}
    <div class="px-3 pt-2 pb-2.5">

        {{-- MODE NORMAL --}}
        <div x-show="!editMode">
            <div class="flex items-start justify-between gap-1">
                <div class="min-w-0 flex-1 py-0.5">
                    @if ($photo->des)
                        <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">{{ $photo->des }}</p>
                    @else
                        <p class="text-xs text-gray-400 italic">Tanpa deskripsi</p>
                    @endif
                </div>
                {{-- Tombol Titik Tiga --}}
                <div class="relative shrink-0">
                    <button type="button" @click.stop="open = !open" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110 4 2 2 0 010-4zm0 4a2 2 0 110 4 2 2 0 010-4z" />
                        </svg>
                    </button>
                    {{-- Dropdown --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display: none;"
                         class="absolute bottom-full right-0 mb-1 w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-30 overflow-hidden py-1">
                        <button type="button" @click="editMode = true; open = false" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            Edit Deskripsi
                        </button>
                        <div class="mx-3 border-t border-gray-100"></div>
                        <form action="{{ route('foto.photo.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Hapus Foto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODE EDIT DESKRIPSI (Inline) --}}
        <div x-show="editMode" style="display: none;">
            <form action="{{ route('foto.photo.update', $photo->id) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="des" x-model="editDes" rows="2" placeholder="Tulis deskripsi..." class="w-full border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg px-2.5 py-2 text-xs text-gray-800 bg-gray-50 resize-none"></textarea>
                <div class="flex items-center justify-end gap-2 mt-1.5">
                    <button type="button" @click="editMode = false; editDes = '{{ addslashes($photo->des) }}'" class="text-[11px] text-gray-500 hover:text-gray-800 font-medium px-2 py-1 rounded transition-colors">Batal</button>
                    <button type="submit" class="text-[11px] bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1 rounded transition-colors">Simpan</button>
                </div>
            </form>
        </div>

        {{-- POPUP MODAL KHUSUS FOTO INI --}}
        <template x-teleport="body">
            <div 
                x-show="showPopup" 
                style="display: none;" 
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 md:p-12"
                @click.self="showPopup = false"
                @keydown.escape.window="showPopup = false"
            >
                {{-- Tombol Close --}}
                <button type="button" @click="showPopup = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white bg-black/40 hover:bg-black/70 rounded-full p-2.5 transition-colors z-50">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Gambar (Dipaksa fit ke layar maksimal) --}}
                <div class="w-full h-full flex items-center justify-center pointer-events-none" @click.self="showPopup = false">
                    <img src="{{ asset('storage/' . $photo->uri) }}" class="w-full h-full object-contain pointer-events-auto" @click.stop>
                </div>
            </div>
        </template>

    </div>
</div>
