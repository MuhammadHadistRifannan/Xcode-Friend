@extends('layouts.admin')
@section('title', 'Dashboard - Admin Panel XCODE')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 lg:pt-8 pb-10 space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold tracking-tight text-zinc-900 leading-tight">Admin Control Panel</h1>
            <p class="mt-1 text-sm text-zinc-500">Kelola anggota, konten, dan pengaturan platform XCODE.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full px-3.5 py-2 text-xs font-semibold {{ $dbStatus ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
            <span class="size-2 rounded-full {{ $dbStatus ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
            {{ $dbStatus ? 'Database terhubung' : 'Database error' }}
        </span>
    </div>

    {{-- Member KPIs --}}
    @php
        $memberStats = [
            ['label' => 'Total Members', 'val' => $stats['total_members'], 'icon' => 'users', 'tone' => 'bg-[#990000]', 'q' => '', 'note' => 'Seluruh akun terdaftar'],
            ['label' => 'Aktif', 'val' => $stats['active_members'], 'icon' => 'user-check', 'tone' => 'bg-emerald-600', 'q' => '?status=active', 'note' => 'Dapat masuk dan berinteraksi'],
            ['label' => 'Pending', 'val' => $stats['pending_members'], 'icon' => 'clock', 'tone' => 'bg-amber-500', 'q' => '?status=pending', 'note' => 'Menunggu verifikasi'],
            ['label' => 'Suspended', 'val' => $stats['suspended_members'], 'icon' => 'ban', 'tone' => 'bg-zinc-800', 'q' => '?status=suspended', 'note' => 'Akses dibekukan admin'],
        ];
    @endphp
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach($memberStats as $s)
        <a href="{{ route('admin.members') }}{{ $s['q'] }}" class="admin-tile block" data-reveal style="--i: {{ $loop->index }}">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-zinc-900">{{ $s['label'] }}</p>
                <span class="admin-tile-icon {{ $s['tone'] }}"><i data-lucide="{{ $s['icon'] }}" class="w-5 h-5"></i></span>
            </div>
            <p class="mt-5 text-[32px] font-semibold leading-none tabular-nums text-zinc-900">{{ number_format($s['val']) }}</p>
            <p class="mt-2 text-xs text-zinc-500">{{ $s['note'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- Content counts --}}
    @php
        $contentStats = [
            ['label' => 'Postingan', 'val' => $stats['total_streams'], 'icon' => 'rss'],
            ['label' => 'Komentar', 'val' => $stats['total_comments'], 'icon' => 'message-circle'],
            ['label' => 'Foto', 'val' => $stats['total_photos'], 'icon' => 'image'],
            ['label' => 'Video', 'val' => $stats['total_videos'], 'icon' => 'play-circle'],
            ['label' => 'Grup', 'val' => $stats['total_groups'], 'icon' => 'users-2'],
            ['label' => 'Halaman', 'val' => $stats['total_pages'], 'icon' => 'file-text'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
        @foreach($contentStats as $s)
        <div class="admin-tile !p-4 flex items-center gap-3" data-reveal style="--i: {{ $loop->index + 4 }}">
            <span class="grid place-items-center size-9 shrink-0 rounded-xl bg-[#fdecec] text-[#990000]">
                <i data-lucide="{{ $s['icon'] }}" class="w-[18px] h-[18px]"></i>
            </span>
            <div class="min-w-0">
                <p class="text-lg font-semibold leading-tight tabular-nums text-zinc-900">{{ number_format($s['val']) }}</p>
                <p class="text-xs text-zinc-500 truncate">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Weekly trend + platform status --}}
    <div class="grid grid-cols-12 gap-5">

        <div class="col-span-12 lg:col-span-8">
            <div class="admin-tile !p-0 h-full">
                <div class="px-6 pt-5 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-zinc-900">Tren aktivitas mingguan</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Postingan dan member baru per hari, 7 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-zinc-600">
                        <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-sm bg-[#990000]"></span>Postingan</span>
                        <span class="flex items-center gap-1.5 font-medium"><span class="w-2.5 h-2.5 rounded-sm bg-zinc-300"></span>Member baru</span>
                    </div>
                </div>

                <div class="px-6 pb-5 pt-2">
                    @php
                        $maxVal = max(
                            max(array_column($weeklyTrend, 'streams')) ?: 1,
                            max(array_column($weeklyTrend, 'members')) ?: 1
                        );
                    @endphp
                    <div class="flex items-end gap-2" style="height: 160px">
                        @foreach($weeklyTrend as $day)
                        @php
                            $sp = max(round(($day['streams'] / $maxVal) * 100), 4);
                            $mp = max(round(($day['members'] / $maxVal) * 100), 4);
                            $isToday = $day['label'] === date('D');
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5 group">
                            <div class="w-full flex items-end gap-1" style="height: 132px">
                                <div class="flex-1 flex items-end h-full relative">
                                    <div class="w-full rounded-full relative"
                                         style="height: {{ $sp }}%; background: linear-gradient(180deg, #d32f2f, #990000);"
                                         title="{{ $day['streams'] }} postingan">
                                        @if($day['streams'] > 0)
                                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-zinc-900 text-white text-[10px] px-1.5 py-0.5 rounded-md opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10">{{ $day['streams'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 flex items-end h-full relative">
                                    <div class="w-full rounded-full relative"
                                         style="height: {{ $mp }}%; background: repeating-linear-gradient(135deg, #e4e4e7 0 4px, #d4d4d8 4px 7px);"
                                         title="{{ $day['members'] }} member baru">
                                        @if($day['members'] > 0)
                                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-zinc-900 text-white text-[10px] px-1.5 py-0.5 rounded-md opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10">{{ $day['members'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-[11px] font-semibold {{ $isToday ? 'text-[#990000]' : 'text-zinc-500' }}">{{ $day['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
            <div class="admin-tile flex-1">
                <h3 class="text-base font-semibold text-zinc-900 mb-4">Status platform</h3>
                <div class="space-y-3">
                    @php
                        $health = [
                            ['label' => 'Website', 'icon' => 'globe', 'ok' => true, 'text' => 'Online'],
                            ['label' => 'Database', 'icon' => 'database', 'ok' => (bool) $dbStatus, 'text' => $dbStatus ? 'Terhubung' : 'Error'],
                        ];
                    @endphp
                    @foreach($health as $h)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-sm text-zinc-700">
                            <span class="grid place-items-center size-8 rounded-lg {{ $h['ok'] ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}"><i data-lucide="{{ $h['icon'] }}" class="w-4 h-4"></i></span>
                            {{ $h['label'] }}
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $h['ok'] ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }}">{{ $h['text'] }}</span>
                    </div>
                    @endforeach
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-sm text-zinc-700">
                            <span class="grid place-items-center size-8 rounded-lg {{ $stats['pending_reports'] > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}"><i data-lucide="shield" class="w-4 h-4"></i></span>
                            Moderasi
                        </div>
                        @if($stats['pending_reports'] > 0)
                        <a href="{{ route('admin.reports') }}" class="text-xs font-semibold text-red-700 bg-red-50 px-2.5 py-1 rounded-full hover:bg-red-100 transition">{{ $stats['pending_reports'] }} pending</a>
                        @else
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">Bersih</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($stats['pending_reports'] > 0)
            <a href="{{ route('admin.reports') }}" class="admin-promo relative block transition active:scale-[0.99]">
                <div class="flex items-center gap-3">
                    <span class="grid place-items-center size-10 rounded-xl bg-white text-[#990000] shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5"></i></span>
                    <div>
                        <div class="text-sm font-semibold">{{ $stats['pending_reports'] }} laporan baru</div>
                        <div class="text-xs text-white/80">Klik untuk menangani</div>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>

    {{-- Recent members + recent reports --}}
    <div class="grid grid-cols-12 gap-5">

        <div class="col-span-12 lg:col-span-7">
            <div class="admin-tile !p-0">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-zinc-900">Member terbaru</h2>
                    <a href="{{ route('admin.members') }}" class="text-xs font-semibold text-[#990000] hover:underline flex items-center gap-1">Lihat semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>
                <div class="divide-y divide-zinc-100">
                    @forelse($recentMembers as $m)
                    <div class="px-6 py-3 flex items-center gap-3 hover:bg-zinc-50 transition">
                        <div class="relative shrink-0">
                            <img src="{{ $m->avatar_url }}" alt="{{ $m->username }}"
                                 class="size-9 rounded-full object-cover bg-zinc-100"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->username ?? 'U') }}&background=e5e7eb&color=6b7280&size=64'">
                            <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-white {{ $m->disabled == 0 ? 'bg-emerald-500' : ($m->disabled == 2 ? 'bg-red-500' : 'bg-amber-400') }}"
                                  title="{{ $m->disabled == 0 ? 'Aktif' : ($m->disabled == 2 ? 'Suspended' : 'Pending') }}"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-zinc-900 truncate">{{ $m->fullname ?: $m->username }}</div>
                            <div class="text-xs text-zinc-500">{{ '@'.$m->username }} &middot; {{ date('d M Y', $m->created) }}</div>
                        </div>
                        <a href="{{ route('admin.members.show', $m->id) }}" class="shrink-0 text-xs font-semibold text-zinc-600 hover:text-[#990000] transition px-3 py-1.5 rounded-full hover:bg-[#fdecec]">Detail</a>
                    </div>
                    @empty
                    <div class="py-10 text-center text-sm text-zinc-500">Belum ada member.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-5">
            <div class="admin-tile !p-0 h-full">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-zinc-900">Laporan terkini</h2>
                    <a href="{{ route('admin.reports') }}" class="text-xs font-semibold text-[#990000] hover:underline flex items-center gap-1">Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>
                @if(count($recentReports) > 0)
                <div class="divide-y divide-zinc-100">
                    @foreach($recentReports as $r)
                    <div class="px-6 py-3 flex items-start gap-3 hover:bg-zinc-50 transition">
                        <span class="grid place-items-center size-8 rounded-lg bg-[#fdecec] text-[#990000] shrink-0 mt-0.5"><i data-lucide="alert-circle" class="w-4 h-4"></i></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-zinc-800 line-clamp-1 font-medium">{{ $r->message ?: ($r->url ?: 'Laporan #'.$r->id) }}</p>
                            <span class="text-xs text-zinc-500">{{ date('d M Y', $r->created) }}</span>
                        </div>
                        <span class="shrink-0 text-[11px] font-semibold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Baru</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <span class="grid place-items-center size-12 rounded-full bg-emerald-50 text-emerald-600 mb-3"><i data-lucide="shield-check" class="w-6 h-6"></i></span>
                    <p class="text-sm font-semibold text-zinc-700">Tidak ada laporan</p>
                    <p class="text-xs text-zinc-500 mt-1">Semua laporan sudah ditangani.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
