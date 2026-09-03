<div class="flex items-center gap-4 px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $notification->hasread ? '' : 'bg-[#fafafa]' }}">
    <!-- Unread Indicator -->
    <div class="w-2 h-2 rounded-full {{ $notification->hasread ? 'bg-gray-300' : 'bg-[#b71c1c]' }} flex-shrink-0"></div>
    
    <!-- Icon -->
    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 
        {{ match($notification->subject) {
            'friend_request', 'friend_accepted' => 'bg-[#f4dada]',
            'new_message' => 'bg-[#e8f5e9]',
            'comment', 'like' => 'bg-[#fff3e0]',
            default => 'bg-gray-100',
        } }}">
        {!! match($notification->subject) {
            'friend_request', 'friend_accepted' => '<svg class="w-5 h-5 text-[#b71c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
            'new_message' => '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
            'comment' => '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>',
            'like' => '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>',
            default => '<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>',
        } !!}
    </div>
    
    <!-- Content -->
    <div class="flex-grow min-w-0">
        <p class="text-sm font-bold text-gray-900">
            {{ match($notification->subject) {
                'friend_request' => 'Permintaan Pertemanan',
                'friend_accepted' => 'Pertemanan Diterima',
                'new_message' => 'Pesan Baru',
                'comment' => 'Komentar Baru',
                'like' => 'Suka',
                default => 'Notifikasi',
            } }}
        </p>
        <p class="text-xs text-gray-500 truncate">{!! $notification->message !!}</p>
    </div>
    
    <!-- Time + Delete -->
    <div class="flex items-center gap-2 flex-shrink-0">
        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($notification->created)->diffForHumans() }}</span>
        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </form>
    </div>
</div>
