@extends('layouts.admin')
@section('title', 'Dashboard - Admin Panel XCODE')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">HOME &gt; ADMIN CP</div>
            <h1 class="text-3xl font-black text-gray-900">ADMIN CONTROL PANEL</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola komunitas XCODE — anggota, konten, dan pengaturan platform.</p>
        </div>

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">

                {{-- Stats: Members --}}
                <div>
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">👥 Members</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @php
                            $memberCards = [
                                ['label' => 'Total Members', 'val' => $stats['total_members'], 'icon' => 'users', 'color' => 'gray'],
                                ['label' => 'Active', 'val' => $stats['active_members'], 'icon' => 'user-check', 'color' => 'green'],
                                ['label' => 'Pending', 'val' => $stats['pending_members'], 'icon' => 'clock', 'color' => 'yellow'],
                                ['label' => 'Suspended', 'val' => $stats['suspended_members'], 'icon' => 'user-x', 'color' => 'red'],
                            ];
                        @endphp
                        @foreach($memberCards as $c)
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-600 text-[10px] tracking-wider uppercase">{{ $c['label'] }}</h3>
                                <div class="text-{{ $c['color'] }}-500 bg-{{ $c['color'] }}-50 p-1.5 rounded-lg">
                                    <i data-lucide="{{ $c['icon'] }}" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">{{ number_format($c['val']) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Stats: Content --}}
                <div>
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">📄 Konten</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @php
                            $contentCards = [
                                ['label' => 'Streams', 'val' => $stats['total_streams'], 'icon' => 'activity', 'color' => 'blue'],
                                ['label' => 'Komentar', 'val' => $stats['total_comments'], 'icon' => 'message-circle', 'color' => 'purple'],
                                ['label' => 'Foto', 'val' => $stats['total_photos'], 'icon' => 'image', 'color' => 'pink'],
                                ['label' => 'Video', 'val' => $stats['total_videos'], 'icon' => 'video', 'color' => 'orange'],
                                ['label' => 'Grup', 'val' => $stats['total_groups'], 'icon' => 'users-2', 'color' => 'teal'],
                                ['label' => 'Pages', 'val' => $stats['total_pages'], 'icon' => 'file', 'color' => 'indigo'],
                            ];
                        @endphp
                        @foreach($contentCards as $c)
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="bg-{{ $c['color'] }}-50 p-2.5 rounded-lg shrink-0">
                                <i data-lucide="{{ $c['icon'] }}" class="w-5 h-5 text-{{ $c['color'] }}-500"></i>
                            </div>
                            <div>
                                <div class="text-xl font-black text-gray-900">{{ number_format($c['val']) }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-semibold">{{ $c['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Members --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            Member Terbaru
                        </h2>
                        <a href="{{ route('admin.members') }}" class="text-xs font-bold text-red-600 hover:text-red-800">Lihat Semua →</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($recentMembers as $m)
                        <div class="px-6 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $m->avatar_url }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover bg-gray-100">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $m->fullname ?: $m->username }}</div>
                                    <div class="text-xs text-gray-400">{{ date('d M Y', $m->created) }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.members.show', $m->id) }}"
                               class="text-xs text-gray-500 hover:text-red-600 font-medium">Detail →</a>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pending Reports --}}
                @if($stats['pending_reports'] > 0)
                <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-100 flex justify-between items-center bg-red-50">
                        <h2 class="text-sm font-bold text-red-800 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i>
                            Laporan Menunggu ({{ $stats['pending_reports'] }})
                        </h2>
                        <a href="{{ route('admin.reports') }}" class="text-xs font-bold text-red-700 hover:text-red-900">Tangani →</a>
                    </div>
                    <div class="divide-y divide-red-50">
                        @foreach($recentReports as $r)
                        <div class="px-6 py-3 flex items-center justify-between">
                            <p class="text-sm text-gray-700 line-clamp-1">{{ $r->message ?: $r->url }}</p>
                            <span class="text-xs text-gray-400 shrink-0 ml-4">{{ date('d M', $r->created) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: Platform Status + Quick Links --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">

                {{-- Platform Status --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-gray-400"></i>
                        Platform Status
                    </h2>
                    <ul class="flex flex-col gap-4">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i> Website
                            </div>
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">ONLINE</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <i data-lucide="database" class="w-4 h-4 text-gray-400"></i> Database
                            </div>
                            <span class="text-[10px] font-bold {{ $dbStatus ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded">
                                {{ $dbStatus ? 'CONNECTED' : 'ERROR' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <i data-lucide="shield-check" class="w-4 h-4 text-gray-400"></i> Moderation
                            </div>
                            <span class="text-[10px] font-bold {{ $stats['pending_reports'] > 0 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' }} px-2 py-1 rounded">
                                {{ $stats['pending_reports'] > 0 ? $stats['pending_reports'].' PENDING' : 'CLEAR' }}
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Quick Access --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4 text-gray-400"></i>
                        Quick Access
                    </h2>
                    <div class="flex flex-col gap-2">
                        @php
                            $quickLinks = [
                                ['name' => 'Members', 'route' => 'admin.members', 'icon' => 'users', 'color' => 'blue'],
                                ['name' => 'Reports', 'route' => 'admin.reports', 'icon' => 'alert-triangle', 'color' => 'red'],
                                ['name' => 'Stream Monitor', 'route' => 'admin.stream-monitor', 'icon' => 'activity', 'color' => 'purple'],
                                ['name' => 'Site Config', 'route' => 'admin.site-config', 'icon' => 'settings', 'color' => 'gray'],
                                ['name' => 'User Roles', 'route' => 'admin.user-roles', 'icon' => 'shield', 'color' => 'green'],
                                ['name' => 'Custom Fields', 'route' => 'admin.custom-fields', 'icon' => 'layout-list', 'color' => 'orange'],
                            ];
                        @endphp
                        @foreach($quickLinks as $ql)
                        <a href="{{ route($ql['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors border border-gray-100 hover:border-gray-200">
                            <i data-lucide="{{ $ql['icon'] }}" class="w-4 h-4 text-{{ $ql['color'] }}-500"></i>
                            {{ $ql['name'] }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Logged in as --}}
                <div class="bg-gray-900 rounded-xl p-4 text-white">
                    <div class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-2">Logged In As</div>
                    <div class="flex items-center gap-3">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 rounded-full border-2 border-red-500 object-cover">
                        <div>
                            <div class="font-bold text-sm">{{ auth()->user()->fullname ?: auth()->user()->username }}</div>
                            <div class="text-xs text-gray-400">Administrator</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
