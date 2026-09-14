@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto">

        <!-- Breadcrumb + Header (di luar container) -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-700 transition">HOME</a>
                <span>›</span>
                <span class="text-gray-700 font-medium">NOTIFICATIONS</span>
            </div>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">NOTIFICATIONS</h1>
                @if($notifications->where('hasread', 0)->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" onsubmit="const btn = this.querySelector('button'); btn.disabled = true; btn.innerText = 'MEMPROSES...';">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-[#b71c1c] hover:text-red-800 transition">
                            TANDAI SEMUA SUDAH DIBACA
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-12">

            <!-- KONTEN KIRI: Notifications -->
            <div class="w-full lg:w-[85%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[400px] h-[650px] pt-8 shadow-sm overflow-hidden border border-gray-200">

            <!-- Info Banner -->
            <div id="unread-banner" class="mx-8 mb-4 bg-white border-l-4 border-[#3b82f6] rounded-r-lg p-4 flex items-start gap-3 {{ $notifications->where('hasread', 0)->count() > 0 ? '' : 'hidden' }}">
                <div class="w-6 h-6 rounded-full bg-[#3b82f6] flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-600" id="unread-banner-text">Anda memiliki {{ $notifications->where('hasread', 0)->count() }} notifikasi yang belum dibaca.</p>
            </div>

            <!-- Section Header -->
            <div class="px-8 mb-2">
                <h2 class="text-sm font-bold text-gray-900">Aktivitas Terkini</h2>
            </div>

            <!-- Notifications List -->
            <div class="px-8 pb-8 flex-grow overflow-y-auto" id="notif-scroll-container">
                <div class="bg-white rounded-[14px] shadow-sm overflow-hidden {{ $notifications->count() > 0 ? '' : 'hidden' }}" id="notifications-list">
                    @foreach($notifications as $notification)
                        @include('notifications.partials._item')
                    @endforeach
                </div>
                
                <div class="bg-white rounded-[14px] shadow-sm p-20 text-center {{ $notifications->count() == 0 ? '' : 'hidden' }}" id="empty-notifs-state">
                    <p class="text-gray-400 text-sm">Tidak ada notifikasi.</p>
                </div>

                <!-- Sentinel Infinite Scroll -->
                <div id="notif-infinite-sentinel" class="py-4 text-center text-xs text-gray-400 font-semibold" style="display: {{ $notifications->count() >= 20 ? 'block' : 'none' }};">
                    <span id="notif-loading-spinner" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Memuat notifikasi lainnya...
                    </span>
                </div>
            </div>
        </div>

        <!-- KONTEN KANAN: Sidebar -->
        <div class="w-full lg:w-[15%] space-y-6">
            <div class="bg-white p-8 rounded-[24px] shadow-sm flex flex-col items-center border border-gray-200">
                <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                <div class="flex text-[#ffc107] mb-2 gap-1">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                <p class="text-xs text-blue-600 font-medium">532 Reviews</p>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-200">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentPage = 1;
    let hasMore = {{ $notifications->count() >= 20 ? 'true' : 'false' }};
    let isLoading = false;

    const notifContainer = document.getElementById('notifications-list');
    const emptyState = document.getElementById('empty-notifs-state');
    const sentinel = document.getElementById('notif-infinite-sentinel');
    const unreadBanner = document.getElementById('unread-banner');
    const unreadBannerText = document.getElementById('unread-banner-text');

    // 1. Infinite Scroll
    if (sentinel && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && hasMore && !isLoading) {
                loadMoreNotifications();
            }
        }, { rootMargin: '150px' });
        observer.observe(sentinel);
    }

    async function loadMoreNotifications() {
        if (isLoading || !hasMore) return;
        isLoading = true;
        currentPage++;

        try {
            const res = await fetch(`{{ route('notifications.index') }}?page=${currentPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            
            if (data.html && data.html.trim().length > 0) {
                notifContainer.insertAdjacentHTML('beforeend', data.html);
            }

            hasMore = data.hasMore;
            if (!hasMore && sentinel) {
                sentinel.style.display = 'none';
            }
        } catch (err) {
            console.error('Gagal memuat notifikasi:', err);
            hasMore = false;
            if (sentinel) sentinel.style.display = 'none';
        } finally {
            isLoading = false;
        }
    }

    // 2. Realtime Echo listener for .notification.created
    if (window.Echo) {
        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.notification.created', (e) => {
                if (emptyState) emptyState.classList.add('hidden');
                if (notifContainer) notifContainer.classList.remove('hidden');

                const notif = e.notification || {};
                const id = notif.id || Date.now();
                const subject = notif.subject || 'default';
                const message = notif.message || '';
                
                const subjectTitles = {
                    'friend_request': 'Permintaan Pertemanan',
                    'friend_accepted': 'Pertemanan Diterima',
                    'new_message': 'Pesan Baru',
                    'comment': 'Komentar Baru',
                    'like': 'Suka',
                    'group_invite': 'Undangan Grup'
                };
                const title = subjectTitles[subject] || 'Notifikasi';

                const cardHtml = `
                    <form action="/notifications/${id}/read" method="POST" class="block notif-item-form transition-all duration-500" data-id="${id}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="flex items-center gap-4 px-6 py-3 border-b border-gray-100 bg-red-50/70 hover:bg-gray-50 transition cursor-pointer" onclick="this.closest('form').submit()">
                            <div class="w-2 h-2 rounded-full bg-[#b71c1c] flex-shrink-0 animate-pulse"></div>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-[#f4dada]">
                                <svg class="w-5 h-5 text-[#b71c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <span class="text-sm font-bold text-gray-900">${title}</span>
                                <span class="text-xs text-gray-600 ml-2">${message}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs text-[#b71c1c] font-semibold">Baru saja</span>
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition" title="Tandai sudah dibaca">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                `;

                notifContainer.insertAdjacentHTML('afterbegin', cardHtml);

                if (unreadBanner && unreadBannerText) {
                    const count = e.unreadNotificationCount || 1;
                    unreadBannerText.innerText = `Anda memiliki ${count} notifikasi yang belum dibaca.`;
                    unreadBanner.classList.remove('hidden');
                }
            });
    }
});
</script>
@endpush
@endsection
