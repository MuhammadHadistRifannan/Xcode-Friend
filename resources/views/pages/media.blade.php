@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('pages.show', $page->id) }}" class="hover:text-red-700 transition-colors">{{ $page->name }}</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-gray-900 font-semibold">{{ $title }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        </div>
        <a href="{{ route('pages.show', $page->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Grup
        </a>
    </div>

    @if($mediaList->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <i data-lucide="{{ $type === 'photo' ? 'image' : 'video' }}" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada {{ $title }}</h3>
            <p class="text-sm text-gray-500">Anggota grup belum membagikan {{ $type === 'photo' ? 'foto' : 'vidio' }} apa pun.</p>
        </div>
    @else
        <div x-data="{
            videoUrl: '', 
            videoId: null,
            photoUrl: null,
            openVideo(url) {
                this.videoUrl = url;
                let match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&?\/\s]{11})/);
                if (match && match[1]) {
                    this.videoId = match[1];
                }
            }
        }">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($mediaList as $media)
                @if($type === 'photo')
                    <!-- Photo Card -->
                    <div @click="photoUrl = '{{ asset('storage/'.$media->attachment) }}'" class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer">
                        <img src="{{ asset('storage/'.$media->attachment) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Author Info (Bottom) -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <div class="flex items-center gap-2">
                                <img src="{{ $media->user?->avatar ? asset('storage/'.$media->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($media->user?->username ?? 'U').'&background=random' }}" class="w-5 h-5 rounded-full border border-white shrink-0">
                                <span class="text-white text-xs font-medium truncate drop-shadow-md">{{ $media->user?->username ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'video')
                    <!-- Video Card -->
                    @php $ytId = str_replace('youtube:', '', $media->attachment); @endphp
                    <div class="group aspect-square bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative block cursor-pointer" @click="openVideo('https://www.youtube.com/watch?v={{ $ytId }}')">
                        <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center">
                            <div class="bg-red-600 text-white rounded-full p-3 shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5"></i>
                            </div>
                        </div>
                        <!-- Author Info (Top) -->
                        <div class="absolute top-0 left-0 right-0 p-3 bg-gradient-to-b from-black/60 to-transparent">
                            <div class="flex items-center gap-2">
                                <img src="{{ $media->user?->avatar ? asset('storage/'.$media->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($media->user?->username ?? 'U').'&background=random' }}" class="w-5 h-5 rounded-full border border-white shrink-0">
                                <span class="text-white text-xs font-medium truncate drop-shadow-md">{{ $media->user?->username ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            </div>

            <!-- Video Player Modal (Alpine.js) -->
            <div x-show="videoId" 
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm p-4"
                style="display: none;"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                
                <div class="relative w-full max-w-4xl mx-auto rounded-xl overflow-hidden shadow-2xl bg-black" @click.away="videoId = null">
                    <!-- Tombol Close -->
                    <button type="button" @click="videoId = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md">
                        Tutup <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    
                    <!-- Video Container -->
                    <div class="relative pt-[56.25%] w-full bg-black rounded-xl overflow-hidden">
                        <template x-if="videoId">
                            <iframe 
                                class="absolute top-0 left-0 w-full h-full"
                                :src="'https://www.youtube.com/embed/' + videoId + '?autoplay=1'" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Photo Viewer Modal (Alpine.js) -->
            <div x-show="photoUrl" 
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm p-4"
                style="display: none;"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                
                <div class="relative w-full h-full max-w-5xl max-h-[90vh] flex items-center justify-center" @click.away="photoUrl = null">
                    <button type="button" @click="photoUrl = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md z-10">
                        Tutup <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    
                    <img :src="photoUrl" class="w-full h-full object-contain rounded-lg shadow-2xl">
                </div>
            </div>
        </div>

        <div class="mt-8">
            {{ $mediaList->links() }}
        </div>
    @endif
</div>
@endsection
