@extends('layouts.admin')
@section('title', 'Stream Monitor - Admin Panel XCODE')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a> &gt;
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">ADMIN CP</a> &gt; STREAM MONITOR
                </div>
                <h1 class="text-3xl font-black text-gray-900">STREAM MONITOR</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau seluruh aktivitas postingan, media, dan teks di seluruh jaringan.</p>
            </div>

            <div class="w-full sm:w-72 mt-4 sm:mt-0">
                <form action="{{ route('admin.stream-monitor') }}" method="GET" class="relative">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari konten atau username..." class="w-full bg-white border border-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block pl-10 pr-4 py-2.5 shadow-sm transition-colors">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.stream-monitor', request()->except('search')) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        

        {{-- Type Filter --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.stream-monitor', request()->except('type', 'page')) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ !request('type') ? 'bg-gray-800 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                Semua Postingan ({{ number_format($typeStats['all']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 'text'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ in_array(request('type'), ['1', 'text']) ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
                <i data-lucide="file-text" class="w-4 h-4 inline-block mr-1"></i> Teks ({{ number_format($typeStats['text']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 'photo'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ in_array(request('type'), ['2', 'photo']) ? 'bg-pink-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200' }}">
                <i data-lucide="image" class="w-4 h-4 inline-block mr-1"></i> Foto ({{ number_format($typeStats['photo']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 'video'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ in_array(request('type'), ['3', 'video']) ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200' }}">
                <i data-lucide="video" class="w-4 h-4 inline-block mr-1"></i> Video ({{ number_format($typeStats['video']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 'blog'])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ request('type') == 'blog' ? 'bg-purple-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-purple-50 hover:text-purple-600 hover:border-purple-200' }}">
                <i data-lucide="book-open" class="w-4 h-4 inline-block mr-1"></i> Blog ({{ number_format($typeStats['blog']) }})
            </a>
        </div>

        <div class="flex items-center justify-between mb-4 bg-white px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-600 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="font-bold text-gray-800">Live Monitor Aktif</span>
                <span class="text-gray-400">&bull; Postingan baru dari pengguna akan muncul secara realtime tanpa refresh.</span>
            </div>
        </div>

        {{-- Stream Feed --}}
        <div class="space-y-4" id="admin-stream-feed">
            @forelse($streams as $stream)
                @php
                    $att = $stream->parsed_attachment;
                    $photoUrls = $stream->photo_urls;
                    $videoUrl = $att['video_url'] ?? null;
                    $ytEmbed = null;
                    $vstoryId = null;
                    $videoRecord = null;
                    // 1. Ekstrak YouTube Embed langsung dari videoUrl atau attachment
                    if ($videoUrl && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $vm)) {
                        $ytEmbed = 'https://www.youtube.com/embed/' . $vm[1];
                    } elseif ($stream->attachment && str_starts_with($stream->attachment, 'youtube:')) {
                        $ytEmbed = 'https://www.youtube.com/embed/' . str_replace('youtube:', '', $stream->attachment);
                    }

                    // 2. Cari ID Video di jcow_stories (vstoryId)
                    if (!empty($att['video_id'])) {
                        $vstoryId = $att['video_id'];
                    } elseif (!empty($att['uri']) && preg_match('/(?:videos\/viewstory\/|\/)(\d+)$/', $att['uri'], $vsm)) {
                        $vstoryId = $vsm[1];
                    } elseif ($stream->type == 3 || in_array($stream->app, ['video', 'videos']) || $videoUrl) {
                        // Cari record video berdasarkan stream_id atau kecocokan video_url dengan uid
                        $videoRecord = \App\Models\Video::where(function($q) use ($stream, $videoUrl) {
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
                        if ($videoRecord) {
                            $vstoryId = $videoRecord->id;
                        }
                    }

                    // 3. Ambil data record Video jika ada vstoryId
                    if ($vstoryId && !$videoRecord) {
                        $videoRecord = \App\Models\Video::find($vstoryId);
                    }

                    // 4. Resolve ytEmbed dari videoRecord jika belum ada
                    if (!$ytEmbed && $videoRecord && $videoRecord->youtube_embed_url) {
                        $ytEmbed = $videoRecord->youtube_embed_url;
                    }
                    $isVid = $stream->type == 3 || in_array($stream->app, ['video', 'videos']) || $ytEmbed !== null;
                    $isPic = count($photoUrls) > 0 || $stream->type == 2 || in_array($stream->app, ['photo', 'photos']);
                    $isBlog = $stream->app === 'blogs';
                    $hasUser = (bool) $stream->user;
                    $username = $stream->user?->username ?? 'user';
                    $fullname = $stream->user?->fullname ?: $username;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-gray-300 transition-colors" id="admin-stream-{{ $stream->id }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-start gap-3">
                            @if($hasUser)
                                <a href="{{ route('profile.show', $username) }}" class="shrink-0">
                                    <img src="{{ $stream->user?->avatar_url ?? asset('assets/img/default.png') }}" alt="{{ $username }}" class="w-10 h-10 rounded-full border border-gray-200 object-cover bg-gray-100 hover:opacity-90 transition">
                                </a>
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" alt="User" class="w-10 h-10 rounded-full border border-gray-200 object-cover bg-gray-100 shrink-0">
                            @endif
                            <div>
                                <div class="font-bold text-gray-900 text-sm flex items-center gap-2 flex-wrap">
                                    @if($hasUser)
                                        <a href="{{ route('profile.show', $username) }}" class="hover:underline hover:text-red-600 transition">{{ $fullname }}</a>
                                        <span class="text-xs font-normal text-gray-400">({{'@' . $username}})</span>
                                    @else
                                        <span class="text-gray-500 italic">Pengguna Dihapus</span>
                                    @endif
                                    
                                    @if($stream->app === 'group' && $stream->targetPage)
                                        <span class="text-xs font-normal text-gray-500">
                                            &bull; di Grup <a href="{{ url('/groups/' . $stream->targetPage->id) }}" class="font-bold text-gray-700 hover:text-red-600 hover:underline"><i data-lucide="users" class="w-3 h-3 inline"></i> {{ $stream->targetPage->name }}</a>
                                        </span>
                                    @elseif($stream->app === 'page' && $stream->targetPage)
                                        <span class="text-xs font-normal text-gray-500">
                                            &bull; di Halaman <a href="{{ url('/pages/' . $stream->targetPage->id) }}" class="font-bold text-gray-700 hover:text-red-600 hover:underline"><i data-lucide="flag" class="w-3 h-3 inline"></i> {{ $stream->targetPage->name }}</a>
                                        </span>
                                    @elseif($stream->app === 'feed' && $stream->wall_id && $stream->wall_id != $stream->uid && $stream->targetWallUser)
                                        <span class="text-xs font-normal text-gray-500">
                                            &bull; di Dinding <a href="{{ url('/@' . $stream->targetWallUser->username) }}" class="font-bold text-gray-700 hover:text-red-600 hover:underline"><i data-lucide="user" class="w-3 h-3 inline"></i> {{ $stream->targetWallUser->fullname ?? $stream->targetWallUser->username }}</a>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                    <span>{{ $stream->created_at->diffForHumans() }}</span>
                                    <span>&bull;</span>
                                    <span>ID: {{ $stream->id }}</span>
                                    <span>&bull;</span>
                                    @if($isVid)
                                        <span class="inline-flex items-center gap-1 text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="video" class="w-3 h-3"></i> Video</span>
                                    @elseif($isPic)
                                        <span class="inline-flex items-center gap-1 text-pink-600 font-bold bg-pink-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="image" class="w-3 h-3"></i> Foto {{ count($photoUrls) > 0 ? '('.count($photoUrls).')' : '' }}</span>
                                    @elseif($isBlog)
                                        <span class="inline-flex items-center gap-1 text-purple-600 font-bold bg-purple-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="book-open" class="w-3 h-3"></i> Blog</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="file-text" class="w-3 h-3"></i> Teks</span>
                                    @endif
                                    
                                    @if($stream->privacy)
                                        <span class="text-gray-400 capitalize text-[11px]">({{ $stream->privacy }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-1">
                            <a href="{{ url('/stream/' . $stream->id) }}" class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Buka Postingan">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.stream-monitor.destroy', $stream->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus Postingan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Content Message --}}
                    @if($stream->message)
                        <div class="text-gray-800 text-sm mb-3 whitespace-pre-wrap leading-relaxed">{!! strip_tags($stream->message, '<a><b><i><strong><em><br><p>') !!}</div>
                    @endif

                    {{-- Photos --}}
                    @if(count($photoUrls) > 0)
                        <div class="mt-3">
                            @if(count($photoUrls) === 1)
                                <a href="{{ $photoUrls[0] }}" target="_blank" class="inline-block max-w-xl rounded-xl overflow-hidden border border-gray-200 bg-gray-50 hover:opacity-95 transition" title="Klik untuk membuka ukuran penuh">
                                    <img src="{{ $photoUrls[0] }}" class="w-full max-h-96 object-contain rounded-xl" alt="Foto Postingan">
                                </a>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-w-2xl">
                                    @foreach($photoUrls as $pUrl)
                                        <a href="{{ $pUrl }}" target="_blank" class="aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 block hover:opacity-95 transition" title="Klik untuk membuka ukuran penuh">
                                            <img src="{{ $pUrl }}" class="w-full h-full object-cover" alt="Foto Postingan">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Videos / YouTube --}}
                    @if($ytEmbed)
                        <div class="mt-3 max-w-2xl rounded-xl overflow-hidden border border-gray-200 bg-black aspect-video">
                            <iframe class="w-full h-full rounded-xl" src="{{ $ytEmbed }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                        @if($vstoryId)
                            <div class="mt-2">
                                <a href="{{ url('/videos/' . $vstoryId . '/watch?ref=stream-monitor') }}" class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-800 font-semibold hover:underline">
                                    <i data-lucide="play-circle" class="w-3.5 h-3.5"></i>
                                    <span>Buka Halaman Pemutar Video &rarr;</span>
                                </a>
                            </div>
                        @endif
                    @elseif(!empty($att['video_url']))
                        <div class="mt-3 max-w-2xl">
                            <a href="{{ $att['video_url'] }}" target="_blank" class="inline-flex items-center gap-2.5 p-3.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition text-sm font-semibold">
                                <i data-lucide="play-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                                <span>{{ $att['title'] ?: $att['video_url'] }}</span>
                            </a>
                        </div>
                    @elseif($vstoryId || (!empty($att['uri']) && str_starts_with($att['uri'], 'videos/viewstory/')))
                        @php
                            if (!$vstoryId && !empty($att['uri'])) {
                                preg_match('/videos\/viewstory\/(\d+)/', $att['uri'], $vsm);
                                $vstoryId = $vsm[1] ?? null;
                            }
                        @endphp
                        <div class="mt-3 max-w-2xl">
                            <a href="{{ url('/videos/' . ($vstoryId ?: '') . '/watch?ref=stream-monitor') }}" class="inline-flex items-center gap-2.5 p-3.5 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition text-sm font-semibold">
                                <i data-lucide="video" class="w-5 h-5 text-red-600 shrink-0"></i>
                                <span>{{ $att['name'] ?? ($videoRecord?->title ?? 'Lihat Video') }}</span>
                            </a>
                        </div>
                    @endif

                    {{-- Blogs --}}
                    @if($stream->app === 'blogs' && $att && !empty($att['name']))
                        <div class="mt-3 max-w-xl rounded-xl border border-purple-200 bg-purple-50/50 p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-bold text-purple-600 tracking-wider uppercase">ARTIKEL BLOG</span>
                                <h5 class="text-sm font-bold text-gray-900 truncate">{{ $att['name'] }}</h5>
                            </div>
                        </div>
                    @endif

                    {{-- Footer info --}}
                    <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-gray-400"></i> {{ $stream->comments()->count() }} Komentar
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="heart" class="w-3.5 h-3.5 text-gray-400"></i> {{ $stream->likes }} Suka
                        </span>
                        <span>&bull;</span>
                        <a href="{{ url('/stream/' . $stream->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-medium ml-auto">
                            Buka di Feed <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i data-lucide="activity" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-sm text-gray-500">Tidak ada postingan yang sesuai dengan filter Anda.</p>
                </div>
            @endforelse
        </div>

        @if($streams->hasPages())
            <div class="mt-6">
                {{ $streams->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) {
        let _isSecure = window.location.protocol === 'https:';
        let _wsPort = window.location.port || (_isSecure ? 443 : 80);
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config("broadcasting.connections.reverb.key") }}',
            wsHost: window.location.hostname,
            wsPort: _wsPort,
            wssPort: _wsPort,
            forceTLS: _isSecure,
            enabledTransports: ['ws', 'wss'],
        });
    }

    const feed = document.getElementById('admin-stream-feed');
    if (window.Echo && feed) {
        window.Echo.channel('public-feed')
            .listen('.stream.created', (e) => {
                const streamId = e.stream_id;
                if (document.getElementById('admin-stream-' + streamId)) return;

                const username = e.username || 'user';
                const fullname = e.fullname || username;
                const avatar = e.avatar_url || '/assets/img/default.png';
                const message = (e.message || '').replace(/</g, "&lt;").replace(/>/g, "&gt;");
                const type = e.type || 1;

                let typeBadge = '<span class="inline-flex items-center gap-1 text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="file-text" class="w-3 h-3"></i> Teks</span>';
                if (type == 2) typeBadge = '<span class="inline-flex items-center gap-1 text-pink-600 font-bold bg-pink-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="image" class="w-3 h-3"></i> Foto</span>';
                else if (type == 3) typeBadge = '<span class="inline-flex items-center gap-1 text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded text-[11px]"><i data-lucide="video" class="w-3 h-3"></i> Video</span>';

                let attachmentHtml = '';
                let photoList = e.photo_urls || [];
                if (photoList.length === 0 && e.attachment) {
                    if (typeof e.attachment === 'string' && e.attachment.startsWith('youtube:')) {
                        const ytId = e.attachment.replace('youtube:', '');
                        attachmentHtml += `<div class="mt-3 max-w-2xl rounded-xl overflow-hidden border border-gray-200 bg-black aspect-video"><iframe class="w-full h-full rounded-xl" src="https://www.youtube.com/embed/${ytId}" frameborder="0" allowfullscreen></iframe></div>`;
                    } else {
                        try {
                            const parsed = JSON.parse(e.attachment);
                            if (parsed.video_url) {
                                let match = parsed.video_url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
                                if (match) {
                                    attachmentHtml += `<div class="mt-3 max-w-2xl rounded-xl overflow-hidden border border-gray-200 bg-black aspect-video"><iframe class="w-full h-full rounded-xl" src="https://www.youtube.com/embed/${match[1]}" frameborder="0" allowfullscreen></iframe></div>`;
                                }
                            } else if (parsed.photos && Array.isArray(parsed.photos)) {
                                photoList = parsed.photos.map(p => p.startsWith('http') ? p : '/storage/' + (p.startsWith('posts/') || p.startsWith('photos/') ? p : 'posts/' + p));
                            }
                        } catch (err) {
                            if (typeof e.attachment === 'string' && !e.attachment.startsWith('{')) {
                                photoList = ['/storage/posts/' + e.attachment];
                            }
                        }
                    }
                }

                if (photoList.length > 0) {
                    attachmentHtml += '<div class="mt-3">';
                    if (photoList.length === 1) {
                        attachmentHtml += `<a href="${photoList[0]}" target="_blank" class="inline-block max-w-xl rounded-xl overflow-hidden border border-gray-200 bg-gray-50"><img src="${photoList[0]}" class="w-full max-h-96 object-contain rounded-xl"></a>`;
                    } else {
                        attachmentHtml += '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-w-2xl">';
                        photoList.forEach(url => {
                            attachmentHtml += `<a href="${url}" target="_blank" class="aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 block"><img src="${url}" class="w-full h-full object-cover"></a>`;
                        });
                        attachmentHtml += '</div>';
                    }
                    attachmentHtml += '</div>';
                }

                const cardHtml = `
                    <div id="admin-stream-${streamId}" class="bg-white rounded-xl shadow-sm border-2 border-emerald-400 p-5 transition-all duration-500">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-start gap-3">
                                <a href="/@${username}" class="shrink-0">
                                    <img src="${avatar}" alt="${username}" class="w-10 h-10 rounded-full border border-gray-200 object-cover bg-gray-100 hover:opacity-90 transition">
                                </a>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm flex items-center gap-2 flex-wrap">
                                        <a href="/@${username}" class="hover:underline hover:text-red-600 transition">${fullname}</a>
                                        <span class="text-xs font-normal text-gray-400">(@${username})</span>
                                        <span class="ml-2 px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-700 font-bold rounded-full">BARU SAJA</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                        <span>Baru saja</span>
                                        <span>&bull;</span>
                                        <span>ID: ${streamId}</span>
                                        <span>&bull;</span>
                                        ${typeBadge}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1">
                                <a href="/stream/${streamId}" class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Buka Postingan">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                                <form action="/admin/stream-monitor/${streamId}" method="POST" onsubmit="return confirm('Hapus postingan ini secara permanen?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus Postingan">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="text-gray-800 text-sm mb-3 whitespace-pre-wrap leading-relaxed">${message}</div>
                        ${attachmentHtml}
                    </div>
                `;

                feed.insertAdjacentHTML('afterbegin', cardHtml);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
    }
});
</script>
@endpush
@endsection
