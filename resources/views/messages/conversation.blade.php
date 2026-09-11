@extends('layouts.app')

@section('title', 'Konversi')

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
                                onclick="showContextMenu(event, this)">

                                @if($msg->reply_to && $msg->replied_message)
                                    <div class="mb-2 {{ $isMine ? 'bg-white/15' : 'bg-gray-50' }} rounded-lg px-3 py-2 border-l-[3px] {{ $isMine ? 'border-white/40' : 'border-[#b71c1c]' }}">
                                        <p class="text-[10px] font-bold {{ $isMine ? 'text-white/90' : 'text-[#b71c1c]' }}">{{ $msg->replied_sender_name }}</p>
                                        <p class="text-[10px] {{ $isMine ? 'text-white/70' : 'text-gray-500' }} truncate">{{ Str::limit($msg->replied_message, 80) }}</p>
                                    </div>
                                @endif

                                <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
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

                <form action="{{ route('messages.store') }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">
                    <input type="hidden" name="reply_to" id="replyToInput" value="">
                    <input type="text" name="message" id="messageInput" placeholder="Ketik pesan..." required
                        class="flex-grow px-4 py-3 bg-gray-100 rounded-[14px] text-sm focus:outline-none focus:ring-2 focus:ring-[#b71c1c]">
                    <button type="submit" class="bg-[#b71c1c] hover:bg-red-800 text-white px-6 py-3 rounded-[14px] text-sm font-bold transition">
                        Kirim
                    </button>
                </form>
            </div>
        </div>

    </div>
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
        var justify = isMine ? 'justify-end' : 'justify-start';
        var timeAlign = isMine ? 'justify-end' : 'justify-start';
        var time = formatTime(message.created);

        var html = '<div class="flex ' + justify + ' mb-2">'
            + '<div class="max-w-[70%]">'
            + '<div class="chat-bubble ' + bubbleClass + ' rounded-[14px] px-4 py-3 shadow-sm cursor-pointer select-none"'
            + ' data-id="' + message.id + '"'
            + ' data-from="' + message.from_id + '"'
            + ' data-message="' + escapeHtml(message.message) + '"'
            + ' onclick="showContextMenu(event, this)">'
            + '<p class="text-sm whitespace-pre-wrap">' + escapeHtml(message.message) + '</p>'
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
                    check.className = 'read-check text-[10px] text-[#b71c1c]';
                    check.innerHTML = '&#10003;&#10003;';
                    timeDiv.appendChild(check);
                }
            }
        });
    }

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
            req.send();
        }

        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.message.sent', function(e) {
                if (e.message.from_id == otherUserId || e.message.to_id == otherUserId) {
                    appendBubble(e.message, e.sender);
                    markAsRead();
                }
                playNotifSound();
            })
            .listen('.message.read', function(e) {
                if (e.senderId == currentUserId && e.readerId == otherUserId) {
                    updateReadReceipts();
                }
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

        var menuWidth = contextMenu.offsetWidth;
        var menuHeight = contextMenu.offsetHeight;
        if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
        if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

        contextMenu.style.left = x + 'px';
        contextMenu.style.top = y + 'px';
    }

    document.addEventListener('click', function() { contextMenu.classList.add('hidden'); });
    document.addEventListener('contextmenu', function() { contextMenu.classList.add('hidden'); });

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
