@extends('layouts.admin')
@section('title', 'AI Security Logs - Admin Panel XCODE')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8" x-data="{
    activeTab: 'logs',
    selectedLog: null,
    showAnalysisModal: false,
    showBlockModal: false,
    blockIpInput: '',
    blockReasonInput: '',
    blockLogIdInput: '',
    openAnalysis(log) {
        this.selectedLog = log;
        this.showAnalysisModal = true;
    },
    openBlockModal(ip, attackType, logId) {
        this.blockIpInput = ip || '';
        this.blockReasonInput = attackType ? ('Terdeteksi serangan AI SOC: ' + attackType) : 'Diblokir manual via AI Security Logs';
        this.blockLogIdInput = logId || '';
        this.showBlockModal = true;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">HOME &gt; ADMIN CP &gt; AI SECURITY LOGS</div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-black text-gray-900">AI SECURITY LOGS</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                        <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                        SANCTUM WEBHOOK ACTIVE
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1">
                    Pusat pemantauan laporan insiden keamanan real-time dari Agen AI (CrewAI / Tim B) via Private API Endpoint (Admin-Only Architecture).
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <form method="POST" action="{{ route('admin.ai-security-logs.simulate') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-gray-800 bg-white border border-gray-300 hover:bg-gray-50 transition shadow-sm">
                        <i data-lucide="cpu" class="w-4 h-4 text-red-600"></i>
                        Simulasi Webhook AI
                    </button>
                </form>

                @if($stats['total'] > 0)
                <form method="POST" action="{{ route('admin.ai-security-logs.clear') }}" onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat AI Security Logs? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus Semua Log
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Newly Generated Sanctum Token Alert --}}
        @if(session('new_sanctum_token'))
        <div class="mb-6 bg-emerald-50 border border-emerald-300 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-emerald-900 font-bold text-sm mb-1">
                        <i data-lucide="key-round" class="w-4 h-4 text-emerald-700"></i>
                        Token Sanctum Baru Berhasil Dibuat! Salin sekarang (hanya ditampilkan satu kali):
                    </div>
                    <div class="mt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <code id="sanctum-token-display" class="flex-1 px-3 py-2 bg-white border border-emerald-300 rounded-lg font-mono text-xs text-gray-900 select-all break-all">{{ session('new_sanctum_token') }}</code>
                        <button type="button"
                                onclick="navigator.clipboard.writeText(document.getElementById('sanctum-token-display').innerText); this.innerText = 'Tersalin!';"
                                class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-bold transition shrink-0">
                            Salin Token
                        </button>
                    </div>
                    <p class="text-[11px] text-emerald-800 mt-2">
                        Gunakan pada header HTTP script Python/CrewAI: <code class="font-mono bg-emerald-100 px-1.5 py-0.5 rounded">Authorization: Bearer {{ session('new_sanctum_token') }}</code> ke endpoint <code class="font-mono bg-emerald-100 px-1.5 py-0.5 rounded">POST {{ url('/api/ai-security-logs') }}</code>
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-600 text-[10px] tracking-wider uppercase">Total Insiden AI</h3>
                    <div class="text-red-500 bg-red-50 p-1.5 rounded-lg">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">{{ number_format($stats['total']) }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-600 text-[10px] tracking-wider uppercase">Critical &amp; High</h3>
                    <div class="text-orange-500 bg-orange-50 p-1.5 rounded-lg">
                        <i data-lucide="flame" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">{{ number_format($stats['critical_high'] ?? $stats['critical'] ?? 0) }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-600 text-[10px] tracking-wider uppercase">Insiden Hari Ini</h3>
                    <div class="text-amber-500 bg-amber-50 p-1.5 rounded-lg">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">{{ number_format($stats['today']) }}</div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-600 text-[10px] tracking-wider uppercase">IP Diblokir</h3>
                    <div class="text-purple-500 bg-purple-50 p-1.5 rounded-lg">
                        <i data-lucide="ban" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900">{{ number_format($stats['blocked_ips']) }}</div>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-gray-200 pb-3">
            <button type="button" @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition">
                <i data-lucide="list-filter" class="w-4 h-4"></i>
                Daftar Log Insiden ({{ $stats['total'] }})
            </button>

            <button type="button" @click="activeTab = 'blocked'"
                    :class="activeTab === 'blocked' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition">
                <i data-lucide="ban" class="w-4 h-4"></i>
                Daftar IP Diblokir ({{ $stats['blocked_ips'] }})
            </button>

            <button type="button" @click="activeTab = 'tokens'"
                    :class="activeTab === 'tokens' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition">
                <i data-lucide="key-round" class="w-4 h-4"></i>
                Sanctum Webhook API &amp; Token ({{ count($tokens) }})
            </button>
        </div>

        {{-- TAB 1: LOGS TABLE --}}
        <div x-show="activeTab === 'logs'">
            {{-- Filter & Search Bar --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
                <form method="GET" action="{{ route('admin.ai-security-logs') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    <div class="sm:col-span-6 flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 shrink-0"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Attack Type, Source IP, Target, atau isi analisis AI..."
                               class="w-full text-sm text-gray-700 outline-none placeholder-gray-400 bg-transparent">
                    </div>

                    <div class="sm:col-span-2">
                        <select name="severity" class="w-full text-xs font-semibold text-gray-700 border border-gray-200 rounded-lg px-3 py-2.5 outline-none">
                            <option value="">Semua Severity</option>
                            @foreach(['critical' => 'CRITICAL', 'high' => 'HIGH', 'medium' => 'MEDIUM', 'low' => 'LOW', 'info' => 'INFO'] as $val => $label)
                                <option value="{{ $val }}" @selected(request('severity') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <select name="status" class="w-full text-xs font-semibold text-gray-700 border border-gray-200 rounded-lg px-3 py-2.5 outline-none">
                            <option value="">Semua Status</option>
                            @foreach(['open' => 'OPEN', 'investigating' => 'INVESTIGATING', 'mitigated' => 'MITIGATED', 'ignored' => 'IGNORED'] as $val => $label)
                                <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'severity', 'status']))
                            <a href="{{ route('admin.ai-security-logs') }}" class="px-3 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @if($logs->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center px-4">
                        <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="shield-check" class="w-7 h-7 text-emerald-500"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-700 mb-1">Belum Ada Laporan Insiden AI</h3>
                        <p class="text-sm text-gray-400 max-w-md">
                            @if(request()->hasAny(['search', 'severity', 'status']))
                                Tidak ditemukan log yang cocok dengan filter pencarian Anda.
                            @else
                                Belum ada log ancaman dari Webhook Agentic AI. Klik tombol <strong>Simulasi Webhook AI</strong> di kanan atas untuk menguji alur data.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-5 py-3">Timestamp</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Attack Type</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Source IP</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Target</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Severity</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">AI Analysis</th>
                                    <th class="text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider px-4 py-3">Status</th>
                                    <th class="text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider px-5 py-3">Aksi Pencegahan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $blockedIpMap = $blockedIps->pluck('ip_address')->all();
                                @endphp
                                @foreach($logs as $log)
                                @php
                                    $sev = strtolower($log->severity ?? 'medium');
                                    $sevBadge = match($sev) {
                                        'critical' => 'bg-red-100 text-red-800 border-red-300',
                                        'high'     => 'bg-orange-100 text-orange-800 border-orange-300',
                                        'medium'   => 'bg-amber-100 text-amber-800 border-amber-300',
                                        'low'      => 'bg-blue-100 text-blue-800 border-blue-300',
                                        default    => 'bg-gray-100 text-gray-700 border-gray-300',
                                    };
                                    $isBlocked = (bool) $log->ip_blocked || ($log->source_ip && in_array($log->source_ip, $blockedIpMap, true));
                                    $ts = $log->detected_at ?? $log->created_at;
                                    $agentName = $log->raw_payload['agent_name'] ?? 'CrewAI Security Agent';
                                    $logJson = [
                                        'id' => $log->id,
                                        'timestamp' => $ts ? $ts->format('d M Y H:i:s') : '-',
                                        'attack_type' => $log->attack_type,
                                        'source_ip' => $log->source_ip ?: 'N/A',
                                        'target' => $log->target ?: 'N/A',
                                        'severity' => strtoupper($sev),
                                        'status' => strtoupper($log->status ?? 'open'),
                                        'agent_name' => $agentName,
                                        'ai_analysis' => $log->ai_analysis,
                                        'raw_payload' => $log->raw_payload,
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition align-top">
                                    {{-- Timestamp --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-xs font-semibold text-gray-800">{{ $ts ? $ts->format('d M Y') : '-' }}</div>
                                        <div class="text-[11px] font-mono text-gray-400">{{ $ts ? $ts->format('H:i:s') : '' }}</div>
                                    </td>

                                    {{-- Attack Type --}}
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-gray-900 text-xs">{{ $log->attack_type }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $agentName }}</div>
                                    </td>

                                    {{-- Source IP --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($log->source_ip)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                                    {{ $log->source_ip }}
                                                </span>
                                                @if($isBlocked)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-black bg-red-600 text-white uppercase">
                                                        BLOCKED
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Target --}}
                                    <td class="px-4 py-4 max-w-[180px]">
                                        <code class="text-xs text-gray-700 font-mono break-all line-clamp-2">{{ $log->target ?: '—' }}</code>
                                    </td>

                                    {{-- Severity --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $sevBadge }}">
                                            {{ strtoupper($sev) }}
                                        </span>
                                    </td>

                                    {{-- AI Analysis --}}
                                    <td class="px-4 py-4 max-w-xs">
                                        <p class="text-xs text-gray-600 line-clamp-2 break-words">{{ $log->ai_analysis }}</p>
                                        <button type="button"
                                                @click="openAnalysis(@js($logJson))"
                                                class="mt-1 inline-flex items-center gap-1 text-[11px] font-bold text-red-600 hover:text-red-800">
                                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                                            Lihat Analisis Lengkap
                                        </button>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.ai-security-logs.status', $log->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                    class="text-[11px] font-bold rounded-lg border border-gray-200 px-2 py-1 bg-gray-50 text-gray-700 cursor-pointer">
                                                @foreach(['open' => 'OPEN', 'investigating' => 'INVESTIGATING', 'mitigated' => 'MITIGATED', 'ignored' => 'IGNORED'] as $stVal => $stLabel)
                                                    <option value="{{ $stVal }}" @selected(($log->status ?? 'open') === $stVal)>{{ $stLabel }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            @if($log->source_ip)
                                                @if($isBlocked)
                                                    <form method="POST" action="{{ route('admin.ai-security-logs.unblock-ip') }}"
                                                          onsubmit="return confirm('Buka blokir untuk IP {{ $log->source_ip }}?');">
                                                        @csrf
                                                        <input type="hidden" name="ip_address" value="{{ $log->source_ip }}">
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                                            Unblock IP
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button"
                                                            @click="openBlockModal(@js($log->source_ip), @js($log->attack_type), @js($log->id))"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                                        <i data-lucide="ban" class="w-3.5 h-3.5"></i>
                                                        Block IP
                                                    </button>
                                                @endif
                                            @endif

                                            <form method="POST" action="{{ route('admin.ai-security-logs.destroy', $log->id) }}"
                                                  onsubmit="return confirm('Hapus log insiden keamanan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition"
                                                        title="Hapus Log">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($logs->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $logs->links() }}
                    </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- TAB 2: BLOCKED IPS MANAGEMENT --}}
        <div x-show="activeTab === 'blocked'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Manual Block Form --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 h-fit">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-1">Blokir IP Manual</h3>
                    <p class="text-xs text-gray-500 mb-4">Tambahkan alamat IP penyerang ke daftar pencegahan aktif (<code class="font-mono">blocked_ips</code> &amp; <code class="font-mono">jcow_banned</code>).</p>

                    <form method="POST" action="{{ route('admin.ai-security-logs.block-ip') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Alamat IP (IPv4 / IPv6)</label>
                            <input type="text" name="ip_address" required placeholder="Contoh: 185.220.101.42"
                                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 font-mono outline-none focus:border-red-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Pemblokiran</label>
                            <input type="text" name="reason" placeholder="Contoh: Brute Force / SQLi Attack"
                                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-red-600">
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold transition">
                            <i data-lucide="ban" class="w-4 h-4"></i>
                            Blokir IP Sekarang
                        </button>
                    </form>
                </div>

                {{-- Blocked IP List --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Daftar IP Terblokir Aktif</h3>
                        <span class="text-xs font-semibold text-gray-500">{{ count($blockedIps) }} IP</span>
                    </div>

                    @if($blockedIps->isEmpty())
                        <div class="p-12 text-center text-sm text-gray-400">
                            Belum ada alamat IP yang diblokir.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="text-left text-[10px] font-bold text-gray-500 uppercase px-5 py-3">IP Address</th>
                                        <th class="text-left text-[10px] font-bold text-gray-500 uppercase px-4 py-3">Alasan</th>
                                        <th class="text-left text-[10px] font-bold text-gray-500 uppercase px-4 py-3">Waktu Blokir</th>
                                        <th class="text-right text-[10px] font-bold text-gray-500 uppercase px-5 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($blockedIps as $bip)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-red-700">{{ $bip->ip_address }}</td>
                                        <td class="px-4 py-3.5 text-xs text-gray-700">{{ $bip->reason ?: '—' }}</td>
                                        <td class="px-4 py-3.5 text-xs text-gray-500">{{ $bip->created_at ? $bip->created_at->format('d M Y H:i') : '—' }}</td>
                                        <td class="px-5 py-3.5 text-right">
                                            <form method="POST" action="{{ route('admin.ai-security-logs.unblock-ip') }}"
                                                  onsubmit="return confirm('Buka blokir IP {{ $bip->ip_address }}?');">
                                                @csrf
                                                <input type="hidden" name="ip_address" value="{{ $bip->ip_address }}">
                                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                                    Unblock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 3: SANCTUM WEBHOOK TOKENS & INTEGRATION GUIDE --}}
        <div x-show="activeTab === 'tokens'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Left: Create & Manage Tokens --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-1">Buat Token Sanctum Baru</h3>
                        <p class="text-xs text-gray-500 mb-4">
                            Buat Bearer Token khusus untuk script Python / CrewAI (Tim B) agar dapat mengirim laporan JSON ke endpoint private.
                        </p>

                        <form method="POST" action="{{ route('admin.ai-security-logs.tokens.store') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Agen / Integrasi</label>
                                <input type="text" name="token_name" value="crewai-soc-webhook" required
                                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 font-mono outline-none focus:border-red-600">
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold transition">
                                <i data-lucide="key-round" class="w-4 h-4"></i>
                                Generate Sanctum Token
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Token Aktif ({{ count($tokens) }})</h3>
                        </div>
                        @if($tokens->isEmpty())
                            <div class="p-8 text-center text-xs text-gray-400">
                                Belum ada token Sanctum yang dibuat.
                            </div>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach($tokens as $token)
                                <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                                    <div>
                                        <div class="font-mono text-xs font-bold text-gray-900">{{ $token->name }}</div>
                                        <div class="text-[11px] text-gray-400">
                                            Dibuat: {{ $token->created_at ? \Carbon\Carbon::parse($token->created_at)->format('d M Y H:i') : '-' }}
                                            @if($token->last_used_at)
                                                &bull; Terakhir dipakai: {{ \Carbon\Carbon::parse($token->last_used_at)->diffForHumans() }}
                                            @endif
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('admin.ai-security-logs.tokens.destroy', $token->id) }}"
                                          onsubmit="return confirm('Cabut (revoke) token ini? Script yang menggunakan token ini tidak akan bisa mengakses API lagi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition">
                                            Revoke
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right: Webhook Specification for Tim B --}}
                <div class="lg:col-span-7 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-1">Dokumentasi Integrasi Webhook (Tim B - Agentic AI)</h3>
                    <p class="text-xs text-gray-500 mb-4">
                        Endpoint di bawah ini diamankan dengan <code class="font-mono">auth:sanctum</code> dan menyimpan laporan langsung ke tabel <code class="font-mono">ai_security_logs</code> tanpa membocorkannya ke feed publik.
                    </p>

                    <div class="space-y-4 text-xs">
                        <div>
                            <div class="font-bold text-gray-700 mb-1">Endpoint URL:</div>
                            <code class="block bg-gray-900 text-emerald-400 px-3 py-2 rounded-lg font-mono">POST {{ url('/api/ai-security-logs') }}</code>
                        </div>

                        <div>
                            <div class="font-bold text-gray-700 mb-1">Contoh Script Python (Tim B - CrewAI Webhook):</div>
                            <pre class="bg-gray-900 text-gray-100 p-3.5 rounded-lg font-mono text-[11px] overflow-x-auto leading-relaxed">import requests

