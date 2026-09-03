@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    <div class="flex flex-col lg:flex-row gap-6">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex flex-col gap-1">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Ada kesalahan:</span>
                </div>
                <ul class="list-disc pl-8 font-normal text-xs mt-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Left Sidebar (col-span-3 equivalent in a 12-grid system: roughly 25%) -->
        <div class="w-full lg:w-3/12 xl:w-1/4 shrink-0">
            <x-sidebar-left :profile="$pageData['profile']" />
        </div>

        <!-- Main Feed (col-span-6 equivalent: roughly 50%) -->
        <div class="w-full lg:w-6/12 xl:w-2/4">
            <!-- Upload Box (Tampil jika user adalah member halaman) -->
            @if($pageData['profile']['isOwner'])
            <x-feed-upload action="{{ route('pages.stream', $page->id) }}" app="page" aid="{{ $page->id }}" wallId="{{ $page->id }}" />
            @endif
            @if(isset($filter) && in_array($filter, ['photo', 'video']))
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3 bg-red-50 text-red-800 px-4 py-3 rounded-xl border border-red-100 shadow-sm">
                    <span class="text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Menampilkan unggahan dengan {{ $filter == 'photo' ? 'Foto' : 'Video' }} saja.
                    </span>
                    <a href="{{ route('pages.show', $page->id) }}" class="text-xs font-bold bg-white text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors shadow-sm">
                        Lihat Semua Postingan
                    </a>
                </div>
            @endif

            <!-- Feed Postingan / Empty State -->
            <div class="max-h-[600px] overflow-y-auto pr-2 pb-4 space-y-5 scrollbar-thin scrollbar-thumb-gray-200">
                @forelse($page->streams as $post)
                    <div id="post-{{ $post->id }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <!-- Post Header: Avatar + Nama + Waktu -->
                    <div class="flex items-start justify-between mb-3 relative" x-data="{ openOptions: false }">
                        <div class="flex items-start gap-3">
                            <img src="{{ $post->user->avatar_url }}"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0" alt="Avatar">
                            <div>
                                <span class="font-bold text-gray-900 text-sm">{{ $post->user?->fullname ?? $post->user?->username ?? 'Unknown User' }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::createFromTimestamp($post->created)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Tombol Opsi Postingan (Hanya untuk Admin Grup atau Pemilik Postingan) -->
                        <!-- Tombol Opsi Postingan -->
                        <div>
                            <button @click="openOptions = !openOptions" @click.away="openOptions = false" type="button" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100 focus:outline-none" title="Opsi Postingan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openOptions" style="display: none;"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 top-8 mt-1 w-36 bg-white border border-gray-200 rounded-md shadow-lg z-10 py-1">
                                
                                @if(Auth::id() === $page->uid || Auth::id() === $post->uid)
                                    @if(Auth::id() === $post->uid)
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 w-full text-left">Edit</a>
                                    @endif
                                    <form action="{{ route('stream.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus postingan ini?')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                                
                                <button type="button" onclick="openReportModal('{{ url('groups/' . $page->id . '#post-' . $post->id) }}')" class="block px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                    Laporkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Isi Postingan -->
                    @if($post->message)
                        <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ $post->message }}</p>
                    @endif

                    <!-- Attachment (Foto atau YouTube Embed) -->
                    @if($post->attachment)
                        @php
                            $isYoutube = str_starts_with($post->attachment, 'youtube:');
                        @endphp
                        @if($isYoutube)
                            @php $ytId = str_replace('youtube:', '', $post->attachment); @endphp
                            <div class="rounded-xl overflow-hidden mt-2 bg-black aspect-video">
                                <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $ytId }}"
                                    title="YouTube video"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <img src="{{ asset('storage/'.$post->attachment) }}"
                                 class="w-full rounded-xl max-h-96 object-contain bg-gray-50 border border-gray-100 mt-2"
                                 alt="Attachment">
                        @endif
                    @endif

                    <!-- Aksi Post (Like & Comment) -->
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-3 mt-3 text-xs font-bold text-gray-500">
                        @php
                            $isLiked = $post->likedBy->where('uid', Auth::id())->count() > 0;
                        @endphp
                        <form action="{{ route('stream.like', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 transition-colors {{ $isLiked ? 'text-red-600' : 'hover:text-red-600' }}">
                                <svg class="w-4 h-4 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                {{ $post->likedBy->count() }} Suka
                            </button>
                        </form>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            {{ $post->comments->count() }} Komentar
                        </div>
                    </div>

                    <!-- List Komentar -->
                    @if($post->comments->count() > 0)
                        <div class="bg-gray-50 rounded-lg p-4 mt-3 space-y-3 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200">
                            @foreach($post->comments as $comment)
                                <div class="flex gap-2" id="comment-{{ $comment->id }}">
                                    <img src="{{ $comment->user->avatar_url }}"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200" alt="Avatar">
                                    <div x-data="{ editing: false, openCommentOptions: false }">
                                        <div class="flex items-center gap-2 group">
                                            <!-- Tampilan Komentar Biasa -->
                                            <div x-show="!editing" class="bg-white px-3 py-2 rounded-2xl border border-gray-100 shadow-sm text-sm">
                                                <span class="font-bold text-gray-900">{{ $comment->user?->fullname ?? $comment->user?->username ?? 'Unknown' }}</span>
                                                <span class="text-gray-700 ml-1">{{ $comment->message }}</span>
                                            </div>

                                            <!-- Form Edit Komentar -->
                                            <form x-show="editing" style="display: none;" action="{{ route('comment.update', $comment->id) }}" method="POST" class="flex gap-2 w-full max-w-sm">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="message" value="{{ $comment->message }}" class="flex-1 rounded-full bg-white border border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-3 py-1 outline-none" required>
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-3 py-1 text-xs font-bold transition-colors">Simpan</button>
                                                <button type="button" @click="editing = false" class="bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full px-3 py-1 text-xs font-bold transition-colors">Batal</button>
                                            </form>

                                            <div class="relative" x-show="!editing">
                                                <button @click="openCommentOptions = !openCommentOptions" @click.away="openCommentOptions = false" type="button" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-200 focus:outline-none" title="Opsi Komentar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>
                                                
                                                <div x-show="openCommentOptions" style="display: none;"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute left-0 top-6 mt-1 w-24 bg-white border border-gray-200 rounded-md shadow-lg z-20 py-1">
                                                    
                                                    @if(Auth::id() === $comment->uid)
                                                    <button type="button" @click="editing = true; openCommentOptions = false" class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 hover:text-gray-900 w-full text-left">Edit</button>
                                                    @endif
                                                    
                                                    @if(Auth::id() === $comment->uid || Auth::id() === $page->uid)
                                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus komentar ini?')" class="block px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 w-full text-left">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <button type="button" onclick="openReportModal('{{ url('groups/' . $page->id . '#comment-' . $comment->id) }}')" class="block px-3 py-1.5 text-xs text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                                        Laporkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 ml-2">
                                            <p class="text-[10px] text-gray-400">{{ $comment->createdAt->diffForHumans() }}</p>
                                            <button type="button" onclick="const inp = document.getElementById('comment-input-{{ $post->id }}'); if(inp) { inp.value = '@' + '{{ addslashes($comment->user?->fullname ?? $comment->user?->username ?? 'Unknown') }}' + ' '; inp.focus(); }" class="text-[10px] text-gray-500 font-semibold hover:text-red-600 transition-colors">Balas</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form Komentar -->
                    <form action="{{ route('stream.comment', $post->id) }}" method="POST" class="mt-3 flex gap-2">
                        @csrf
                        <input type="text" name="message" id="comment-input-{{ $post->id }}" placeholder="Tulis komentar..." required
                               class="flex-1 rounded-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-4 py-2 transition-all outline-none">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shrink-0 transition-colors flex items-center justify-center w-10 h-10 shadow-sm">
                            <svg class="w-4 h-4 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-20 px-4 flex flex-col items-center justify-center text-center">
                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <p class="text-gray-500 font-bold">Belum ada postingan</p>
                    <p class="text-sm text-gray-400 mt-1">Jadilah yang pertama memulai percakapan di halaman ini!</p>
                </div>
            @endforelse
            </div>
        </div>

        <!-- Right Sidebar (col-span-3 equivalent: roughly 25%) -->
        <div class="w-full lg:w-3/12 xl:w-1/4 shrink-0">
            <x-sidebar-right />

