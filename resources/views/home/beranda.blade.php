@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-[95%] lg:max-w-5xl mx-auto w-full">
 <!-- Header Page -->
 <h2 class="text-xs font-bold text-neutral-800 uppercase tracking-widest mb-6">DASHBOARD</h2>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- KOLOM KIRI: Menu Navigasi Samping -->
        <div class="lg:col-span-3 space-y-6 animate-fade-in-up">

            <!-- Pages Populer -->
            @if(\App\Helpers\ModuleHelper::isActive('PAGES'))
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex items-center justify-between border-l-4 border-red-700 pl-2 mb-4">
                    <h3 class="text-xs font-bold text-neutral-800 uppercase tracking-wider">PAGES POPULER</h3>
                    <a href="{{ route('pages.index') }}" class="text-[11px] text-red-600 hover:text-red-700 hover:underline font-bold">Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($popularPages as $page)
                        <a href="{{ route('pages.show', $page->id) }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-50 transition group">
                            <div class="shrink-0">
                                @if($page->logo_url)
                                    <img src="{{ $page->logo_url }}" alt="{{ $page->name }}" class="w-9 h-9 rounded-lg object-cover border border-neutral-200">
                                @else
                                    <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center border border-red-100 font-bold text-xs">
                                        {{ strtoupper(substr($page->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-neutral-800 truncate group-hover:text-red-700 transition">
                                    {{ $page->name }}
                                </h4>
                                <p class="text-[11px] text-neutral-400">
                                    {{ number_format($page->followers_count ?? $page->users ?? 0) }} Pengikut
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-neutral-400 py-2 text-center">Belum ada page populer.</p>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- Grup Populer -->
            @if(\App\Helpers\ModuleHelper::isActive('GROUPS'))
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex items-center justify-between border-l-4 border-red-700 pl-2 mb-4">
                    <h3 class="text-xs font-bold text-neutral-800 uppercase tracking-wider">GRUP POPULER</h3>
                    <a href="{{ route('groups.browse') }}" class="text-[11px] text-red-600 hover:text-red-700 hover:underline font-bold">Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($popularGroups as $group)
                        <a href="{{ route('groups.show', $group->id) }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-50 transition group">
                            <div class="shrink-0">
                                @if($group->logo_url)
                                    <img src="{{ $group->logo_url }}" alt="{{ $group->name }}" class="w-9 h-9 rounded-lg object-cover border border-neutral-200">
                                @else
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 font-bold text-xs">
                                        {{ strtoupper(substr($group->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-neutral-800 truncate group-hover:text-red-700 transition">
                                    {{ $group->name }}
                                </h4>
                                <p class="text-[11px] text-neutral-400">
                                    {{ number_format($group->members_count ?? $group->users ?? 0) }} Anggota
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-neutral-400 py-2 text-center">Belum ada grup populer.</p>
                    @endforelse
                </div>
            </div>
            @endif

 <!-- Profile Info Menu -->
 <div class="bg-white rounded-xl shadow-sm border border-neutral-200 py-3">
 <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Profilku</span>
 </a>
 <a href="{{ route('profile.show', ['username' => auth()->user()->username, 'tab' => 'pengikut']) }}" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <div class="flex items-center">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Pengikutku</span>
 </div>
 <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followerCount }}</span>
 </a>
 <a href="{{ route('profile.show', ['username' => auth()->user()->username, 'tab' => 'mengikuti']) }}" class="flex items-center justify-between px-5 py-3 hover:bg-neutral-50 transition border-b border-neutral-100 group">
 <div class="flex items-center">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Yang aku ikuti</span>
 </div>
 <span class="text-xs bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full">{{ $followingCount }}</span>
 </a>
 <a href="{{ route('profile.edit') }}" class="flex items-center px-5 py-3 hover:bg-neutral-50 transition group">
 <svg class="w-5 h-5 text-neutral-400 mr-3 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
 <span class="text-sm font-semibold text-neutral-700">Opsi</span>
 </a>
 </div>
 
 @php $leftColumnHtml = \App\Helpers\SettingHelper::get('theme_block_left_column', ''); @endphp
 @if($leftColumnHtml)
     <div class="mb-4">{!! $leftColumnHtml !!}</div>
 @endif
 </div>

    <!-- KOLOM TENGAH: BAGI CEPAT & FEED BERITA -->
    <div class="lg:col-span-6 space-y-6 animate-fade-in-up delay-100">

        <!-- Buat Post (Bagi Cepat) -->
 <x-feed-upload action="{{ route('stream.store') }}" app="feed" aid="0" wallId="0" />

 <!-- Feed Berita -->
 <h3 class="text-xs font-bold text-neutral-800 uppercase border-l-4 border-red-700 pl-2 mt-8 mb-4">FEED BERITA</h3>

        <!-- Realtime New Post Notification Banner (hidden by default) -->
        <div id="new-posts-banner" class="hidden mb-4 p-3 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow cursor-pointer transition text-center text-xs font-bold flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
            <span>Ada postingan baru! Klik untuk melihat ke atas</span>
        </div>

        <!-- Scrollable Wrapper Khusus Feed Berita -->
        <div class="h-[90vh] min-h-[800px] overflow-y-auto overflow-x-hidden custom-scrollbar pr-2 pb-6">
            <!-- Container Feed Berita -->
            <div id="feed-stream-container" class="space-y-4">
                @forelse ($streams as $stream)
                    @php $delays = ['', 'delay-75', 'delay-150', 'delay-200', 'delay-300', 'delay-400']; $d = $delays[min($loop->index, 5)]; @endphp
                    <div class="animate-fade-in-up {{ $d }}"><x-single-stream :stream="$stream" /></div>
                    @if($loop->index == 0 && $streams->currentPage() == 1)
                        @php $centerColumnHtml = \App\Helpers\SettingHelper::get('theme_block_center_column', ''); @endphp
                        @if($centerColumnHtml)
                            <div class="mb-4">{!! $centerColumnHtml !!}</div>
                        @endif
                    @endif
                @empty
                    <div id="feed-empty-state" class="text-center text-sm text-neutral-500 py-10 bg-white rounded-xl shadow-sm border border-neutral-200">Tidak ada feed berita terbaru.</div>
                @endforelse
            </div>

            <!-- Infinite Scroll Sentinel & Skeleton Shimmer Loading -->
            <div id="feed-infinite-sentinel" class="py-4 text-center" data-next-page="{{ $streams->nextPageUrl() }}" data-has-more="{{ $streams->hasMorePages() ? '1' : '0' }}">
                <div id="feed-loading-spinner" class="hidden my-2 space-y-4 text-left">
                    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="skeleton-shimmer w-10 h-10 rounded-full flex-shrink-0"></div>
                            <div class="space-y-2 flex-1">
                                <div class="skeleton-shimmer h-3.5 w-1/4 rounded"></div>
                                <div class="skeleton-shimmer h-2.5 w-1/6 rounded"></div>
                            </div>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="skeleton-shimmer h-3 w-3/4 rounded"></div>
                            <div class="skeleton-shimmer h-3 w-1/2 rounded"></div>
                        </div>
                        <div class="skeleton-shimmer h-40 w-full rounded-xl"></div>
                    </div>
                </div>
                <div id="feed-no-more" class="{{ $streams->hasMorePages() ? 'hidden' : '' }} text-xs text-neutral-400 py-3">
                    Semua postingan telah ditampilkan.
                </div>
            </div>
        </div>



 </div>

    <!-- KOLOM KANAN: Review & Links -->
    <div class="lg:col-span-3 space-y-6 animate-fade-in-up delay-200">
        <!-- Widget Pantauan Member Online -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
            <!-- Header Widget -->
            <div class="flex items-center justify-between border-l-4 border-red-700 pl-2 mb-3">
                <h3 class="text-xs font-bold text-neutral-800 uppercase tracking-wider">MEMBER ONLINE</h3>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </span>
                    LIVE
                </span>
            </div>

            <!-- Box Info Teks Berapa Member yang Online -->
            <div class="bg-gradient-to-r from-emerald-50/80 to-teal-50/60 border border-emerald-100 rounded-xl p-3 mb-4 flex items-center justify-between">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-white shadow-2xs border border-emerald-200/60 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div id="online-counter-text" class="text-xs font-semibold text-neutral-800 leading-tight">
                            @if($onlineCount <= 0)
                                <span>Tidak ada member yang aktif</span>
                            @elseif($onlineCount == 1)
                                <span><strong id="online-counter-number" class="text-emerald-700 font-bold">1 member</strong> sedang aktif</span>
                            @else
                                <span><strong id="online-counter-number" class="text-emerald-700 font-bold">{{ $onlineCount }} member</strong> sedang aktif</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Terpantau secara realtime</p>
                    </div>
                </div>
            </div>

            <div id="online-members-container" class="space-y-2 max-h-[615px] overflow-y-auto pr-1.5 custom-scrollbar scroll-smooth">
                @forelse($onlineMembers as $member)
                    <a href="{{ route('profile.show', $member->username) }}" 
                       class="flex items-center justify-between p-2 rounded-lg hover:bg-neutral-50 transition border border-transparent hover:border-neutral-100 group"
                       data-member-id="{{ $member->id }}">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <div class="relative shrink-0">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->fullname }}" class="w-9 h-9 rounded-full object-cover border border-neutral-200">
                                <span class="member-status-dot absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white bg-emerald-500"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-neutral-800 truncate group-hover:text-red-700 transition">
                                    {{ $member->fullname }}
                                </p>
                                <p class="text-[11px] text-neutral-400 truncate">
                                    {{ '@' . $member->username }}
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right ml-2">
                            <span class="member-status-badge text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/80">
                                Online
                            </span>
                        </div>
                    </a>
                @empty
                    <div id="online-empty-state" class="text-center py-5 px-3 bg-neutral-50 rounded-lg border border-dashed border-neutral-200">
                        <svg class="w-8 h-8 mx-auto text-neutral-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-xs font-medium text-neutral-500">Tidak ada member lain yang sedang online.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <x-sidebar-right />
        @php $rightColumnHtml = \App\Helpers\SettingHelper::get('theme_block_right_column', ''); @endphp
        @if($rightColumnHtml)
            <div class="mb-4">{!! $rightColumnHtml !!}</div>
        @endif
    </div>

 </div>
</div>

@include('components.lightbox')

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
 <input type="hidden" name="url" id="reportUrl" value="">
 <div class="p-6">
 <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pelaporan</label>
 <textarea name="message" rows="4" class="w-full text-sm p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 resize-none mb-3" placeholder="Jelaskan mengapa konten ini tidak pantas..." required></textarea>
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    function syncOnlineWidget() {
        if (!window.onlineUsers || !window.onlineUsersReady) return;
        const onlineList = Object.values(window.onlineUsers);
        const onlineIds = onlineList.map(u => parseInt(u.id));
        const count = onlineList.length;

        // Perbarui teks info berapa member yang online secara elegan
        const counterText = document.getElementById('online-counter-text');
        if (counterText) {
            if (count <= 0) {
                counterText.innerHTML = '<span>Tidak ada member yang aktif</span>';
            } else if (count === 1) {
                counterText.innerHTML = '<span><strong id="online-counter-number" class="text-emerald-700 font-bold">1 member</strong> sedang aktif</span>';
            } else {
                counterText.innerHTML = `<span><strong id="online-counter-number" class="text-emerald-700 font-bold">${count} member</strong> sedang aktif</span>`;
            }
        }

        const container = document.getElementById('online-members-container');
        if (!container) return;

        // Hapus langsung member yang sudah offline (tidak lagi tampil sama sekali)
        container.querySelectorAll('[data-member-id]').forEach(el => {
            const uid = parseInt(el.getAttribute('data-member-id'));
            const isOnline = onlineIds.includes(uid);
            if (!isOnline) {
                el.remove();
            }
        });

        // Sisipkan member baru jika ada yang join di presence channel dan belum ada di container
        onlineList.forEach(u => {
            if (!u || !u.id) return;
            const existing = container.querySelector(`[data-member-id="${u.id}"]`);
            if (!existing && u.username) {
                const emptyState = document.getElementById('online-empty-state');
                if (emptyState) emptyState.remove();

                const div = document.createElement('a');
                div.href = window.AppBase ? `${window.AppBase}/@${u.username}` : `/@${u.username}`;
                div.className = 'flex items-center justify-between p-2 rounded-lg hover:bg-neutral-50 transition border border-transparent hover:border-neutral-100 group animate-fade-in-up';
                div.setAttribute('data-member-id', u.id);
                div.innerHTML = `
                    <div class="flex items-center space-x-2.5 min-w-0">
                        <div class="relative shrink-0">
                            <img src="${u.avatar_url || '/assets/img/default.png'}" alt="${u.fullname || u.username}" class="w-9 h-9 rounded-full object-cover border border-neutral-200">
                            <span class="member-status-dot absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white bg-emerald-500"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-neutral-800 truncate group-hover:text-red-700 transition">
                                ${u.fullname || u.username}
                            </p>
                            <p class="text-[11px] text-neutral-400 truncate">
                                @${u.username}
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0 text-right ml-2">
                        <span class="member-status-badge text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/80">
                            Online
                        </span>
                    </div>
                `;
                container.prepend(div);
            }
        });

        // Jika tidak ada member yang online sama sekali, tampilkan pesan kosong
        const remainingMembers = container.querySelectorAll('[data-member-id]');
        if (remainingMembers.length === 0) {
            if (!document.getElementById('online-empty-state')) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'online-empty-state';
                emptyDiv.className = 'text-center py-5 px-3 bg-neutral-50 rounded-lg border border-dashed border-neutral-200';
                emptyDiv.innerHTML = `
                    <svg class="w-8 h-8 mx-auto text-neutral-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p class="text-xs font-medium text-neutral-500">Tidak ada member lain yang sedang online.</p>
                `;
                container.appendChild(emptyDiv);
            }
        } else {
            const emptyState = document.getElementById('online-empty-state');
            if (emptyState) emptyState.remove();
        }
    }

    window.addEventListener('online-update', syncOnlineWidget);
    setTimeout(syncOnlineWidget, 1000);
});
</script>

@include('components.feed-scripts')
@endsection

