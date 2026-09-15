@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8 -mt-10 -mb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('foto.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Album
                </a>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-0.5">FOTO &gt; ALBUM</div>
                    <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">{{ $album->name ?: 'Tanpa Nama' }}</h1>
                    @if ($album->description)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $album->description }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('photos.upload') }}" class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-5 py-2.5 rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Tambah Foto
            </a>
        </div>

        {{-- ===== PESAN SUKSES / ERROR ===== --}}
        

        {{-- ===== GRID FOTO ===== --}}
        @if ($photos->isEmpty())
            <div class="text-center py-24">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Album ini masih kosong</h3>
                <p class="text-sm text-gray-500 mb-6">Belum ada foto di album <strong>{{ $album->name }}</strong>.</p>
                <a href="{{ route('photos.upload') }}" class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded-lg transition-colors">
                    + Unggah Foto Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" id="photos-grid">
                @foreach ($photos as $photo)
                    @include('photos.partials._photo-item', ['photo' => $photo, 'album' => $album])
                @endforeach
            </div>

            <!-- Sentinel Infinite Scroll -->
            <div id="photos-sentinel" class="py-8 text-center" style="display: {{ $photos->hasMorePages() ? 'block' : 'none' }};">
                <span class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200">
                    <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Memuat foto lainnya...
                </span>
            </div>

        @endif

    </div>

    </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let photoPage = 1;
    let photoHasMore = {{ $photos->hasMorePages() ? 'true' : 'false' }};
    let photoLoading = false;
    const photosGrid = document.getElementById('photos-grid');
    const photosSentinel = document.getElementById('photos-sentinel');

    if (photosSentinel && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && photoHasMore && !photoLoading) {
                loadMorePhotos();
            }
        }, { rootMargin: '250px' });
        observer.observe(photosSentinel);
    }

    async function loadMorePhotos() {
        if (photoLoading || !photoHasMore) return;
        photoLoading = true;
        photoPage++;

        try {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('page', photoPage);

            const res = await fetch(currentUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();

            if (data.html && data.html.trim().length > 0) {
                photosGrid.insertAdjacentHTML('beforeend', data.html);
            }

            photoHasMore = data.hasMore;
            if (!photoHasMore && photosSentinel) {
                photosSentinel.style.display = 'none';
            }
        } catch (err) {
            console.error('Gagal memuat foto:', err);
            photoHasMore = false;
            if (photosSentinel) photosSentinel.style.display = 'none';
        } finally {
            photoLoading = false;
        }
    }
});
</script>
@endpush
@endsection

