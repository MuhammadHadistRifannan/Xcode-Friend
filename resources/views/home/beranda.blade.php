@extends('layouts.app')
@section('title', 'Beranda · XCODE Friends')

@php
    $me = auth()->user();
    $myPostCount = \Illuminate\Support\Facades\DB::table('jcow_streams')->where('uid', $me->id)->count();
    $card = 'bg-white rounded-[20px] shadow-[0_1px_2px_rgba(20,20,22,0.04)]';
@endphp

@section('content')
<div class="max-w-[1400px] mx-auto w-full px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_minmax(0,1fr)] xl:grid-cols-[272px_minmax(0,1fr)_304px] gap-5 items-start">

        {{-- ============ KIRI ============ --}}
        <aside class="space-y-4 lg:sticky lg:top-24 animate-fade-in-up">

            <a href="{{ route('undang.index') }}" class="xf-promo block group">
                <div class="relative flex items-start justify-between gap-3">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.08em] text-white/70">Undang teman</span>
                    <span class="grid place-items-center size-7 rounded-full bg-white/10 transition group-hover:bg-white/20 group-hover:rotate-45">
                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="relative mt-3 text-[15px] font-semibold leading-snug">Ajak rekan belajar dan berbagi karya di XCODE Friends.</p>
            </a>

            @if(\App\Helpers\ModuleHelper::isActive('PAGES') || \App\Helpers\ModuleHelper::isActive('GROUPS'))
            <section class="{{ $card }} p-4">
                @if(\App\Helpers\ModuleHelper::isActive('PAGES'))
                <div class="flex items-center justify-between px-1 mb-2">
                    <h3 class="xf-label">Pages populer</h3>
                    <a href="{{ route('pages.index') }}" class="text-xs font-medium text-zinc-500 hover:text-[#990000] transition">Semua</a>
                </div>
                <div class="space-y-0.5">
                    @forelse($popularPages as $page)
                        <a href="{{ route('pages.show', $page->id) }}" class="flex items-center gap-3 rounded-xl px-1.5 py-1.5 hover:bg-zinc-50 transition group">
                            @if($page->logo_url)
                                <img src="{{ $page->logo_url }}" alt="" class="size-8 rounded-lg object-cover bg-zinc-100">
                            @else
                                <span class="grid place-items-center size-8 rounded-lg bg-red-50 text-[#990000] text-xs font-semibold">{{ strtoupper(substr($page->name, 0, 1)) }}</span>
                            @endif
                            <span class="min-w-0 flex-1 truncate text-[13px] font-medium text-zinc-800 group-hover:text-zinc-950">{{ $page->name }}</span>
                            <span class="shrink-0 text-[11px] tabular-nums text-zinc-400">{{ number_format($page->followers_count ?? $page->users ?? 0) }}</span>
                        </a>
                    @empty
                        <p class="px-1.5 py-2 text-xs text-zinc-400">Belum ada page populer.</p>
                    @endforelse
                </div>
                @endif

                @if(\App\Helpers\ModuleHelper::isActive('GROUPS'))
                <div class="flex items-center justify-between px-1 mb-2 {{ \App\Helpers\ModuleHelper::isActive('PAGES') ? 'mt-4 pt-4 border-t border-zinc-100' : '' }}">
                    <h3 class="xf-label">Grup populer</h3>
                    <a href="{{ route('groups.browse') }}" class="text-xs font-medium text-zinc-500 hover:text-[#990000] transition">Semua</a>
                </div>
                <div class="space-y-0.5">
                    @forelse($popularGroups as $group)
                        <a href="{{ route('groups.show', $group->id) }}" class="flex items-center gap-3 rounded-xl px-1.5 py-1.5 hover:bg-zinc-50 transition group">
                            @if($group->logo_url)
                                <img src="{{ $group->logo_url }}" alt="" class="size-8 rounded-lg object-cover bg-zinc-100">
                            @else
                                <span class="grid place-items-center size-8 rounded-lg bg-zinc-100 text-zinc-600 text-xs font-semibold">{{ strtoupper(substr($group->name, 0, 1)) }}</span>
                            @endif
                            <span class="min-w-0 flex-1 truncate text-[13px] font-medium text-zinc-800 group-hover:text-zinc-950">{{ $group->name }}</span>
                            <span class="shrink-0 rounded-full bg-zinc-900 px-2 py-0.5 text-[10px] font-semibold tabular-nums text-white">{{ number_format($group->members_count ?? $group->users ?? 0) }}</span>
                        </a>
                    @empty
                        <p class="px-1.5 py-2 text-xs text-zinc-400">Belum ada grup populer.</p>
                    @endforelse
                </div>
                @endif
            </section>
            @endif

            <nav class="{{ $card }} p-2" aria-label="Menu akun">
                @php
                    $menu = [
                        [route('profile.show', $me->username), 'user-round', 'Profilku', null],
                        [route('profile.show', ['username' => $me->username, 'tab' => 'pengikut']), 'users', 'Pengikutku', $followerCount],
                        [route('profile.show', ['username' => $me->username, 'tab' => 'mengikuti']), 'user-plus', 'Yang aku ikuti', $followingCount],
                        [route('profile.edit'), 'settings-2', 'Opsi', null],
                    ];
                @endphp
                @foreach($menu as [$href, $icon, $label, $count])
                    <a href="{{ $href }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] font-medium text-zinc-700 hover:bg-zinc-50 hover:text-zinc-950 transition">
                        <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px] text-zinc-400"></i>
                        <span class="flex-1">{{ $label }}</span>
                        @if(!is_null($count))
                            <span class="text-xs tabular-nums text-zinc-400">{{ $count }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            @php $leftColumnHtml = \App\Helpers\SettingHelper::get('theme_block_left_column', ''); @endphp
            @if($leftColumnHtml)
                <div>{!! $leftColumnHtml !!}</div>
            @endif
        </aside>

        {{-- ============ TENGAH ============ --}}
        <section class="min-w-0 space-y-4 animate-fade-in-up delay-100">
            <x-feed-upload action="{{ route('stream.store') }}" app="feed" aid="0" wallId="0" />

            <div class="flex items-center justify-between px-1 pt-2">
                <h2 class="xf-label">Feed terbaru</h2>
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-zinc-500">
                    <span class="relative flex size-1.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#990000] opacity-60"></span><span class="relative inline-flex size-1.5 rounded-full bg-[#990000]"></span></span>
                    Realtime
                </span>
            </div>

            <button type="button" id="new-posts-banner" class="hidden w-full xf-pill justify-center bg-[#141416] text-white shadow-lg">
                <i data-lucide="arrow-up" class="w-4 h-4"></i>
                Ada postingan baru. Klik untuk melihat
            </button>

            <div id="feed-stream-container" class="space-y-4">
                @forelse ($streams as $stream)
                    @php $delays = ['', 'delay-75', 'delay-150', 'delay-200', 'delay-300', 'delay-400']; $d = $delays[min($loop->index, 5)]; @endphp
                    <div class="animate-fade-in-up {{ $d }}"><x-single-stream :stream="$stream" /></div>
                    @if($loop->index == 0 && $streams->currentPage() == 1)
                        @php $centerColumnHtml = \App\Helpers\SettingHelper::get('theme_block_center_column', ''); @endphp
                        @if($centerColumnHtml)
                            <div>{!! $centerColumnHtml !!}</div>
                        @endif
                    @endif
                @empty
                    <div id="feed-empty-state" class="{{ $card }} px-6 py-14 text-center">
                        <span class="mx-auto grid place-items-center size-12 rounded-2xl bg-zinc-100 text-zinc-500"><i data-lucide="image" class="w-5 h-5"></i></span>
                        <p class="mt-4 text-sm font-semibold text-zinc-800">Feed masih kosong</p>
                        <p class="mt-1 text-sm text-zinc-500">Bagikan status atau foto pertama Anda di atas.</p>
                    </div>
                @endforelse
            </div>

            <div id="feed-infinite-sentinel" class="py-2 text-center" data-next-page="{{ $streams->nextPageUrl() }}" data-has-more="{{ $streams->hasMorePages() ? '1' : '0' }}">
                <div id="feed-loading-spinner" class="hidden text-left">
                    <div class="{{ $card }} p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="skeleton-shimmer size-10 rounded-full flex-shrink-0"></div>
                            <div class="space-y-2 flex-1">
                                <div class="skeleton-shimmer h-3.5 w-1/4 rounded"></div>
                                <div class="skeleton-shimmer h-2.5 w-1/6 rounded"></div>
                            </div>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="skeleton-shimmer h-3 w-3/4 rounded"></div>
                            <div class="skeleton-shimmer h-3 w-1/2 rounded"></div>
                        </div>
                        <div class="skeleton-shimmer h-56 w-full rounded-2xl"></div>
                    </div>
                </div>
                <div id="feed-no-more" class="{{ $streams->hasMorePages() ? 'hidden' : '' }} text-xs text-zinc-400 py-3">
                    Anda sudah melihat semua postingan.
                </div>
            </div>
        </section>

        {{-- ============ KANAN ============ --}}
        <aside class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1 content-start lg:col-span-2 xl:col-span-1 xl:sticky xl:top-24 animate-fade-in-up delay-200 [&>*]:min-w-0">

            {{-- Kartu profil --}}
            <section class="{{ $card }} overflow-hidden">
                <div class="relative h-24">
                    <picture>
                        <source srcset="{{ asset('assets/img/login-image.webp') }}" type="image/webp">
                        <img src="{{ asset('assets/img/login-image.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-b from-[#990000]/30 to-[#141416]/50"></div>
                </div>
                <div class="px-5 pb-5 text-center">
                    <img src="{{ $me->avatar_url }}" alt="" class="relative -mt-10 mx-auto size-20 rounded-full object-cover ring-4 ring-white bg-zinc-200">
                    <p class="mt-2 text-[15px] font-semibold text-zinc-900 truncate">{{ $me->fullname ?: $me->username }}</p>
                    <p class="text-xs text-zinc-500">{{ '@'.$me->username }}</p>

                    <dl class="mt-4 grid grid-cols-3 divide-x divide-zinc-100">
                        <div><dt class="sr-only">Postingan</dt><dd class="text-base font-semibold tabular-nums text-zinc-900">{{ number_format($myPostCount) }}</dd><p class="text-[11px] text-zinc-500">postingan</p></div>
                        <div><dt class="sr-only">Pengikut</dt><dd class="text-base font-semibold tabular-nums text-zinc-900">{{ number_format($followerCount) }}</dd><p class="text-[11px] text-zinc-500">pengikut</p></div>
                        <div><dt class="sr-only">Mengikuti</dt><dd class="text-base font-semibold tabular-nums text-zinc-900">{{ number_format($followingCount) }}</dd><p class="text-[11px] text-zinc-500">mengikuti</p></div>
                    </dl>

                    @if(\App\Helpers\ModuleHelper::isActive('PHOTOS'))
                    <a href="{{ route('foto.index') }}" class="xf-pill mt-5 w-full justify-center bg-[#141416] text-white hover:bg-black">
                        <i data-lucide="camera" class="w-4 h-4"></i> Unggah foto
                    </a>
                    @else
                    <a href="{{ route('profile.show', $me->username) }}" class="xf-pill mt-5 w-full justify-center bg-[#141416] text-white hover:bg-black">
                        <i data-lucide="user-round" class="w-4 h-4"></i> Lihat profil
                    </a>
                    @endif
                </div>
            </section>

            {{-- Member online --}}
            <section class="{{ $card }} p-4">
                <div class="flex items-center justify-between px-1 mb-3">
                    <h3 class="xf-label">Member online</h3>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                        <span class="relative flex size-1.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex size-1.5 rounded-full bg-emerald-500"></span></span>
                        Live
                    </span>
                </div>
                <div id="online-counter-text" class="px-1 mb-3 text-[13px] text-zinc-600">
                    @if($onlineCount <= 0)
                        <span>Tidak ada member yang aktif</span>
                    @else
                        <span><strong id="online-counter-number" class="font-semibold text-zinc-900">{{ $onlineCount }} member</strong> sedang aktif</span>
                    @endif
                </div>

                <div id="online-members-container" class="space-y-0.5 max-h-[340px] overflow-y-auto -mr-1.5 pr-1.5 custom-scrollbar">
                    @forelse($onlineMembers as $member)
                        <a href="{{ route('profile.show', $member->username) }}" class="flex items-center gap-3 rounded-xl px-1.5 py-1.5 hover:bg-zinc-50 transition group" data-member-id="{{ $member->id }}">
                            <span class="relative shrink-0">
                                <img src="{{ $member->avatar_url }}" alt="" class="size-9 rounded-full object-cover bg-zinc-200">
                                <span class="member-status-dot absolute bottom-0 right-0 size-2.5 rounded-full ring-2 ring-white bg-emerald-500"></span>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[13px] font-medium text-zinc-800 group-hover:text-zinc-950">{{ $member->fullname }}</span>
                                <span class="block truncate text-[11px] text-zinc-400">{{ '@' . $member->username }}</span>
                            </span>
                        </a>
                    @empty
                        <div id="online-empty-state" class="rounded-xl border border-dashed border-zinc-200 px-3 py-5 text-center">
                            <p class="text-xs text-zinc-500">Tidak ada member lain yang sedang online.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <x-sidebar-right />
            @php $rightColumnHtml = \App\Helpers\SettingHelper::get('theme_block_right_column', ''); @endphp
            @if($rightColumnHtml)
                <div>{!! $rightColumnHtml !!}</div>
            @endif
        </aside>
    </div>
</div>

@include('components.lightbox')

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 pt-6 flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold text-zinc-900">Laporkan konten</h3>
                <p class="mt-1 text-sm text-zinc-500">Laporan bersifat anonim dan ditinjau administrator.</p>
            </div>
            <button type="button" onclick="closeReportModal()" class="grid place-items-center size-9 rounded-full text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 transition" aria-label="Tutup">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <input type="hidden" name="url" id="reportUrl" value="">
            <div class="px-6 pt-5">
                <label for="report-message" class="block text-sm font-medium text-zinc-700 mb-2">Alasan pelaporan</label>
                <textarea id="report-message" name="message" rows="4" class="w-full text-sm p-3 bg-zinc-50 border border-zinc-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#990000]/15 focus:border-[#990000] resize-none" placeholder="Jelaskan mengapa konten ini tidak pantas" required></textarea>
            </div>
            <div class="px-6 py-5 flex justify-end gap-2">
                <button type="button" onclick="closeReportModal()" class="xf-pill bg-zinc-100 text-zinc-700 hover:bg-zinc-200">Batal</button>
                <button type="submit" class="xf-pill bg-[#990000] text-white hover:bg-[#7a0000]">Kirim laporan</button>
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
    function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : String(s); return d.innerHTML; }

    function syncOnlineWidget() {
        if (!window.onlineUsers || !window.onlineUsersReady) return;
        const onlineList = Object.values(window.onlineUsers);
        const onlineIds = onlineList.map(u => parseInt(u.id));
        const count = onlineList.length;

        const counterText = document.getElementById('online-counter-text');
        if (counterText) {
            counterText.innerHTML = count <= 0
                ? '<span>Tidak ada member yang aktif</span>'
                : `<span><strong id="online-counter-number" class="font-semibold text-zinc-900">${count} member</strong> sedang aktif</span>`;
        }

        const container = document.getElementById('online-members-container');
        if (!container) return;

        container.querySelectorAll('[data-member-id]').forEach(el => {
            if (!onlineIds.includes(parseInt(el.getAttribute('data-member-id')))) el.remove();
        });

        onlineList.forEach(u => {
            if (!u || !u.id || !u.username) return;
            if (container.querySelector(`[data-member-id="${u.id}"]`)) return;
            const emptyState = document.getElementById('online-empty-state');
            if (emptyState) emptyState.remove();

            const a = document.createElement('a');
            a.href = (window.AppBase || '') + '/@' + encodeURIComponent(u.username);
            a.className = 'flex items-center gap-3 rounded-xl px-1.5 py-1.5 hover:bg-zinc-50 transition group animate-fade-in-up';
            a.setAttribute('data-member-id', u.id);
            a.innerHTML = `
                <span class="relative shrink-0">
                    <img src="${esc(u.avatar_url || '/assets/img/default.png')}" alt="" class="size-9 rounded-full object-cover bg-zinc-200">
                    <span class="member-status-dot absolute bottom-0 right-0 size-2.5 rounded-full ring-2 ring-white bg-emerald-500"></span>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-[13px] font-medium text-zinc-800 group-hover:text-zinc-950">${esc(u.fullname || u.username)}</span>
                    <span class="block truncate text-[11px] text-zinc-400">@${esc(u.username)}</span>
                </span>`;
            container.prepend(a);
        });

        if (!container.querySelector('[data-member-id]') && !document.getElementById('online-empty-state')) {
            const empty = document.createElement('div');
            empty.id = 'online-empty-state';
            empty.className = 'rounded-xl border border-dashed border-zinc-200 px-3 py-5 text-center';
            empty.innerHTML = '<p class="text-xs text-zinc-500">Tidak ada member lain yang sedang online.</p>';
            container.appendChild(empty);
        }
    }

    window.addEventListener('online-update', syncOnlineWidget);
    setTimeout(syncOnlineWidget, 1000);
});
</script>

@include('components.feed-scripts')
@endsection
