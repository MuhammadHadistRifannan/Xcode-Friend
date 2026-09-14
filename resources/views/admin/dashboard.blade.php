@extends('layouts.admin')
@section('title', 'Dashboard - Admin Panel XCODE')

@section('content')

{{-- ======================================================
     DASHBOARD HEADER HERO
====================================================== --}}
<div class="relative overflow-hidden bg-white border-b border-gray-200">
    {{-- Clean White Header (No Gradients) --}}

    <div class="relative max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">HOME › ADMIN CP</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Admin Control Panel</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola komunitas XCODE — anggota, konten, dan pengaturan platform.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            {{-- DB Status Badge --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $dbStatus ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">
                <span class="w-2 h-2 rounded-full {{ $dbStatus ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                <span class="text-xs font-semibold {{ $dbStatus ? 'text-emerald-700' : 'text-red-700' }}">{{ $dbStatus ? 'DB Connected' : 'DB Error' }}</span>
            </div>
            {{-- Logged In As --}}
            <div class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 shadow-sm">
                <img src="{{ auth()->user()->avatar_url }}" class="w-7 h-7 rounded-full border border-gray-300 object-cover shrink-0" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=e5e7eb&color=6b7280&size=64'">
                <div>
                    <div class="text-xs font-bold text-gray-900 leading-tight">{{ Str::limit(auth()->user()->fullname ?: auth()->user()->username, 18) }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Administrator</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-7 space-y-7">

    {{-- ======================================================
         ROW 1: KEY STATS — 4 member + 6 content in one clean row
    ====================================================== --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Ringkasan Platform</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- Member Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-4 gap-3 mb-3">
            @php
                $memberStats = [
                    ['label' => 'Total Members', 'val' => $stats['total_members'], 'icon' => 'users', 'bg' => 'bg-slate-50', 'iconColor' => 'text-slate-500', 'valColor' => 'text-slate-900', 'trend' => null],
                    ['label' => 'Aktif', 'val' => $stats['active_members'], 'icon' => 'user-check', 'bg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-600', 'valColor' => 'text-emerald-700', 'trend' => null],
                    ['label' => 'Pending', 'val' => $stats['pending_members'], 'icon' => 'clock', 'bg' => 'bg-amber-50', 'iconColor' => 'text-amber-500', 'valColor' => 'text-amber-700', 'trend' => null],
                    ['label' => 'Suspended', 'val' => $stats['suspended_members'], 'icon' => 'ban', 'bg' => 'bg-red-50', 'iconColor' => 'text-red-500', 'valColor' => 'text-red-700', 'trend' => null],
                ];
            @endphp
            @foreach($memberStats as $s)
            <a href="{{ route('admin.members') }}{{ $loop->index === 1 ? '?status=active' : ($loop->index === 2 ? '?status=pending' : ($loop->index === 3 ? '?status=suspended' : '')) }}"
               class="group bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-200">
                <div class="{{ $s['bg'] }} rounded-lg p-2.5 shrink-0 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 {{ $s['iconColor'] }}"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] text-gray-500 font-medium truncate">{{ $s['label'] }}</div>
                    <div class="text-xl font-black {{ $s['valColor'] }}">{{ number_format($s['val']) }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Content Stats Row --}}
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
            @php
                $contentStats = [
                    ['label' => 'Postingan', 'val' => $stats['total_streams'], 'icon' => 'rss', 'accent' => '#3b82f6'],
                    ['label' => 'Komentar', 'val' => $stats['total_comments'], 'icon' => 'message-circle', 'accent' => '#8b5cf6'],
                    ['label' => 'Foto', 'val' => $stats['total_photos'], 'icon' => 'image', 'accent' => '#ec4899'],
                    ['label' => 'Video', 'val' => $stats['total_videos'], 'icon' => 'play-circle', 'accent' => '#f97316'],
                    ['label' => 'Grup', 'val' => $stats['total_groups'], 'icon' => 'users-2', 'accent' => '#14b8a6'],
                    ['label' => 'Halaman', 'val' => $stats['total_pages'], 'icon' => 'file-text', 'accent' => '#6366f1'],
                ];
            @endphp
            @foreach($contentStats as $s)
            <div class="bg-white rounded-xl border border-gray-200 p-3.5 text-center shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
                <div class="flex justify-center mb-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: {{ $s['accent'] }}18">
                        <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4" style="color: {{ $s['accent'] }}"></i>
                    </div>
                </div>
                <div class="text-lg font-black text-gray-900 group-hover:scale-110 transition-transform">{{ number_format($s['val']) }}</div>
                <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ======================================================
         ROW 2: WEEKLY TREND CHART + PLATFORM STATUS + ALERTS
    ====================================================== --}}
    <div class="grid grid-cols-12 gap-5">

        {{-- LEFT: Weekly Trends Chart --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-full">
                <div class="px-6 pt-5 pb-4 flex items-center justify-between border-b border-gray-100">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Tren Aktivitas Mingguan</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Postingan &amp; member baru per hari — 7 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5 font-medium">
                            <span class="w-2.5 h-2.5 rounded-sm bg-blue-500 inline-block"></span>Postingan
                        </span>
                        <span class="flex items-center gap-1.5 font-medium">
                            <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500 inline-block"></span>Member Baru
                        </span>
                    </div>
                </div>

                <div class="px-6 pb-5 pt-4">
                    @php
                        $maxVal = max(
                            max(array_column($weeklyTrend, 'streams')) ?: 1,
                            max(array_column($weeklyTrend, 'members')) ?: 1
                        );
                    @endphp
                    <div class="flex items-end gap-2" style="height: 140px">
                        @foreach($weeklyTrend as $day)
                        @php
                            $sp = max(round(($day['streams'] / $maxVal) * 100), 4);
                            $mp = max(round(($day['members'] / $maxVal) * 100), 4);
                            $isToday = $day['label'] === date('D');
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            <div class="w-full flex items-end gap-0.5" style="height: 116px">
                                {{-- Streams bar --}}
                                <div class="flex-1 flex items-end h-full relative">
                                    <div class="w-full rounded-t-md transition-all duration-500 relative overflow-visible"
                                         style="height: {{ $sp }}%; background: linear-gradient(to top, #2563eb, #60a5fa);"
                                         title="{{ $day['streams'] }} postingan">
                                        @if($day['streams'] > 0)
                                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[9px] px-1.5 py-0.5 rounded-md opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10">
                                            {{ $day['streams'] }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Members bar --}}
                                <div class="flex-1 flex items-end h-full relative">
                                    <div class="w-full rounded-t-md transition-all duration-500 relative overflow-visible"
                                         style="height: {{ $mp }}%; background: linear-gradient(to top, #059669, #34d399);"
                                         title="{{ $day['members'] }} member baru">
                                        @if($day['members'] > 0)
                                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[9px] px-1.5 py-0.5 rounded-md opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-10">
                                            {{ $day['members'] }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-[10px] font-semibold {{ $isToday ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $day['label'] }}
                                @if($isToday)<span class="block w-1 h-1 rounded-full bg-red-500 mx-auto mt-0.5"></span>@endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Status Panel --}}
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">

            {{-- Platform Health --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex-1">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Status Platform</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-sm text-gray-700">
                            <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="globe" class="w-3.5 h-3.5 text-emerald-500"></i>
                            </div>
                            Website
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>ONLINE
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-sm text-gray-700">
                            <div class="w-7 h-7 {{ $dbStatus ? 'bg-emerald-50' : 'bg-red-50' }} rounded-lg flex items-center justify-center">
                                <i data-lucide="database" class="w-3.5 h-3.5 {{ $dbStatus ? 'text-emerald-500' : 'text-red-500' }}"></i>
                            </div>
                            Database
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold {{ $dbStatus ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : 'text-red-600 bg-red-50 border-red-200' }} border px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 {{ $dbStatus ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }} rounded-full"></span>
                            {{ $dbStatus ? 'CONNECTED' : 'ERROR' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-sm text-gray-700">
                            <div class="w-7 h-7 {{ $stats['pending_reports'] > 0 ? 'bg-red-50' : 'bg-emerald-50' }} rounded-lg flex items-center justify-center">
                                <i data-lucide="shield" class="w-3.5 h-3.5 {{ $stats['pending_reports'] > 0 ? 'text-red-500' : 'text-emerald-500' }}"></i>
                            </div>
                            Moderasi
                        </div>
                        @if($stats['pending_reports'] > 0)
                        <a href="{{ route('admin.reports') }}" class="flex items-center gap-1.5 text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full hover:bg-red-100 transition">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                            {{ $stats['pending_reports'] }} PENDING
                        </a>
                        @else
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>CLEAR
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pending Alert (if any) --}}
            @if($stats['pending_reports'] > 0)
            <a href="{{ route('admin.reports') }}" class="group block bg-gradient-to-br from-red-600 to-red-700 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:from-red-500 hover:to-red-600 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-4.5 h-4.5 text-white"></i>
                    </div>
                    <div>
                        <div class="text-white text-sm font-bold">{{ $stats['pending_reports'] }} Laporan Baru</div>
                        <div class="text-red-200 text-xs">Klik untuk tangani →</div>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>

    {{-- ======================================================
         ROW 3: RECENT MEMBERS + RECENT REPORTS (side by side)
    ====================================================== --}}
    <div class="grid grid-cols-12 gap-5">

        {{-- Recent Members --}}
        <div class="col-span-12 lg:col-span-7">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                        Member Terbaru
                    </h2>
                    <a href="{{ route('admin.members') }}" class="text-[11px] font-bold text-red-600 hover:text-red-800 transition flex items-center gap-1">
                        Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentMembers as $m)
                    <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50/70 transition">
                        <div class="relative shrink-0">
                            <img src="{{ $m->avatar_url }}" alt="{{ $m->username }}"
                                 class="w-9 h-9 rounded-full border-2 border-gray-100 object-cover bg-gray-100"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->username ?? 'U') }}&background=e5e7eb&color=6b7280&size=64'">
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white {{ $m->disabled == 0 ? 'bg-emerald-400' : ($m->disabled == 2 ? 'bg-red-400' : 'bg-amber-400') }}"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">{{ $m->fullname ?: $m->username }}</div>
                            <div class="text-xs text-gray-400">{{ '@'.$m->username }} · {{ date('d M Y', $m->created) }}</div>
                        </div>
                        <a href="{{ route('admin.members.show', $m->id) }}"
                           class="shrink-0 text-xs text-gray-400 hover:text-red-600 transition font-medium px-2.5 py-1 rounded-lg hover:bg-red-50">
                            Detail →
                        </a>
                    </div>
                    @empty
                    <div class="py-10 text-center text-sm text-gray-400">Belum ada member.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Reports / Recent Streams Summary --}}
        <div class="col-span-12 lg:col-span-5">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-full">
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="flag" class="w-4 h-4 text-gray-400"></i>
                        Laporan Terkini
                    </h2>
                    <a href="{{ route('admin.reports') }}" class="text-[11px] font-bold text-red-600 hover:text-red-800 transition flex items-center gap-1">
                        Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
                @if(count($recentReports) > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($recentReports as $r)
                    <div class="px-6 py-3 flex items-start gap-3 hover:bg-gray-50/70 transition">
                        <div class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-red-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 line-clamp-1 font-medium">{{ $r->message ?: ($r->url ?: 'Laporan #'.$r->id) }}</p>
                            <span class="text-xs text-gray-400">{{ date('d M Y', $r->created) }}</span>
                        </div>
                        <span class="shrink-0 text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full">Baru</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-emerald-500"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600">Tidak Ada Laporan</p>
                    <p class="text-xs text-gray-400 mt-1">Semua laporan sudah ditangani ✅</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