<!-- Kotak Media Halaman -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ 
                mediaTab: 'photo',
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
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-sm tracking-wide uppercase">Media Halaman</h3>
                </div>
                
                <!-- Tabs Navigation -->
                <div class="flex border-b border-gray-100">
                    <button type="button" @click="mediaTab = 'photo'" :class="{ 'border-red-600 text-red-600': mediaTab === 'photo', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mediaTab !== 'photo' }" class="flex-1 py-3 text-sm font-bold border-b-2 transition-colors">
                        Foto
                    </button>
                    <button type="button" @click="mediaTab = 'video'" :class="{ 'border-red-600 text-red-600': mediaTab === 'video', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': mediaTab !== 'video' }" class="flex-1 py-3 text-sm font-bold border-b-2 transition-colors">
                        Vidio
                    </button>
                </div>

                <!-- Photos Grid -->
                <div x-show="mediaTab === 'photo'" class="p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($recentPhotos->count() > 0)
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach($recentPhotos as $photo)
                                <div @click="photoUrl = '{{ asset('storage/'.$photo->attachment) }}'" class="aspect-square bg-gray-100 rounded-md overflow-hidden block group relative cursor-pointer">
                                    <img src="{{ asset('storage/'.$photo->attachment) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-xs text-gray-400 bg-gray-50 rounded-lg mb-4 border border-gray-100">
                            Belum ada foto
                        </div>
                    @endif
                    <a href="{{ route('pages.media', ['page' => $page->id, 'type' => 'photo']) }}" class="block w-full py-2 text-center text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors hover:text-red-700">
                        Lihat semuanya
                    </a>
                </div>

                <!-- Videos Grid -->
                <div x-show="mediaTab === 'video'" class="p-4" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($recentVideos->count() > 0)
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach($recentVideos as $video)
                                @php $ytId = str_replace('youtube:', '', $video->attachment); @endphp
                                <div @click="openVideo('https://www.youtube.com/watch?v={{ $ytId }}')" class="aspect-square bg-gray-100 rounded-md overflow-hidden block relative group border border-gray-200 cursor-pointer">
                                    <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 opacity-90 group-hover:opacity-100">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-black bg-opacity-60 rounded-full p-1.5 backdrop-blur-sm group-hover:bg-red-600 transition-colors">
                                            <i data-lucide="play" class="w-3 h-3 text-white fill-white ml-0.5"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-xs text-gray-400 bg-gray-50 rounded-lg mb-4 border border-gray-100">
                            Belum ada vidio
                        </div>
                    @endif
                    <a href="{{ route('pages.media', ['page' => $page->id, 'type' => 'video']) }}" class="block w-full py-2 text-center text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors hover:text-red-700">
                        Lihat semuanya
                    </a>
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
                        <button type="button" @click="videoId = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        
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

        </div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Laporkan Konten</h3>
            <button type="button" onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <input type="hidden" name="url" id="reportUrl" value="">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Laporan</label>
                    <textarea name="message" rows="4" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="Jelaskan alasan mengapa Anda melaporkan konten ini..."></textarea>
                </div>
                <p class="text-xs text-gray-500">Laporan Anda bersifat anonim dan akan ditinjau oleh administrator.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-red-700 shadow-sm transition-colors">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal(url) {
        document.getElementById('reportUrl').value = url;
        document.getElementById('reportModal').classList.remove('hidden');
    }
    
    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
    }
