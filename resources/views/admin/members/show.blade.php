@extends('layouts.admin')
@section('title', 'Detail Member - Admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a> &gt;
            <a href="{{ route('admin.members') }}" class="hover:text-red-600">MEMBERS</a> &gt;
            {{ strtoupper($member->username) }}
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-12 gap-6">

            {{-- LEFT: Profile Card --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                {{-- Profile Card --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-20 bg-gradient-to-r from-red-600 to-red-800"></div>
                    <div class="px-6 pb-6">
                        <div class="-mt-10 mb-4">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->fullname }}"
                                 class="w-20 h-20 rounded-full border-4 border-white shadow-md object-cover bg-gray-100">
                        </div>
                        <h2 class="text-xl font-black text-gray-900">{{ $member->fullname ?: $member->username }}</h2>
                        <p class="text-sm text-gray-500 mb-1">@{{ $member->username }}</p>
                        <p class="text-xs text-gray-400 mb-4">{{ $member->email }}</p>

                        {{-- Status Badge --}}
                        @php
                            $statusMap = [0 => ['Active', 'green'], 1 => ['Pending', 'yellow'], 2 => ['Suspended', 'red']];
                            [$statusLabel, $statusColor] = $statusMap[$member->disabled] ?? ['Unknown', 'gray'];
                        @endphp
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex items-center gap-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 text-xs font-bold px-3 py-1 rounded-full">
                                <i data-lucide="{{ $member->disabled == 0 ? 'check-circle' : ($member->disabled == 1 ? 'clock' : 'slash') }}" class="w-3 h-3"></i>
                                {{ $statusLabel }}
                            </span>
                            @if($member->level == 1 || in_array(strtolower($member->roles ?? ''), ['admin','administrator']))
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">
                                    <i data-lucide="shield" class="w-3 h-3"></i> Admin
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1 text-xs text-gray-500">
                            <div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $member->location ?: '-' }}</div>
                            <div class="flex items-center gap-2"><i data-lucide="calendar" class="w-3 h-3"></i> Bergabung: {{ date('d M Y', $member->created) }}</div>
                            <div class="flex items-center gap-2"><i data-lucide="activity" class="w-3 h-3"></i> Terakhir login: {{ $member->lastlogin > 0 ? date('d M Y H:i', $member->lastlogin) : 'Belum pernah' }}</div>
                            <div class="flex items-center gap-2"><i data-lucide="monitor" class="w-3 h-3"></i> IP: {{ $member->ipaddress ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Statistik</h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-2xl font-black text-gray-900">{{ $streamCount }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">Posts</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-2xl font-black text-gray-900">{{ $commentCount }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">Komentar</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-2xl font-black text-{{ $reportCount > 0 ? 'red' : 'gray' }}-{{ $reportCount > 0 ? '600' : '900' }}">{{ $reportCount }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">Laporan</div>
                        </div>
                    </div>
                </div>

                {{-- Quick Link --}}
                <a href="{{ route('profile.show', $member->username) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Lihat Profil Publik
                </a>
            </div>

            {{-- RIGHT: Actions --}}
            <div class="col-span-12 lg:col-span-8 space-y-4">

                {{-- Update Status --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="toggle-left" class="w-4 h-4 text-gray-400"></i>
                        Status Akun
                    </h3>
                    <form action="{{ route('admin.members.status', $member->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="disabled" value="0" {{ $member->disabled == 0 ? 'checked' : '' }} class="accent-green-600">
                                <span class="text-sm font-semibold text-green-700">Active</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="disabled" value="1" {{ $member->disabled == 1 ? 'checked' : '' }} class="accent-yellow-600">
                                <span class="text-sm font-semibold text-yellow-700">Pending</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="disabled" value="2" {{ $member->disabled == 2 ? 'checked' : '' }} class="accent-red-600">
                                <span class="text-sm font-semibold text-red-700">Suspended</span>
                            </label>
                            <button type="submit" class="ml-auto bg-gray-900 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Simpan Status
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Update Role --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4 text-gray-400"></i>
                        Role / Hak Akses
                    </h3>
                    <form action="{{ route('admin.members.role', $member->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="flex items-center gap-3">
                            <select name="roles" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                                <option value="Member" {{ !in_array(strtolower($member->roles ?? ''), ['admin','administrator']) && $member->level != 1 ? 'selected' : '' }}>Member Biasa</option>
                                <option value="Administrator" {{ in_array(strtolower($member->roles ?? ''), ['admin','administrator']) || $member->level == 1 ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                                Simpan Role
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Recent Posts --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                            5 Postingan Terakhir
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentStreams as $s)
                            <div class="px-6 py-3">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $s->message ?: '(media tanpa teks)' }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ date('d M Y H:i', $s->created) }} &bull; Type: {{ $s->type }}</p>
                            </div>
                        @empty
                            <div class="px-6 py-6 text-center text-gray-400 text-sm">Belum ada postingan.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Danger Zone --}}
                @if($member->id !== auth()->id() && $member->level != 1)
                <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        Danger Zone
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Menghapus pengguna akan menghapus semua postingan, komentar, pesan, dan data terkait secara permanen.</p>
                    <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST"
                          onsubmit="return confirm('Anda yakin ingin menghapus {{ $member->username }} secara permanen? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
                            Hapus Pengguna Permanen
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
