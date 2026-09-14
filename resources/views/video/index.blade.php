@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8 -mt-10 -mb-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
        {{-- ===== HEADER ===== --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mb-1">
                    {{ isset($isPublic) && $isPublic ? 'PUBLIC' : 'MY APPS' }} &gt; VIDEO
                </div>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Album Video</h1>
                <p class="text-sm text-gray-500 mt-1">Koleksi album video.</p>
            </div>
            
            @if(!isset($isPublic) || !$isPublic)
            <a href="{{ route('videos.create') }}"
               class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-5 py-2.5 rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Video
            </a>
            @endif
        </div>

        {{-- ===== PESAN SUKSES ===== --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ===== GRID ALBUM ===== --}}
        @if ($albums->isEmpty())
            <div class="text-center py-24">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada album video</h3>
                <p class="text-sm text-gray-500 mb-6">Belum ada video yang ditambahkan di album ini.</p>
                @if(!isset($isPublic) || !$isPublic)
                <a href="{{ route('videos.create') }}"
                   class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-6 py-2.5 rounded-lg transition-colors">
                    + Tambah Video Sekarang
                </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5" id="videos-grid">
                @foreach ($albums as $album)
                    @include('video.partials._album-card', ['album' => $album, 'isPublic' => $isPublic ?? false])
                @endforeach
            </div>

            <!-- Sentinel Infinite Scroll -->
            <div id="videos-sentinel" class="py-8 text-center" style="display: {{ $albums->hasMorePages() ? 'block' : 'none' }};">
                <span class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200">
                    <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Memuat album lainnya...
                </span>
            </div>
        @endif

        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            <x-sidebar-right />
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let videoPage = 1;
    let videoHasMore = {{ $albums->hasMorePages() ? 'true' : 'false' }};
    let videoLoading = false;
    const videosGrid = document.getElementById('videos-grid');
    const videosSentinel = document.getElementById('videos-sentinel');

    if (videosSentinel && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && videoHasMore && !videoLoading) {
                loadMoreVideos();
            }
        }, { rootMargin: '250px' });
        observer.observe(videosSentinel);
    }

    async function loadMoreVideos() {
        if (videoLoading || !videoHasMore) return;
        videoLoading = true;
        videoPage++;

        try {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('page', videoPage);

            const res = await fetch(currentUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();

            if (data.html && data.html.trim().length > 0) {
                videosGrid.insertAdjacentHTML('beforeend', data.html);
            }

            videoHasMore = data.hasMore;
            if (!videoHasMore && videosSentinel) {
                videosSentinel.style.display = 'none';
            }
        } catch (err) {
            console.error('Gagal memuat video:', err);
            videoHasMore = false;
            if (videosSentinel) videosSentinel.style.display = 'none';
        } finally {
            videoLoading = false;
        }
    }
});
</script>
@endpush
@endsection

