@props(['stream'])

<div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5 stream-card transition-all duration-300" id="stream-card-{{ $stream->id }}" x-data="{ editingStream: false, openOptions: false }">
    <div class="flex justify-between items-start mb-2">
        <div class="flex items-center space-x-3">
            <a href="/{{ '@' . ($stream->user->username ?? '') }}" class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden flex-shrink-0 border border-neutral-200 hover:ring-2 hover:ring-red-700 transition">
                <img src="{{ $stream->user?->avatar_url ?? asset('assets/img/default.png') }}" class="w-full h-full object-cover">
            </a>
            <div>
                <h4 class="text-sm font-bold text-neutral-900">
                    <a href="/{{ '@' . ($stream->user->username ?? '') }}" class="hover:text-red-700 transition">{{ $stream->user->fullname ?? 'Unknown User' }}</a> 
                    @if($stream->type == 1 && !$stream->attachment)
                        <span class="font-normal text-neutral-500">memperbarui status</span>
                    @elseif($stream->type == 2)
                        <span class="font-normal text-neutral-500">mengunggah foto</span>
                    @elseif($stream->type == 3)
                        <span class="font-normal text-neutral-500">membagikan video</span>
                    @elseif($stream->app === 'events')
                        <span class="font-normal text-neutral-500">membuat acara</span>
                    @elseif($stream->app === 'blogs')
                        <span class="font-normal text-neutral-500">menulis artikel</span>
                    @else
                        <span class="font-normal text-neutral-500">memposting</span>
                    @endif
                </h4>
                <p class="text-[11px] text-neutral-400 flex flex-wrap items-center gap-x-1">
                    {{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }}
                    &bull;
                    @if($stream->privacy === 'private')
                        <svg class="w-3 h-3" title="Hanya Saya" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    @elseif($stream->privacy === 'friends')
                        <svg class="w-3 h-3" title="Teman" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    @else
                        <svg class="w-3 h-3" title="Publik" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                    @if($stream->app === 'group' && $stream->targetPage)
                        &bull; Mengunggah di Grup <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                    @elseif($stream->app === 'page' && $stream->targetPage)
                        &bull; Mengunggah di Halaman <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetPage->name }}</a>
                    @elseif($stream->app === 'feed' && $stream->wall_id != $stream->uid && $stream->targetWallUser)
                        &bull; Mengunggah di Profil <a href="{{ url('/@' . $stream->targetWallUser->username) }}" class="font-semibold text-neutral-600 hover:text-red-700 hover:underline">{{ $stream->targetWallUser->fullname ?? $stream->targetWallUser->username }}</a>
                    @endif
                </p>
            </div>
        </div>
        <!-- 3-dots dropdown -->
        <div class="relative">
            <button @click="openOptions = !openOptions" @click.away="openOptions = false" class="text-neutral-400 hover:text-neutral-600 focus:outline-none mt-1">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
            </button>
            <div x-show="openOptions" style="display: none;" class="absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg border border-neutral-100 z-50 overflow-hidden">
                @php
                    $isAuthor = auth()->check() && ((int) auth()->id() === (int) $stream->uid);
                    $isAdmin = auth()->check() && (auth()->user()->isAdmin());
                    $isGroupOwner = auth()->check() && (($stream->app === 'group' && $stream->targetPage && (int) $stream->targetPage->uid === (int) auth()->id()) || ($stream->targetGroup && (int) $stream->targetGroup->uid === (int) auth()->id()));
                    $isPageOwner = auth()->check() && $stream->app === 'page' && $stream->targetPage && (int) $stream->targetPage->uid === (int) auth()->id();
                    $canDelete = $isAuthor || $isAdmin || $isGroupOwner || $isPageOwner;
                @endphp
                @if($isAuthor)
                    <button @click="openOptions = false; editingStream = true" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition">Edit</button>
                @endif
                @if($canDelete)
                    <form action="{{ route('stream.destroy', $stream->id) }}" method="POST" class="block w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Hapus</button>
                    </form>
                @endif
                <button type="button" @click="openOptions = false; openReportModal('{{ url('/stream/'.$stream->id) }}')" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition {{ $canDelete ? 'border-t border-neutral-100' : '' }}">Lapor</button>
            </div>
        </div>
    </div>

    @if($stream->message)
        <p x-show="!editingStream" class="text-sm text-neutral-800 mb-4 whitespace-pre-wrap leading-relaxed">{{ $stream->message }}</p>
        <form x-show="editingStream" style="display: none;" action="{{ route('stream.update', $stream->id) }}" method="POST" class="mb-4">
            @csrf
            @method('PUT')
            <textarea name="message" rows="3" class="w-full text-sm p-3 border border-neutral-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-2">{{ $stream->message }}</textarea>
            <div class="flex justify-end space-x-2">
                <button type="button" @click="editingStream = false" class="px-3 py-1.5 text-xs font-semibold text-neutral-600 hover:bg-neutral-100 rounded-md transition">Batal</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md transition">Simpan</button>
            </div>
        </form>
    @else
        <div x-show="editingStream" style="display: none;" class="mb-4">
            <form action="{{ route('stream.update', $stream->id) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="message" rows="3" class="w-full text-sm p-3 border border-neutral-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-2" placeholder="Tambahkan caption..."></textarea>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="editingStream = false" class="px-3 py-1.5 text-xs font-semibold text-neutral-600 hover:bg-neutral-100 rounded-md transition">Batal</button>
                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md transition">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    @if($stream->app === 'music' && $stream->attachment)
        <div class="mb-4 bg-neutral-50 rounded-xl border border-neutral-200 p-4 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h5 class="text-sm font-bold text-neutral-800 truncate mb-2">{{ $stream->message ?: 'Lagu tanpa judul' }}</h5>
                <audio controls class="w-full h-8 outline-none" preload="none">
                    <source src="{{ asset('storage/music/' . $stream->attachment) }}" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>
        </div>
    @endif

    @php
        $att = $stream->parsed_attachment;
        $photosList = $stream->photo_urls;
    @endphp

    @if(count($photosList) > 0)
        @php
            $ptCount = count($photosList);
            $jsonPhotos = json_encode($photosList);
        @endphp
        <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200 post-photo-wrapper relative select-none">
            @if($ptCount == 1)
                <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-auto max-h-[500px] object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
            @elseif($ptCount == 2)
                <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                    <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    <img src="{{ $photosList[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                </div>
            @elseif($ptCount == 3)
                <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
                    <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    <div class="grid grid-rows-2 gap-1 h-full">
                        <img src="{{ $photosList[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                        <img src="{{ $photosList[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    </div>
                </div>
            @elseif($ptCount >= 4)
                <div class="grid grid-cols-2 grid-rows-2 gap-1 h-72 sm:h-96">
                    <img src="{{ $photosList[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    <img src="{{ $photosList[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    <img src="{{ $photosList[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
                    <div class="relative w-full h-full" onclick='openLightbox({!! $jsonPhotos !!}, 3)'>
                        <img src="{{ $photosList[3] }}" onerror="this.onerror=null; this.closest('.post-photo-wrapper')?.remove();" class="w-full h-full object-cover cursor-pointer" alt="Post Photo">
                        @if($ptCount > 4)
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center cursor-pointer hover:bg-black/50 transition">
                                <span class="text-white text-3xl font-bold">+{{ $ptCount - 4 }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(($stream->type == 3 || in_array($stream->app, ['video', 'videos'])) && $att)
        @php
            $videoUrl = $att['video_url'] ?? null;
            $videoId = $att['video_id'] ?? null;
            if (!$videoId && !empty($att['uri']) && preg_match('/(?:videos\/viewstory\/|\/)(\d+)$/', $att['uri'], $vm)) {
                $videoId = $vm[1];
            }
            if (!$videoId && ($videoUrl || $stream->id)) {
                $vFound = \App\Models\Video::where(function($q) use ($stream, $videoUrl) {
                    $q->where('stream_id', $stream->id);
                    if ($videoUrl) {
                        $q->orWhere(function($sq) use ($stream, $videoUrl) {
                            $sq->where('uid', $stream->uid)->where('var1', $videoUrl);
                        });
                        if (preg_match('/(?:youtu\.be\/|v=)([^"&?\/\s]{11})/', $videoUrl, $yid)) {
                            $q->orWhere(function($sq) use ($stream, $yid) {
                                $sq->where('uid', $stream->uid)->where('var1', 'like', '%' . $yid[1] . '%');
                            });
                        }
                    }
                })->first();
                if ($vFound) {
                    $videoId = $vFound->id;
                }
            }
            $embedUrl = '';
            if ($videoUrl && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
            } elseif ($videoId) {
                $vidModel = \App\Models\Video::find($videoId);
                if ($vidModel && $vidModel->youtube_embed_url) {
                    $embedUrl = $vidModel->youtube_embed_url;
                }
            }
        @endphp
        <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
            @if($embedUrl)
                <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @if($videoId)
                    <div class="p-2.5 bg-neutral-50 border-t border-neutral-100 flex items-center justify-between">
                        <span class="text-xs text-neutral-600 font-semibold truncate">{{ $att['title'] ?? ($att['name'] ?? 'Video') }}</span>
                        <a href="{{ url('/videos/' . $videoId . '/watch') }}" class="text-xs text-red-600 hover:text-red-800 font-bold hover:underline shrink-0 flex items-center gap-1">
                            Tonton di Pemutar Video &rarr;
                        </a>
                    </div>
                @endif
            @elseif($videoId)
                <a href="{{ url('/videos/' . $videoId . '/watch') }}" class="flex items-center gap-3 p-4 bg-neutral-50 hover:bg-neutral-100 transition group">
                    <div class="w-12 h-12 rounded-lg bg-red-600 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-neutral-800 group-hover:text-red-700 transition">{{ $att['title'] ?? ($att['name'] ?? 'Tonton Video') }}</h5>
                        <span class="text-xs text-neutral-500">Klik untuk memutar video</span>
                    </div>
                </a>
            @elseif($videoUrl)
                <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tonton Video
                </a>
            @endif
        </div>
    @endif

    @if($stream->app === 'blogs' && $att && !empty($att['name']))
        <div class="mb-4 rounded-xl border border-neutral-200 bg-neutral-50 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-neutral-200 flex items-center justify-center text-neutral-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] font-bold text-neutral-400 tracking-wider uppercase">ARTIKEL BLOG</span>
                <h5 class="text-sm font-bold text-neutral-800 truncate">{{ $att['name'] }}</h5>
            </div>
        </div>
    @endif

    @if($stream->app === 'events' && $att && !empty($att['name']))
        <div class="mb-4 rounded-xl border border-red-100 bg-red-50/50 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] font-bold text-red-600 tracking-wider uppercase">ACARA / EVENT</span>
                <h5 class="text-sm font-bold text-neutral-800 truncate">{{ $att['name'] }}</h5>
            </div>
        </div>
    @endif

    <div class="flex items-center space-x-4 mt-3">
        <button type="button" onclick="document.getElementById('comment-form-home-{{ $stream->id }}').classList.toggle('hidden')" class="btn-press flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> <span id="comments-count-{{ $stream->id }}">{{ $stream->comments->count() ?? 0 }}</span>&nbsp;Komentar
        </button>
        <form action="{{ route('like.toggle', $stream->id) }}" method="POST" class="form-like">
            @csrf
            <button type="submit" class="btn-press like-btn flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium group/like">
                <svg class="like-icon w-4 h-4 mr-1.5 transition-transform duration-200 group-hover/like:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> <span id="like-count-{{ $stream->id }}">{{ $stream->likes }}</span>&nbsp;Suka
            </button>
        </form>
    </div>

    <!-- Comment Section -->
    <div id="comment-form-home-{{ $stream->id }}" class="hidden mt-3 pt-3 border-t border-neutral-100">
        <div id="comments-list-{{ $stream->id }}" class="bg-neutral-50 rounded-lg p-4 mb-3 space-y-3 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-neutral-200">
            @foreach($stream->comments as $comment)
                <div class="flex gap-2" id="comment-{{ $comment->id }}">
                    <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0 border border-neutral-200">
                        <img src="{{ $comment->user?->avatar_url ?? asset('assets/img/default.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div x-data="{ editing: false, openCommentOptions: false }" class="flex-1">
                        <div class="flex items-start gap-2 group">
                            <!-- Comment Bubble -->
                            <div x-show="!editing" class="bg-white px-3 py-2 rounded-2xl border border-neutral-100 shadow-sm text-sm break-words max-w-[85%]">
                                <span class="font-bold text-neutral-900 mr-1">{{ $comment->user?->fullname ?? 'Unknown' }}</span>
                                @php 
                                    $parsedMessage = htmlspecialchars($comment->message);
                                    $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);
                                @endphp
                                <span class="text-neutral-700 comment-text" id="comment-text-{{ $comment->id }}">{!! $parsedMessage !!}</span>
                            </div>

                            <!-- Form Edit Komentar -->
                            <form x-show="editing" style="display: none;" action="{{ route('comment.update', $comment->id) }}" method="POST" class="flex gap-2 w-full max-w-sm form-comment-edit" data-comment-id="{{ $comment->id }}">
                                @csrf
                                @method('PUT')
                                <input type="text" name="message" value="{{ $comment->message }}" class="flex-1 rounded-full bg-white border border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-3 py-1 outline-none" required>
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-3 py-1 text-xs font-bold transition-colors">Simpan</button>
                                <button type="button" @click="editing = false" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-600 rounded-full px-3 py-1 text-xs font-bold transition-colors">Batal</button>
                            </form>

                            <!-- Options Dropdown -->
                            <div class="relative mt-1" x-show="!editing">
                                <button @click="openCommentOptions = !openCommentOptions" @click.away="openCommentOptions = false" type="button" class="text-neutral-400 hover:text-neutral-600 transition p-1 rounded-full hover:bg-neutral-200 focus:outline-none" title="Opsi Komentar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                                
                                <div x-show="openCommentOptions" style="display: none;" class="absolute left-0 top-6 mt-1 w-24 bg-white border border-neutral-200 rounded-md shadow-lg z-20 py-1">
                                    @php
                                        $isCommentAuthor = auth()->check() && ((int) auth()->id() === (int) $comment->uid);
                                        $isStreamAuthor = auth()->check() && ((int) auth()->id() === (int) $stream->uid);
                                        $canDeleteComment = $isCommentAuthor || $isStreamAuthor || ($isAdmin ?? (auth()->check() && auth()->user()->isAdmin()));
                                    @endphp
                                    @if($isCommentAuthor)
                                        <button type="button" @click="editing = true; openCommentOptions = false" class="block px-3 py-1.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 w-full text-left">Edit</button>
                                    @endif
                                    
                                    @if($canDeleteComment)
                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="form-comment-delete" data-comment-id="{{ $comment->id }}" data-stream-id="{{ $stream->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus komentar ini?')" class="block px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 w-full text-left">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" onclick="openReportModal('{{ route('stream.show', $stream->id) }}#comment-{{ $comment->id }}')" class="block px-3 py-1.5 text-xs text-yellow-600 hover:bg-yellow-50 w-full text-left">
                                        Laporkan
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Bawah Bubble -->
                        <div class="flex items-center gap-3 mt-1 ml-2">
                            <p class="text-[10px] text-neutral-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Baru saja' }}</p>
                            @if(auth()->check())
                                <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ addslashes($comment->user?->username ?? 'user') }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 transition-colors uppercase tracking-wider">Balas</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <form action="{{ route('comment.store', $stream->id) }}" method="POST" class="flex gap-2 form-comment" data-stream-id="{{ $stream->id }}">
            @csrf
            <input type="text" name="message" id="comment-input-{{ $stream->id }}" required placeholder="Tulis komentar..." class="flex-1 rounded-full bg-neutral-50 border border-neutral-200 focus:bg-white focus:border-red-300 focus:ring-2 focus:ring-red-100 text-sm px-4 py-2 transition-all outline-none">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shrink-0 transition-colors flex items-center justify-center w-9 h-9 shadow-sm">
                <svg class="w-4 h-4 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>
</div>
