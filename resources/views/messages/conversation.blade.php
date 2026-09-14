@extends('layouts.app')

@section('title', 'Konversi')

@push('styles')
<style>
    @keyframes chatBubblePop {
        0% {
            opacity: 0;
            transform: scale(0.9) translateY(8px);
        }
        70% {
            transform: scale(1.02) translateY(-2px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    .chat-bubble-pop {
        animation: chatBubblePop 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    @keyframes typingWave {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.35;
        }
        30% {
            transform: translateY(-5px);
            opacity: 1;
        }
    }
    .typing-wave-dot {
        animation: typingWave 1.2s infinite ease-in-out;
    }

    @keyframes readCheckPop {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        70% {
            transform: scale(1.4);
            opacity: 1;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    .read-check-pop {
        display: inline-block;
        animation: readCheckPop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    @keyframes menuPop {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    .menu-pop-in {
        animation: menuPop 0.15s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform-origin: top left;
    }
</style>
@endpush

@section('content')
<div class="pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto">

        <!-- Breadcrumb + Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-700 transition">HOME</a>
                <span>›</span>
                <a href="{{ route('messages.index') }}" class="hover:text-gray-700 transition">MESSAGES</a>
                <span>›</span>
                <span class="text-gray-700 font-medium">KONVERSI</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="w-10 h-10 rounded-full bg-[#f4dada] flex items-center justify-center text-[#b71c1c] font-bold">
                    {{ substr($otherUser->fullname ?? 'U', 0, 1) }}
                </div>
                <div>
                    @php
                        $lastSeenValue = $otherUser->last_seen ?? 0;
                        $lastLoginValue = $otherUser->lastlogin ?? 0;
                        $effectiveLastSeen = $lastSeenValue > 0 ? $lastSeenValue : $lastLoginValue;
                        $isOnline = $effectiveLastSeen > 0 && (time() - $effectiveLastSeen) < 300;
                    @endphp
                    <h1 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        {{ $otherUser->fullname ?? 'Unknown' }}
                        <span id="statusDot" class="inline-block w-2.5 h-2.5 rounded-full {{ $isOnline ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    </h1>
                    @if($isOnline)
                        <p id="onlineStatus" class="text-xs text-green-500 font-medium">Online</p>
                    @elseif($effectiveLastSeen > 0)
                        <p id="onlineStatus" class="text-xs text-gray-500">Terakhir online {{ \Carbon\Carbon::createFromTimestamp($effectiveLastSeen)->diffForHumans() }}</p>
                    @else
                        <p id="onlineStatus" class="text-xs text-gray-500">Offline</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[400px] h-[650px] shadow-sm overflow-hidden border border-gray-200"
             style="background-image: url('{{ asset('assets/img/background-chat.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

            <!-- Messages Area -->
            <div class="flex-grow p-4 overflow-y-auto" id="chatContainer">
                @forelse($messages as $msg)
                    @php
                        $isMine = $msg->from_id == Auth::id();
                    @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} mb-2">
                        <div class="max-w-[70%]">
                            <div class="chat-bubble {{ $isMine ? 'bg-[#b71c1c] text-white' : 'bg-white text-gray-900' }} rounded-[14px] px-4 py-3 shadow-sm cursor-pointer select-none"
                                data-id="{{ $msg->id }}"
                                data-from="{{ $msg->from_id }}"
                                data-message="{{ $msg->message }}"
                                data-attachment="{{ $msg->attachment ?? '' }}"
                                data-attachment-url="{{ !empty($msg->attachment) ? asset('storage/' . $msg->attachment) : '' }}"
                                onclick="showContextMenu(event, this)">

                                @if($msg->reply_to && $msg->replied_message)
                                    <div class="mb-2 {{ $isMine ? 'bg-white/15' : 'bg-gray-50' }} rounded-lg px-3 py-2 border-l-[3px] {{ $isMine ? 'border-white/40' : 'border-[#b71c1c]' }}">
                                        <p class="text-[10px] font-bold {{ $isMine ? 'text-white/90' : 'text-[#b71c1c]' }}">{{ $msg->replied_sender_name }}</p>
                                        <p class="text-[10px] {{ $isMine ? 'text-white/70' : 'text-gray-500' }} truncate">{{ Str::limit($msg->replied_message, 80) }}</p>
                                    </div>
                                @endif

                                @if(!empty($msg->attachment))
                                    <div class="mb-2 overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $msg->attachment) }}" 
                                             alt="Foto lampiran" 
                                             class="max-w-full max-h-64 object-cover rounded-lg hover:opacity-95 transition cursor-zoom-in"
                                             loading="lazy"
                                             onclick="openLightbox(event, '{{ asset('storage/' . $msg->attachment) }}')">
                                    </div>
                                @endif

                                @if(!empty($msg->message))
                                    <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <p class="text-[10px] text-gray-400">
                                    {{ \Carbon\Carbon::createFromTimestamp($msg->created)->format('H:i') }}
                                </p>
                                @if($isMine && $msg->hasread)
                                    <span class="read-check text-[10px] text-[#b71c1c]">&#10003;&#10003;</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <p class="text-gray-400 text-sm">Belum ada pesan. Mulai percakapan!</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white rounded-b-[24px] border-t border-gray-200">
                <!-- Reply Preview Bar -->
                <div id="replyPreview" class="hidden mb-3 bg-[#f6f3f3] rounded-xl px-4 py-3 flex items-start gap-3 border-l-4 border-[#b71c1c]">
                    <div class="flex-grow min-w-0">
                        <p class="text-xs font-bold text-[#b71c1c]" id="replySender"></p>
                        <p class="text-xs text-gray-500 truncate" id="replyText"></p>
                    </div>
                    <button type="button" onclick="cancelReply()" class="text-gray-400 hover:text-gray-600 flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Attachment Preview Bar -->
                <div id="attachmentPreview" class="hidden mb-3 bg-[#f6f3f3] rounded-xl p-2.5 flex items-center gap-3 border-l-4 border-[#b71c1c]">
                    <div class="relative w-14 h-14 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 border border-gray-300">
                        <img id="attachmentPreviewImg" src="" alt="Pratinjau Foto" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate" id="attachmentFileName">Foto dipilih</p>
                        <p class="text-[10px] text-gray-500" id="attachmentFileSize">0 KB</p>
                    </div>
                    <button type="button" onclick="cancelAttachment()" class="p-1 text-gray-400 hover:text-red-600 rounded-full hover:bg-gray-200 transition flex-shrink-0" title="Batal lampirkan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Typing Indicator -->
                <div id="typingIndicator" class="hidden mb-3 px-3.5 py-1.5 bg-white/95 backdrop-blur-sm rounded-full shadow-sm border border-gray-200/80 w-fit flex items-center gap-2.5 transition-all duration-300">
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-[#b71c1c] rounded-full typing-wave-dot" style="animation-delay: 0ms;"></span>
                        <span class="w-1.5 h-1.5 bg-[#b71c1c] rounded-full typing-wave-dot" style="animation-delay: 180ms;"></span>
                        <span class="w-1.5 h-1.5 bg-[#b71c1c] rounded-full typing-wave-dot" style="animation-delay: 360ms;"></span>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">{{ $otherUser->fullname }} sedang mengetik...</span>
                </div>

                <form action="{{ route('messages.store') }}" method="POST" id="chatForm" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">
                    <input type="hidden" name="reply_to" id="replyToInput" value="">
                    <input type="file" id="attachmentInput" name="attachment" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" class="hidden">
                    
                    <!-- Attach Image Button -->
                    <button type="button" id="attachBtn" onclick="document.getElementById('attachmentInput').click()" 
                        title="Kirim Foto" 
                        class="p-3 text-gray-500 hover:text-[#b71c1c] hover:bg-red-50 active:scale-95 rounded-full transition flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </button>

                    <input type="text" name="message" id="messageInput" placeholder="Ketik pesan..." 
                        class="flex-grow px-4 py-3 bg-gray-100 rounded-[14px] text-sm focus:outline-none focus:ring-2 focus:ring-[#b71c1c]">
                    
                    <button type="submit" id="chatSendBtn" class="bg-[#b71c1c] hover:bg-red-800 active:scale-95 text-white px-6 py-3 rounded-[14px] text-sm font-bold transition-all duration-150 flex-shrink-0 flex items-center gap-1.5">
                        <span>Kirim</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Lightbox Modal Fullscreen -->
<div id="lightboxModal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0" onclick="closeLightbox(event)">
    <button type="button" onclick="closeLightbox(event, true)" class="absolute top-4 right-4 text-white/80 hover:text-white p-2 rounded-full bg-black/40 hover:bg-black/70 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <a id="lightboxDownloadBtn" href="" download target="_blank" class="absolute top-4 left-4 text-white/80 hover:text-white p-2 rounded-full bg-black/40 hover:bg-black/70 transition flex items-center gap-2 text-xs font-semibold px-3 py-2" onclick="event.stopPropagation()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        <span>Unduh</span>
    </a>
    <img id="lightboxImg" src="" alt="Full Preview" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl transition-transform duration-300 scale-95" onclick="event.stopPropagation()">
</div>

<!-- Context Menu -->
<div id="contextMenu" class="hidden fixed bg-white rounded-xl shadow-xl border border-gray-200 py-2 min-w-[200px] z-50">
    <button onclick="replyMessage()" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Balas
    </button>
    <button onclick="copyMessage()" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        Salin Pesan
    </button>
    <button onclick="forwardMessage()" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
        Teruskan Pesan
    </button>
    <div class="border-t border-gray-100 my-1"></div>
    <button onclick="deleteMsg('self')" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Hapus untuk saya
    </button>
    <button onclick="deleteMsg('everyone')" id="deleteEveryoneBtn" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-3">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Hapus untuk semua
    </button>
</div>

<!-- Hidden Forms -->
<form id="deleteForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
<form id="deleteEveryoneForm" method="POST" style="display: none;">@csrf @method('POST')</form>

@push('scripts')
<script>
    var container = document.getElementById('chatContainer');
    var contextMenu = document.getElementById('contextMenu');
    var messageInput = document.getElementById('messageInput');
    var replyPreview = document.getElementById('replyPreview');
    var replySender = document.getElementById('replySender');
    var replyText = document.getElementById('replyText');
    var replyToInput = document.getElementById('replyToInput');
    var selectedMsg = { id: null, from: null, message: '' };
    var currentUserId = {{ Auth::id() }};
    var otherUserId = {{ $otherUser->id }};

    // ==== Online Status via AJAX ====
    var onlineStatusEl = document.getElementById('onlineStatus');
    var statusDotEl = document.getElementById('statusDot');

    function setOnlineStatus(online, text) {
        if (!onlineStatusEl) return;
        onlineStatusEl.textContent = text;
        if (online) {
            onlineStatusEl.classList.remove('text-gray-500');
            onlineStatusEl.classList.add('text-green-500', 'font-medium');
            if (statusDotEl) {
                statusDotEl.classList.remove('bg-gray-400');
                statusDotEl.classList.add('bg-green-500');
            }
        } else {
            onlineStatusEl.classList.remove('text-green-500', 'font-medium');
            onlineStatusEl.classList.add('text-gray-500');
            if (statusDotEl) {
                statusDotEl.classList.remove('bg-green-500');
                statusDotEl.classList.add('bg-gray-400');
            }
        }
    }

    // ==== Realtime online status via WebSocket presence ====
    function refreshOnlineStatus() {
        if (!onlineStatusEl) return;
        var isOnline = window.onlineUsers && window.onlineUsers[otherUserId] !== undefined;
        setOnlineStatus(isOnline, isOnline ? 'Online' : 'Terakhir online beberapa saat lalu');
    }

    refreshOnlineStatus();
    window.addEventListener('online-update', function(e) {
        if (e.detail.id === null || e.detail.id == otherUserId) {
            refreshOnlineStatus();
        }
    });

    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function formatTime(timestamp) {
        var d = new Date(timestamp * 1000);
        var h = d.getHours().toString().padStart(2, '0');
        var m = d.getMinutes().toString().padStart(2, '0');
        return h + ':' + m;
    }

    function appendBubble(message, sender) {
        if (!container) return;
        var isMine = message.from_id == currentUserId;
        var bubbleClass = isMine ? 'bg-[#b71c1c] text-white' : 'bg-white text-gray-900';
        var originClass = isMine ? 'origin-bottom-right' : 'origin-bottom-left';
        var justify = isMine ? 'justify-end' : 'justify-start';
        var timeAlign = isMine ? 'justify-end' : 'justify-start';
        var time = formatTime(message.created);

        var imgUrl = message.attachment_url || (message.attachment ? '{{ asset('storage') }}/' + message.attachment : null);
        var imgHtml = '';
        if (imgUrl) {
            imgHtml = '<div class="mb-2 overflow-hidden rounded-lg">'
                + '<img src="' + imgUrl + '" alt="Foto lampiran" class="max-w-full max-h-64 object-cover rounded-lg hover:opacity-95 transition cursor-zoom-in" loading="lazy" onclick="openLightbox(event, \'' + imgUrl + '\')">'
                + '</div>';
        }

        var textHtml = '';
        if (message.message && message.message.trim() !== '') {
            textHtml = '<p class="text-sm whitespace-pre-wrap">' + escapeHtml(message.message) + '</p>';
        }

        var html = '<div class="flex ' + justify + ' mb-2">'
            + '<div class="max-w-[70%]">'
            + '<div class="chat-bubble chat-bubble-pop ' + originClass + ' ' + bubbleClass + ' rounded-[14px] px-4 py-3 shadow-sm cursor-pointer select-none"'
            + ' data-id="' + message.id + '"'
            + ' data-from="' + message.from_id + '"'
            + ' data-message="' + escapeHtml(message.message || '') + '"'
            + ' data-attachment="' + (message.attachment || '') + '"'
            + ' data-attachment-url="' + (imgUrl || '') + '"'
            + ' onclick="showContextMenu(event, this)">'
            + imgHtml
            + textHtml
            + '</div>'
            + '<div class="flex items-center gap-2 mt-1 ' + timeAlign + '">'
            + '<p class="text-[10px] text-gray-400">' + time + '</p>'
            + '</div>'
            + '</div>'
            + '</div>';

        container.insertAdjacentHTML('beforeend', html);
        container.scrollTop = container.scrollHeight;
    }

    function updateReadReceipts() {
        var bubbles = container.querySelectorAll('.chat-bubble');
        bubbles.forEach(function(bubble) {
            if (bubble.dataset.from == currentUserId && !bubble.nextElementSibling?.querySelector('.read-check')) {
                var timeDiv = bubble.parentElement.querySelector('.flex.items-center');
                if (timeDiv && !timeDiv.querySelector('.read-check')) {
                    var check = document.createElement('span');
                    check.className = 'read-check read-check-pop text-[10px] text-[#b71c1c]';
                    check.innerHTML = '&#10003;&#10003;';
                    timeDiv.appendChild(check);
                }
            }
        });
    }

    // ==== Attachment File Picker & Preview ====
    var attachmentInput = document.getElementById('attachmentInput');
    var attachmentPreview = document.getElementById('attachmentPreview');
    var attachmentPreviewImg = document.getElementById('attachmentPreviewImg');
    var attachmentFileName = document.getElementById('attachmentFileName');
    var attachmentFileSize = document.getElementById('attachmentFileSize');

    if (attachmentInput) {
        attachmentInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan.');
                    cancelAttachment();
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar maksimal adalah 5 MB.');
                    cancelAttachment();
                    return;
                }
                var reader = new FileReader();
                reader.onload = function(e) {
                    attachmentPreviewImg.src = e.target.result;
                    attachmentFileName.textContent = file.name;
                    var kb = (file.size / 1024).toFixed(1);
                    attachmentFileSize.textContent = (kb > 1024) ? (kb / 1024).toFixed(2) + ' MB' : kb + ' KB';
                    attachmentPreview.classList.remove('hidden');
                    messageInput.focus();
                };
                reader.readAsDataURL(file);
            } else {
                cancelAttachment();
            }
        });
    }

    function cancelAttachment() {
        if (attachmentInput) attachmentInput.value = '';
        if (attachmentPreview) attachmentPreview.classList.add('hidden');
        if (attachmentPreviewImg) attachmentPreviewImg.src = '';
    }

    // ==== Fullscreen Lightbox Modal ====
    function openLightbox(e, src) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var modal = document.getElementById('lightboxModal');
        var img = document.getElementById('lightboxImg');
        var dl = document.getElementById('lightboxDownloadBtn');
        if (!modal || !img) return;
        img.src = src;
        if (dl) dl.href = src;
        modal.classList.remove('hidden');
        requestAnimationFrame(function() {
            modal.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        });
    }

    function closeLightbox(e, force) {
        if (e && !force && e.target.id !== 'lightboxModal') return;
        if (e) e.stopPropagation();
        var modal = document.getElementById('lightboxModal');
        var img = document.getElementById('lightboxImg');
        if (!modal || !img) return;
        modal.classList.add('opacity-0');
        img.classList.remove('scale-100');
        img.classList.add('scale-95');
        setTimeout(function() {
            modal.classList.add('hidden');
            img.src = '';
        }, 250);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(null, true);
        }
    });

    @auth
    if (window.Echo) {
        var msgSoundSrc = '{{ asset("bereal.mp3") }}';

        function playNotifSound() {
            var s = new Audio(msgSoundSrc);
            s.volume = 1;
            s.play().catch(function() {});
        }

        var markReadTimer = null;
        function markAsRead() {
            if (markReadTimer) return;
            markReadTimer = setTimeout(function() { markReadTimer = null; }, 3000);
            var req = new XMLHttpRequest();
            req.open('POST', '{{ route("messages.markAsRead", $otherUser->id) }}', true);
            req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            req.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            req.onreadystatechange = function() {
                if (req.readyState === 4 && req.status === 200) {
                    window.dispatchEvent(new CustomEvent('sync-unread-badges'));
                }
            };
            req.send();
        }

        // Sinkronisasi badge unread di navbar saat percakapan dibuka
        setTimeout(function() {
            window.dispatchEvent(new CustomEvent('sync-unread-badges'));
        }, 300);

        var typingTimeout = null;
        var typingIndicatorEl = document.getElementById('typingIndicator');

        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.message.sent', function(e) {
                if (e.message.from_id == otherUserId) {
                    appendBubble(e.message, e.sender);
                    markAsRead();
                    if (typingIndicatorEl) typingIndicatorEl.classList.add('hidden');
                }
                playNotifSound();
            })
            .listen('.message.read', function(e) {
                if (e.senderId == currentUserId && e.readerId == otherUserId) {
                    updateReadReceipts();
                }
            })
            .listenForWhisper('typing', function(e) {
                if (e.userId == otherUserId && typingIndicatorEl) {
                    typingIndicatorEl.classList.remove('hidden');
                    if (container) container.scrollTop = container.scrollHeight;
                    if (typingTimeout) clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(function() {
                        typingIndicatorEl.classList.add('hidden');
                    }, 2500);
                }
            });

        // Kirim whisper saat mengetik
        var lastWhisperTime = 0;
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                var now = Date.now();
                if (now - lastWhisperTime > 1500) {
                    lastWhisperTime = now;
                    window.Echo.private('user.' + otherUserId).whisper('typing', { userId: currentUserId });
                }
            });
        }
    }

    // AJAX Form Submit untuk Chat Instan Tanpa Refresh
    var chatForm = document.getElementById('chatForm');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var text = messageInput.value.trim();
            var hasFile = attachmentInput && attachmentInput.files && attachmentInput.files.length > 0;
            if (!text && !hasFile) return;

            var formData = new FormData(chatForm);

            var tempAttachmentUrl = null;
            if (hasFile) {
                try {
                    tempAttachmentUrl = URL.createObjectURL(attachmentInput.files[0]);
                } catch (err) {}
            }

            // Tampilkan bubble seketika (optimistic render)
            var tempMsg = {
                id: 'temp-' + Date.now(),
                from_id: currentUserId,
                to_id: otherUserId,
                message: text,
                attachment_url: tempAttachmentUrl,
                created: Math.floor(Date.now() / 1000)
            };
            appendBubble(tempMsg, { id: currentUserId, fullname: 'Saya' });

            messageInput.value = '';
            cancelAttachment();
            if (typeof cancelReply === 'function') cancelReply();

            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.status !== 'success') {
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            })
            .catch(function(err) {
                console.error(err);
            });
        });
    }
    @endauth

    function showContextMenu(e, el) {
        e.preventDefault();
        e.stopPropagation();

        selectedMsg.id = el.dataset.id;
        selectedMsg.from = el.dataset.from;
        selectedMsg.message = el.dataset.message;

        var deleteEveryoneBtn = document.getElementById('deleteEveryoneBtn');
        if (selectedMsg.from != currentUserId) {
            deleteEveryoneBtn.classList.add('hidden');
        } else {
            deleteEveryoneBtn.classList.remove('hidden');
        }

        var x = e.clientX;
        var y = e.clientY;

        contextMenu.classList.remove('hidden');
        contextMenu.classList.remove('menu-pop-in');
        void contextMenu.offsetWidth;
        contextMenu.classList.add('menu-pop-in');

        var menuWidth = contextMenu.offsetWidth;
        var menuHeight = contextMenu.offsetHeight;
        if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
        if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

        contextMenu.style.left = x + 'px';
        contextMenu.style.top = y + 'px';
    }

    document.addEventListener('click', function() {
        contextMenu.classList.add('hidden');
        contextMenu.classList.remove('menu-pop-in');
    });
    document.addEventListener('contextmenu', function() {
        contextMenu.classList.add('hidden');
        contextMenu.classList.remove('menu-pop-in');
    });

    function replyMessage() {
        contextMenu.classList.add('hidden');
        var senderName = selectedMsg.from == currentUserId ? 'Kamu' : '{{ $otherUser->fullname }}';
        replySender.textContent = senderName;
        replyText.textContent = selectedMsg.message;
        replyToInput.value = selectedMsg.id;
        replyPreview.classList.remove('hidden');
        messageInput.value = '';
        messageInput.focus();
    }

    function cancelReply() {
        replyPreview.classList.add('hidden');
        replyToInput.value = '';
    }

    function copyMessage() {
        contextMenu.classList.add('hidden');
        navigator.clipboard.writeText(selectedMsg.message).then(function() {
            alert('Pesan disalin!');
        });
    }

    function forwardMessage() {
        contextMenu.classList.add('hidden');
        alert('Fitur teruskan pesan segera hadir.');
    }

    function deleteMsg(type) {
        contextMenu.classList.add('hidden');
        if (type === 'self') {
            if (!confirm('Hapus pesan ini untuk anda?')) return;
            var form = document.getElementById('deleteForm');
            form.action = '{{ route('messages.destroy', ':id') }}'.replace(':id', selectedMsg.id);
            form.submit();
        } else {
            if (!confirm('Hapus pesan ini untuk semua orang?')) return;
            var form = document.getElementById('deleteEveryoneForm');
            form.action = '{{ route('messages.deleteForEveryone', ':id') }}'.replace(':id', selectedMsg.id);
            form.submit();
        }
    }
</script>
@endpush
@endsection
