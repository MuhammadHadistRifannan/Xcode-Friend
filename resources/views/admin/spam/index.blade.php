@extends('layouts.admin')
@section('title', 'Spam Log Monitor - Admin Panel XCODE')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">HOME &gt; ADMIN CP &gt; SPAM MONITOR</div>
                <h1 class="text-3xl font-black text-gray-900">SPAM LOG MONITOR</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau dan kelola log deteksi spam otomatis dari sistem.</p>
            </div>
            @if($stats['total'] > 0)
            <form method="POST" action="{{ route('admin.spam-logs.clear') }}" onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat spam log? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('POST')
                <button type="submit" id="btn-clear-all-spam" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Hapus Semua Log
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
                <i data-lucide="check-circle" class="w-4 h-4 text-green-500 shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @php
                $spamStatCards = [
                    ['label' => 'Total Terdeteksi', 'val' => $stats['total'], 'icon' => 'shield-alert', 'color' => 'red'],
                    ['label' => 'Hari Ini', 'val' => $stats['today'], 'icon' => 'clock', 'color' => 'orange'],
                    ['label' => '7 Hari Terakhir', 'val' => $stats['this_week'], 'icon' => 'calendar', 'color' => 'yellow'],
                    ['label' => 'Pengguna Unik', 'val' => $stats['unique_users'], 'icon' => 'user-x', 'color' => 'purple'],
                ];
            @endphp
            @foreach($spamStatCards as $c)
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

        {{-- Search Bar --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.spam-logs') }}" class="flex items-center gap-3 p-4">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari username, pesan, atau alasan deteksi..."
                       class="flex-1 text-sm text-gray-700 outline-none placeholder-gray-400">
                <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.spam-logs') }}" class="text-xs text-red-600 hover:text-red-800 font-medium">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="shield-check" class="w-7 h-7 text-green-500"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-700 mb-1">Tidak Ada Log Spam</h3>
                    <p class="text-sm text-gray-400">
                        @if(request('search'))
                            Tidak ada hasil untuk pencarian "<strong>{{ request('search') }}</strong>".
                        @else
                            Sistem tidak mendeteksi aktivitas spam saat ini. Platform aman! ✅
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-6 py-3">Pengguna</th>
                                <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Konten Terdeteksi</th>
                                <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Alasan</th>
                                <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Waktu</th>
                                <th class="text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $avatarSrc = $log->avatar
                                                ? (str_starts_with($log->avatar, 'http') ? $log->avatar : asset('storage/' . $log->avatar))
                                                : asset('assets/img/default-avatar.png');
                                        @endphp
                                        <img src="{{ $avatarSrc }}" alt="avatar"
                                             class="w-8 h-8 rounded-full border border-gray-200 object-cover bg-gray-100 shrink-0"
                                             onerror="this.src='{{ asset('assets/img/default-avatar.png') }}'">
                                        <div>
                                            @if($log->username)
                                                <a href="{{ route('profile.show', $log->username) }}" target="_blank"
                                                   class="font-semibold text-gray-900 hover:text-red-600 transition text-sm">
                                                    {{ $log->fullname ?: $log->username }}
                                                </a>
                                                <div class="text-xs text-gray-400">@{{ $log->username }}</div>
                                            @else
                                                <span class="font-semibold text-gray-500 text-sm">User #{{ $log->user_id }}</span>
                                                <div class="text-xs text-gray-400">Akun telah dihapus</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 max-w-xs">
                                    <p class="text-sm text-gray-700 line-clamp-2 break-words">{{ $log->message ?: '—' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @php $reasons = explode(',', $log->reasons ?? ''); @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($reasons as $reason)
                                            @if(trim($reason))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                                {{ trim($reason) }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-600">{{ date('d M Y', $log->created) }}</div>
                                    <div class="text-xs text-gray-400">{{ date('H:i', $log->created) }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.spam-logs.destroy', $log->id) }}"
                                          onsubmit="return confirm('Hapus log spam ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="btn-delete-spam-{{ $log->id }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $logs->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
