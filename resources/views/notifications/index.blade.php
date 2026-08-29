@extends('layouts.app')

@section('content')
<div class="pt-10 pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto flex flex-col lg:flex-row gap-6 lg:gap-12">

        <!-- KONTEN KIRI: Notifications -->
        <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm">
            
            <!-- Header -->
            <div class="px-8 pt-8 pb-4">
                <div class="flex justify-between items-center border-b border-gray-300/60 pb-4">
                    <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                    @if($notifications->where('hasread', 0)->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-[#b71c1c] hover:text-red-800 transition">
                                MARK ALL READ
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Banner -->
            <div class="mx-8 mb-4 bg-white border-l-4 border-[#3b82f6] rounded-r-lg p-4 flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-[#3b82f6] flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-600">Go to 'Admin CP' - 'Themes' - 'Manage Blocks' to edit this message.</p>
            </div>

            <!-- Section Header -->
            <div class="px-8 mb-2">
                <h2 class="text-sm font-bold text-gray-900">Recent Activity</h2>
            </div>

            <!-- Notifications List -->
            <div class="px-8 pb-8 flex-grow">
                @if($notifications->count() > 0)
                    <div class="bg-white rounded-[14px] shadow-sm overflow-hidden">
                        @foreach($notifications as $notif)
                            @php
                                $isUnread = !$notif->hasread;
                                $type = $notif->subject;
                                
                                $iconBg = match($type) {
                                    'friend_request', 'friend_accepted' => 'bg-[#f4dada]',
                                    'new_message' => 'bg-[#e8f5e9]',
                                    'comment', 'like' => 'bg-[#fff3e0]',
                                    default => 'bg-gray-100',
                                };
                                
                                $iconColor = match($type) {
                                    'friend_request', 'friend_accepted' => 'text-[#b71c1c]',
                                    'new_message' => 'text-green-600',
                                    'comment' => 'text-orange-500',
                                    'like' => 'text-red-500',
                                    default => 'text-gray-500',
                                };
                                
                                $title = match($type) {
                                    'friend_request' => 'Permintaan Pertemanan',
                                    'friend_accepted' => 'Pertemanan Diterima',
                                    'new_message' => 'Pesan Baru',
                                    'comment' => 'Komentar Baru',
                                    'like' => 'Suka',
                                    default => 'Notifikasi',
                                };
                            @endphp
                            
                            <div class="flex items-center gap-4 px-6 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} {{ $isUnread ? 'bg-[#fafafa]' : '' }}">
                                <!-- Unread Indicator -->
                                <div class="w-2 h-2 rounded-full {{ $isUnread ? 'bg-[#b71c1c]' : 'bg-gray-300' }} flex-shrink-0"></div>
                                
                                <!-- Icon -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $iconBg }}">
                                    @if($type === 'friend_request' || $type === 'friend_accepted')
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    @elseif($type === 'new_message')
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @elseif($type === 'comment')
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    @elseif($type === 'like')
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-bold text-gray-900">{{ $title }}</p>
                                    <p class="text-xs text-gray-500 truncate">{!! $notif->message !!}</p>
                                </div>
                                
                                <!-- Time + Delete -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($notif->created)->diffForHumans() }}</span>
                                    <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
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
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-[14px] shadow-sm p-20 text-center">
                        <p class="text-gray-400 text-sm">Tidak ada notifikasi.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KONTEN KANAN: Sidebar -->
        <div class="w-full lg:w-[15%] space-y-6">
            <div class="bg-white p-8 rounded-[24px] shadow-sm flex flex-col items-center">
                <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                <div class="flex text-[#ffc107] mb-2 gap-1">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                <p class="text-xs text-blue-600 font-medium hover:underline cursor-pointer">532 Reviews</p>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm">
                <h3 class="text-[15px] font-bold text-gray-900 mb-4">Network Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">LinkedIn</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">phpBB Group</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">Facebook</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
