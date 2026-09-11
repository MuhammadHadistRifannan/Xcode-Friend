 @forelse ($streams as $stream)
 <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5" x-data="{ editingStream: false, openOptions: false }">
 <div class="flex justify-between items-start mb-2">
 <div class="flex items-center space-x-3">
 <a href="/{{ '@' . ($stream->user->username ?? '') }}" class="w-10 h-10 rounded-full bg-neutral-100 overflow-hidden flex-shrink-0 border border-neutral-200 hover:ring-2 hover:ring-red-700 transition">
 <img src="{{ $stream->user->avatar_url }}" class="w-full h-full object-cover">
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
 @if(auth()->check() && (auth()->id() === $stream->uid))
 <button @click="openOptions = false; editingStream = true" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition">Edit</button>
 <form action="{{ route('stream.destroy', $stream->id) }}" method="POST" class="block w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
 @csrf
 @method('DELETE')
 <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Hapus</button>
 </form>
 <button type="button" @click="openOptions = false; openReportModal('{{ url('/stream/'.$stream->id) }}')" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition border-t border-neutral-100">Lapor</button>
 @else
 <button type="button" @click="openOptions = false; openReportModal('{{ url('/stream/'.$stream->id) }}')" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition">Lapor</button>
 @endif
 </div>
 </div>
 </div>

 @if($stream->message)
 <div x-show="!editingStream" x-data="{ expanded: false, isLong: false }" x-init="$nextTick(() => { isLong = $refs.msg.scrollHeight > 100 })" class="mb-4">
    <p x-ref="msg" :class="expanded ? '' : 'line-clamp-4'" class="text-sm text-neutral-800 whitespace-pre-wrap leading-relaxed break-words break-all sm:break-words">{{ $stream->message }}</p>
    <button x-show="isLong && !expanded" style="display: none;" @click="expanded = true" class="text-red-600 hover:underline text-xs font-bold mt-1">Selengkapnya</button>
    <button x-show="isLong && expanded" style="display: none;" @click="expanded = false" class="text-red-600 hover:underline text-xs font-bold mt-1">Sembunyikan</button>
 </div>
 <form x-show="editingStream" style="display: none;" action="{{ route('stream.update', $stream->id) }}" method="POST" class="mb-4 ">
 @csrf
 @method('PUT')
 <textarea name="message" rows="3" class="w-full text-sm p-3 border border-neutral-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-2">{{ $stream->message }}</textarea>
 <div class="flex justify-end space-x-2">
 <button type="button" @click="editingStream = false" class="px-3 py-1.5 text-xs font-semibold text-neutral-600 hover:bg-neutral-100 rounded-md transition">Batal</button>
 <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md transition">Simpan</button>
 </div>
 </form>
 @else
 <div x-show="editingStream" style="display: none;" class="mb-4 ">
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
 
 @if($stream->type == 4 && $stream->attachment)
 <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200 bg-neutral-50 p-4 flex items-center space-x-4">
    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path></svg>
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

 @if($stream->type == 2 && $stream->attachment)
 @php $att = json_decode($stream->attachment, true); @endphp
 @if(isset($att['photos']) && is_array($att['photos']))
 @php 
 $ptCount = count($att['photos']); 
 $photoUrls = array_map(fn($p) => asset('storage/posts/' . $p), $att['photos']);
 $jsonPhotos = json_encode($photoUrls);
 @endphp
 <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
 @if($ptCount == 1)
 <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-auto max-h-[500px] object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 @elseif($ptCount == 2)
 <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
 <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 </div>
 @elseif($ptCount == 3)
 <div class="grid grid-cols-2 gap-1 h-64 sm:h-80">
 <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <div class="grid grid-rows-2 gap-1 h-full">
 <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 </div>
 </div>
 @elseif($ptCount >= 4)
 <div class="grid grid-cols-2 grid-rows-2 gap-1 h-72 sm:h-96">
 <img src="{{ $photoUrls[0] }}" onclick='openLightbox({!! $jsonPhotos !!}, 0)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <img src="{{ $photoUrls[1] }}" onclick='openLightbox({!! $jsonPhotos !!}, 1)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <img src="{{ $photoUrls[2] }}" onclick='openLightbox({!! $jsonPhotos !!}, 2)' class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 <div class="relative w-full h-full" onclick='openLightbox({!! $jsonPhotos !!}, 3)'>
 <img src="{{ $photoUrls[3] }}" class="w-full h-full object-cover cursor-pointer" alt="Post Photo">
 @if($ptCount > 4)
 <div class="absolute inset-0 bg-black/60 flex items-center justify-center cursor-pointer hover:bg-black/50 transition">
 <span class="text-white text-3xl font-bold">+{{ $ptCount - 4 }}</span>
 </div>
 @endif
 </div>
 </div>
 @endif
 </div>
 @elseif(isset($att['photo']))
 <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
 @php $singlePhoto = json_encode([asset('storage/posts/' . $att['photo'])]); @endphp
 <img src="{{ asset('storage/posts/' . $att['photo']) }}" onclick='openLightbox({!! $singlePhoto !!}, 0)' class="w-full h-auto cursor-pointer hover:opacity-95 transition" alt="Post Photo">
 </div>
 @endif
 @endif
 
 @if($stream->type == 3 && $stream->attachment)
 @php $att = json_decode($stream->attachment, true); @endphp
 @if(isset($att['video_url']))
 @php
 $videoUrl = $att['video_url'];
 $embedUrl = '';
 if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $matches)) {
 $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
 }
 @endphp
 <div class="mb-4 rounded-xl overflow-hidden border border-neutral-200">
 @if($embedUrl)
 <iframe src="{{ $embedUrl }}" class="w-full h-[300px]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
 @else
 <a href="{{ $videoUrl }}" target="_blank" class="text-blue-600 hover:underline flex items-center p-3 bg-neutral-50"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Tonton Video</a>
 @endif
 </div>
 @endif
 @endif

 <div class="flex items-center space-x-4 mt-3">
 <button type="button" onclick="document.getElementById('comment-form-home-{{ $stream->id }}').classList.toggle('hidden')" class="flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium">
 <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg> <span id="comments-count-{{ $stream->id }}">{{ $stream->comments->count() ?? 0 }}</span>&nbsp;Komentar
 </button>
 <form action="{{ route('like.toggle', $stream->id) }}" method="POST" class="form-like">
 @csrf
 <button type="submit" class="flex items-center text-xs text-neutral-500 hover:text-red-700 transition font-medium">
 <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg> <span id="like-count-{{ $stream->id }}">{{ $stream->likes }}</span>&nbsp;Suka
 </button>
 </form>
 </div>

 <!-- Comment Section -->
 <div id="comment-form-home-{{ $stream->id }}" class="hidden mt-3 pt-3 border-t border-neutral-100 ">
 <div id="comments-list-{{ $stream->id }}" class="bg-neutral-50 rounded-lg p-4 mb-3 space-y-3 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-neutral-200">
 @foreach($stream->comments as $comment)
 <div class="flex gap-2" id="comment-{{ $comment->id }}">
 <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0 border border-neutral-200">
 <img src="{{ $comment->user->avatar_url }}" class="w-full h-full object-cover">
 </div>
 <div x-data="{ editing: false, openCommentOptions: false }" class="flex-1">
 <div class="flex items-start gap-2 group">
 <!-- Comment Bubble -->
 <div x-show="!editing" class="bg-white px-3 py-2 rounded-2xl border border-neutral-100 shadow-sm text-sm break-words max-w-[85%]">
 <span class="font-bold text-neutral-900 mr-1">{{ $comment->user->fullname ?? 'Unknown' }}</span>
 @php 
 $parsedMessage = htmlspecialchars($comment->message);
 $parsedMessage = preg_replace('/@([a-zA-Z0-9_]+)/', '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>', $parsedMessage);
 @endphp
 <span class="text-neutral-700 break-words break-all sm:break-words">{!! $parsedMessage !!}</span>
 </div>

 <!-- Form Edit Komentar (Placeholder for future or logic) -->
 <form x-show="editing" style="display: none;" action="{{ route('comment.update', $comment->id) }}" method="POST" class="flex gap-2 w-full max-w-sm">
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
 
 <div x-show="openCommentOptions" style="display: none;"
 class="absolute left-0 top-6 mt-1 w-24 bg-white border border-neutral-200 rounded-md shadow-lg z-20 py-1">
 
 @if(auth()->check() && auth()->id() === $comment->uid)
 <button type="button" @click="editing = true; openCommentOptions = false" class="block px-3 py-1.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 w-full text-left">Edit</button>
 @endif
 
 @if(auth()->check() && (auth()->id() === $comment->uid))
 <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
 @csrf
 @method('DELETE')
 <button type="submit" onclick="return confirm('Hapus komentar ini?')" class="block px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 w-full text-left">
 Hapus
 </button>
 </form>
 @endif

 <button type="button" onclick="openReportModal('{{ url('/beranda#' . $comment->id) }}')" class="block px-3 py-1.5 text-xs text-yellow-600 hover:bg-yellow-50 w-full text-left">
 Laporkan
 </button>
 </div>
 </div>
 </div>
 <!-- Bawah Bubble -->
 <div class="flex items-center gap-3 mt-1 ml-2">
 <p class="text-[10px] text-neutral-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Baru saja' }}</p>
 @if(auth()->check())
 <button type="button" onclick="replyTo('{{ $stream->id }}', '{{ addslashes($comment->user->fullname ?? $comment->user->username ?? 'user') }}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 transition-colors uppercase tracking-wider">Balas</button>
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

 @if($loop->index == 0)
    @php $centerColumnHtml = \App\Helpers\SettingHelper::get('theme_block_center_column', ''); @endphp
    @if($centerColumnHtml)
        <div class="mb-4">{!! $centerColumnHtml !!}</div>
    @endif
 @endif

 @empty
 <div class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Tidak ada feed berita terbaru.</div>
 @endforelse

@if($streams->hasMorePages())
    <div id="infinite-scroll-trigger" class="flex justify-center py-6" data-next-url="{{ $streams->nextPageUrl() }}">
        <svg class="animate-spin h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
@endif