API_URL = "{{ url('/api/ai-security-logs') }}"
SANCTUM_TOKEN = "PASTE_SANCTUM_BEARER_TOKEN_DISINI"

payload = {
    "timestamp": "2026-11-11T14:30:00+07:00",
    "attack_type": "SQL Injection (UNION-Based)",
    "source_ip": "185.220.101.42",
    "target": "GET /search?q=1' UNION SELECT 1,username,password--",
    "severity": "critical",
    "ai_analysis": "Agen SOC mendeteksi injeksi UNION SELECT pada endpoint pencarian. Disarankan memblokir IP 185.220.101.42."
}

response = requests.post(
    API_URL,
    json=payload,
    headers={
        "Authorization": f"Bearer {SANCTUM_TOKEN}",
        "Accept": "application/json"
    },
    timeout=10
)
print(response.status_code, response.json())</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL: AI ANALYSIS DETAIL --}}
    <div x-show="showAnalysisModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
         @keydown.escape.window="showAnalysisModal = false">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-gray-200 overflow-hidden"
             @click.outside="showAnalysisModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="cpu" class="w-5 h-5 text-red-600"></i>
                    <h3 class="text-base font-black text-gray-900">Detail Laporan Analisis Agen AI</h3>
                </div>
                <button type="button" @click="showAnalysisModal = false" class="text-gray-400 hover:text-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto" x-if="selectedLog">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-3.5 rounded-xl border border-gray-200 text-xs">
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Attack Type</div>
                        <div class="font-bold text-gray-900 mt-0.5" x-text="selectedLog.attack_type"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Source IP</div>
                        <div class="font-mono font-bold text-red-600 mt-0.5" x-text="selectedLog.source_ip"></div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Severity / Status</div>
                        <div class="font-bold text-gray-900 mt-0.5" x-text="selectedLog.severity + ' • ' + selectedLog.status"></div>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Target Endpoint / Payload</div>
                        <code class="block font-mono text-gray-800 mt-0.5 break-all" x-text="selectedLog.target"></code>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">AI Security Analysis &amp; Rekomendasi Mitigasi</div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-800 whitespace-pre-line leading-relaxed"
                         x-text="selectedLog.ai_analysis"></div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" @click="showAnalysisModal = false"
                        class="px-4 py-2 rounded-lg text-xs font-bold bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL: CONFIRM BLOCK IP --}}
    <div x-show="showBlockModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
         @keydown.escape.window="showBlockModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-gray-200 overflow-hidden"
             @click.outside="showBlockModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="ban" class="w-5 h-5 text-red-600"></i>
                    <h3 class="text-base font-black text-gray-900">Tindakan Pencegahan: Blokir IP</h3>
                </div>
                <button type="button" @click="showBlockModal = false" class="text-gray-400 hover:text-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.ai-security-logs.block-ip') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="log_id" :value="blockLogIdInput">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Source IP Penyerang</label>
                    <input type="text" name="ip_address" x-model="blockIpInput" readonly
                           class="w-full text-sm font-mono font-bold bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-red-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Pemblokiran</label>
                    <input type="text" name="reason" x-model="blockReasonInput" required
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-red-600">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="showBlockModal = false"
                            class="px-4 py-2 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-xs font-bold bg-red-600 text-white hover:bg-red-700 transition">
                        Konfirmasi Blokir IP
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
