@extends('layouts.admin')
@section('title', 'Members Management - Admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a> &gt;
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">ADMIN CP</a> &gt; MEMBERS
            </div>
            <h1 class="text-3xl font-black text-gray-900">MEMBERS MANAGEMENT</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola role, status, dan pantau aktivitas anggota komunitas.</p>
        </div>

        {{-- Toaster --}}
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

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @php
                $cards = [
                    ['label' => 'Total', 'val' => $stats['total'], 'color' => 'gray', 'icon' => 'users', 'filter' => null],
                    ['label' => 'Active', 'val' => $stats['active'], 'color' => 'green', 'icon' => 'user-check', 'filter' => 'active'],
                    ['label' => 'Pending', 'val' => $stats['pending'], 'color' => 'yellow', 'icon' => 'clock', 'filter' => 'pending'],
                    ['label' => 'Suspended', 'val' => $stats['suspended'], 'color' => 'red', 'icon' => 'user-x', 'filter' => 'suspended'],
                ];
            @endphp
            @foreach($cards as $card)
            <a href="{{ route('admin.members', array_merge(request()->except('status'), $card['filter'] ? ['status' => $card['filter']] : [])) }}"
               class="bg-white rounded-xl border {{ request('status') == $card['filter'] ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-200' }} p-4 shadow-sm flex items-center gap-4 hover:shadow-md transition-all">
                <div class="bg-{{ $card['color'] }}-50 p-2.5 rounded-lg">
                    <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 text-{{ $card['color'] }}-500"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $card['val'] }}</div>
                    <div class="text-[10px] text-gray-500 uppercase font-semibold">{{ $card['label'] }}</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Search & Filter Bar --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <form action="{{ route('admin.members') }}" method="GET" class="relative flex-1">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari username, nama, atau email..."
                       class="w-full bg-white border border-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 pl-10 pr-4 py-2.5 shadow-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.members', request()->except('search')) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </form>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.members') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 bg-white border border-gray-200 px-4 py-2.5 rounded-lg shadow-sm flex items-center gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Reset Filter
                </a>
            @endif
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold">User</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 font-bold">Role</th>
                            <th class="px-6 py-4 font-bold">Terakhir Login</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($members as $member)
                            <tr class="hover:bg-gray-50 transition-colors {{ $member->disabled == 2 ? 'bg-red-50/30' : ($member->disabled == 1 ? 'bg-yellow-50/30' : '') }}"
                                x-data="{ editRoleModal: false }">

                                {{-- User Info --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $member->avatar_url }}" alt="{{ $member->username }}"
                                             class="w-9 h-9 rounded-full object-cover border border-gray-200 bg-gray-100 shrink-0">
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $member->fullname ?: $member->username }}</div>
                                            <div class="text-xs text-gray-400">@{{ $member->username }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->disabled == 0)
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i> Active
                                        </span>
                                    @elseif($member->disabled == 1)
                                        <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                            <i data-lucide="clock" class="w-3 h-3"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                            <i data-lucide="slash" class="w-3 h-3"></i> Suspended
                                        </span>
                                    @endif
                                </td>

                                {{-- Role --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ ($member->level == 1 || in_array(strtolower($member->roles ?? ''), ['admin','administrator'])) ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $member->level == 1 || in_array(strtolower($member->roles ?? ''), ['admin','administrator']) ? 'Administrator' : ($member->roles ?: 'Member') }}
                                    </span>
                                </td>

                                {{-- Last Login --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    @if($member->lastlogin > 0)
                                        {{ date('d M Y, H:i', $member->lastlogin) }}
                                    @else
                                        <span class="text-gray-400 italic">Belum pernah</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('admin.members.show', $member->id) }}"
                                           class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors" title="Lihat Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>

                                        {{-- Edit Role Modal --}}
                                        <button @click="editRoleModal = true"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit Role">
                                            <i data-lucide="shield" class="w-4 h-4"></i>
                                        </button>

                                        {{-- Ban/Unban --}}
                                        <form action="{{ route('admin.members.ban', $member->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('{{ $member->disabled > 0 ? 'Aktifkan' : 'Suspend' }} pengguna {{ $member->username }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="{{ $member->disabled > 0 ? 'text-green-600 bg-green-50 hover:bg-green-100' : 'text-red-600 bg-red-50 hover:bg-red-100' }} p-2 rounded-lg transition-colors"
                                                    title="{{ $member->disabled > 0 ? 'Aktifkan' : 'Suspend' }}">
                                                <i data-lucide="{{ $member->disabled > 0 ? 'user-check' : 'user-x' }}" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Edit Role Modal --}}
                                    <div x-show="editRoleModal" x-cloak
                                         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-modal="true">
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="editRoleModal = false"></div>
                                        <div x-show="editRoleModal"
                                             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                             class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 z-10 text-left">
                                            <h3 class="text-base font-bold text-gray-900 mb-1">Ubah Role Pengguna</h3>
                                            <p class="text-sm text-gray-500 mb-4">Pengguna: <strong>{{ $member->fullname ?: $member->username }}</strong></p>
                                            <form action="{{ route('admin.members.role', $member->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <select name="roles" class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5 mb-4">
                                                    <option value="Member" {{ !in_array(strtolower($member->roles ?? ''), ['admin','administrator']) && $member->level != 1 ? 'selected' : '' }}>Member Biasa</option>
                                                    <option value="Administrator" {{ in_array(strtolower($member->roles ?? ''), ['admin','administrator']) || $member->level == 1 ? 'selected' : '' }}>Administrator</option>
                                                </select>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="editRoleModal = false"
                                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                                    <button type="submit"
                                                            class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    <i data-lucide="search-x" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                                    <p>Tidak ada anggota yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $members->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