</script>
        </div>

    </div>
</div>

<!-- Image Modal (Alpine.js) -->
<div x-data="{ imgModal: false, imgModalSrc: '', imgModalTitle: '' }"
    @img-modal.window="imgModal = true; imgModalSrc = $event.detail.imgModalSrc; imgModalTitle = $event.detail.imgModalTitle"
    x-cloak>
    
    <template x-teleport="body">
        <div x-show="imgModal"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 transition-opacity duration-300"
            x-transition:enter="ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <button @click="imgModal = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-sm transition-all focus:outline-none">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="relative w-full h-full max-w-[95vw] max-h-[95vh] flex items-center justify-center flex-col" @click.away="imgModal = false">
                <img :src="imgModalSrc" :alt="imgModalTitle" class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl">
                <p x-show="imgModalTitle" x-text="imgModalTitle" class="text-white text-center mt-4 text-sm md:text-base font-medium opacity-80"></p>
            </div>
        </div>
    </template>
</div>

<!-- Video Modal (Alpine.js) -->
<div x-data="{ vidModal: false, vidModalSrc: '', vidModalTitle: '' }"
    @vid-modal.window="vidModal = true; vidModalSrc = $event.detail.vidModalSrc; vidModalTitle = $event.detail.vidModalTitle"
    x-cloak>
    
    <template x-teleport="body">
        <div x-show="vidModal"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 transition-opacity duration-300"
            x-transition:enter="ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <button @click="vidModal = false; vidModalSrc = ''" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-sm transition-all focus:outline-none z-10">
                <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="w-full max-w-5xl aspect-video rounded-xl overflow-hidden shadow-2xl border border-white/10 bg-black flex flex-col" @click.away="vidModal = false; vidModalSrc = ''">
                <iframe :src="vidModalSrc" class="w-full h-full flex-1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div x-show="vidModalTitle" class="bg-gray-900 px-6 py-4 border-t border-white/10">
                    <p x-text="vidModalTitle" class="text-white text-base md:text-lg font-medium"></p>
                </div>
            </div>
        </div>
    </template>
</div>
@include('components.lightbox')
@include('components.feed-scripts')
@endsection
