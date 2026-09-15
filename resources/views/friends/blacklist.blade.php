@extends('layouts.app')

@section('title', 'Daftar Blokir')

@section('content')
<div class="pb-10 bg-[#fafafa] min-h-screen">
    <div class="w-full px-4 lg:px-20 mx-auto">

        {{-- Breadcrumb --}}
        <div class="pt-6 mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-600 transition">HOME</a>
                <span>&rsaquo;</span>
                <a href="{{ route('friends.index') }}" class="hover:text-gray-600 transition">TEMAN</a>
                <span>&rsaquo;</span>
                <span class="text-gray-700 font-semibold">BLACKLIST</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Daftar Blokir</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengguna yang telah kamu blokir.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            {{-- KONTEN UTAMA --}}
            <div class="w-full lg:w-[78%]">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Navigasi Tab --}}
                    <div class="border-b border-gray-100 px-6">
                        <div class="flex space-x-6">
                            <a href="{{ route('friends.index') }}" class="py-4 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition whitespace-nowrap">Teman</a>
                            <a href="{{ route('friends.requests') }}" class="py-4 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition whitespace-nowrap">Permintaan</a>
                            <a href="{{ route('friends.blacklist') }}" class="py-4 text-sm font-bold text-[#b71c1c] border-b-2 border-[#b71c1c] whitespace-nowrap">Blokir</a>
                        </div>
                    </div>

                    {{-- Header + Search --}}
                    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-gray-50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#b71c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span class="text-sm font-semibold text-gray-700">Pengguna Diblokir</span>
                            <span class="bg-[#b71c1c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $blockedUsers->count() }}</span>
                        </div>
                        <div class="relative w-full sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                            <input type="text" id="searchBlocked" placeholder="Cari pengguna..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#b71c1c]/30 focus:border-[#b71c1c] transition">
                        </div>
                    </div>

                    {{-- Daftar --}}
                    <div class="divide-y divide-gray-50" id="blockedList">
                        @forelse($blockedUsers as $blocked)
                            <div class="blocked-item flex items-center gap-4 px-6 py-4 hover:bg-gray-50/70 transition group" data-name="{{ strtolower($blocked->fullname) }}" data-username="{{ strtolower($blocked->username) }}">
                                {{-- Avatar --}}
                                <div class="relative flex-shrink-0">
                                    @if(!empty($blocked->avatar))
                                        <img src="{{ asset('uploads/avatars/' . $blocked->avatar) }}" alt="{{ $blocked->fullname }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#f4dada] to-[#fce8e8] flex items-center justify-center text-[#b71c1c] font-bold text-lg ring-2 ring-gray-100" style="display:none;">{{ strtoupper(substr($blocked->fullname, 0, 1)) }}</div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#f4dada] to-[#fce8e8] flex items-center justify-center text-[#b71c1c] font-bold text-lg ring-2 ring-gray-100">{{ strtoupper(substr($blocked->fullname, 0, 1)) }}</div>
                                    @endif
                                    <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-[#b71c1c] rounded-full flex items-center justify-center ring-2 ring-white">
                                        <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                                {{-- Info --}}
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $blocked->fullname }}</p>
                                    <p class="text-xs text-gray-400 truncate">@{{ $blocked->username }}</p>
                                </div>
                                {{-- Buka Blokir --}}
                                <button type="button" onclick="openUnblockModal({{ $blocked->id }}, '{{ addslashes($blocked->fullname) }}')" class="flex-shrink-0 flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#b71c1c] bg-gray-100 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    Buka Blokir
                                </button>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-600 mb-1">Belum ada pengguna yang diblokir</h3>
                                <p class="text-sm text-gray-400">Daftar blokir kamu saat ini kosong.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- No search result --}}
                    <div id="noResult" class="hidden flex-col items-center justify-center py-10 px-6 text-center">
                        <p class="text-sm text-gray-400">Tidak ada hasil untuk pencarian tersebut.</p>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="w-full lg:w-[22%] space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#b71c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800">Tentang Blokir</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-500">
                        <li class="flex items-start gap-2"><span class="text-[#b71c1c] mt-0.5 font-bold">&bull;</span>Pengguna yang diblokir tidak bisa melihat profilmu</li>
                        <li class="flex items-start gap-2"><span class="text-[#b71c1c] mt-0.5 font-bold">&bull;</span>Mereka tidak bisa mengirim permintaan pertemanan</li>
                        <li class="flex items-start gap-2"><span class="text-[#b71c1c] mt-0.5 font-bold">&bull;</span>Mereka tidak dapat mengirim pesan kepadamu</li>
                        <li class="flex items-start gap-2"><span class="text-[#b71c1c] mt-0.5 font-bold">&bull;</span>Kamu bisa membuka blokir kapan saja</li>
                    </ul>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Statistik</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Total Diblokir</span>
                        <span class="text-2xl font-extrabold text-[#b71c1c]">{{ $blockedUsers->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="unblockModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#b71c1c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Buka Blokir Pengguna</h3>
                    <p class="text-xs text-gray-400">Tindakan ini tidak bisa dibatalkan secara otomatis</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-6">Yakin ingin membuka blokir <span id="modalUserName" class="font-semibold text-gray-900"></span>? Pengguna ini akan bisa berinteraksi denganmu kembali.</p>
            <div class="flex gap-3">
                <button onclick="closeUnblockModal()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</button>
                <form id="unblockForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-[#b71c1c] hover:bg-[#9a1515] rounded-xl transition">Ya, Buka Blokir</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchBlocked');
    const items       = document.querySelectorAll('.blocked-item');
    const noResult    = document.getElementById('noResult');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            items.forEach(item => {
                const name     = item.dataset.name || '';
                const username = item.dataset.username || '';
                const match    = name.includes(q) || username.includes(q);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            noResult.classList.toggle('hidden', visible > 0 || q === '');
            noResult.classList.toggle('flex', visible === 0 && q !== '');
        });
    }

    function openUnblockModal(userId, userName) {
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('unblockForm').action = '/friends/unblock/' + userId;
        const modal = document.getElementById('unblockModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeUnblockModal() {
        const modal = document.getElementById('unblockModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('unblockModal').addEventListener('click', function(e) {
        if (e.target === this) closeUnblockModal();
    });
</script>
@endpush

@endsection
