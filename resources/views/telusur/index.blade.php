@extends('layouts.app')

@section('title', 'Telusur')

@section('content')
<div class="pt-10 pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto flex flex-col lg:flex-row gap-6 lg:gap-12">

        <!-- KONTEN KIRI: Telusur -->
        <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm">

            <!-- Header -->
            <div class="px-8 pt-8 pb-4">
                <h1 class="text-2xl font-bold text-gray-900 border-b-2 border-[#b71c1c] pb-3 inline-block">TELUSUR</h1>
            </div>

            <!-- Filter Members -->
            <div class="px-8 pb-6">
                <div class="bg-white rounded-[14px] p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h2 class="text-sm font-bold text-gray-900">Filter Members</h2>
                    </div>

                    <form method="GET" action="{{ route('telusur.index') }}" id="filterForm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">JENIS KELAMIN</label>
                                <select name="gender" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] outline-none">
                                    <option value="all" {{ ($filters['gender'] ?? '') == 'all' || !isset($filters['gender']) ? 'selected' : '' }}>Keduanya</option>
                                    <option value="1" {{ ($filters['gender'] ?? '') == '1' ? 'selected' : '' }}>Cowok</option>
                                    <option value="2" {{ ($filters['gender'] ?? '') == '2' ? 'selected' : '' }}>Cewek</option>
                                </select>
                            </div>

                            <!-- Umur -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">UMUR</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="umur_min" value="{{ $filters['umur_min'] ?? '' }}" placeholder="Min" min="0" max="120" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] outline-none">
                                    <span class="text-gray-400 text-sm">s/d</span>
                                    <input type="number" name="umur_max" value="{{ $filters['umur_max'] ?? '' }}" placeholder="Max" min="0" max="120" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] outline-none">
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">STATUS</label>
                                <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] outline-none">
                                    <option value="all" {{ ($filters['status'] ?? '') == 'all' || !isset($filters['status']) ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Jomblo" {{ ($filters['status'] ?? '') == 'Jomblo' ? 'selected' : '' }}>Jomblo</option>
                                    <option value="TTM" {{ ($filters['status'] ?? '') == 'TTM' ? 'selected' : '' }}>TTM</option>
                                    <option value="Pacaran" {{ ($filters['status'] ?? '') == 'Pacaran' ? 'selected' : '' }}>Pacaran</option>
                                    <option value="Menikah" {{ ($filters['status'] ?? '') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Tunangan" {{ ($filters['status'] ?? '') == 'Tunangan' ? 'selected' : '' }}>Tunangan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                            <!-- Lokasi -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-700 mb-1">LOKASI</label>
                                <div id="lokasiWrapper" class="relative">
                                    <input type="text" id="lokasiInput" readonly value="{{ $filters['lokasi'] ?? '' }}" placeholder="Semua Lokasi" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] focus:border-[#b71c1c] outline-none shadow-sm cursor-pointer pr-10">
                                    <input type="hidden" name="lokasi" id="lokasiValue" value="{{ $filters['lokasi'] ?? '' }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div id="lokasiDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                                        <div class="px-4 py-2 border-b border-gray-100 sticky top-0 bg-white">
                                            <input type="text" id="lokasiSearch" placeholder="Cari lokasi..." class="w-full text-sm outline-none">
                                        </div>
                                        <div id="lokasiList" class="py-1">
                                            @foreach($locations as $loc)
                                                <div class="lokasi-option px-4 py-2.5 text-sm cursor-pointer hover:bg-[#f4dada] hover:text-[#b71c1c] transition {{ ($filters['lokasi'] ?? '') == $loc ? 'bg-[#f4dada] text-[#b71c1c] font-bold' : 'text-gray-700' }}" data-value="{{ $loc }}">{{ $loc }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Diutur Berdasarkan + Tombol -->
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">DIURUT BERDASARKAN</label>
                                    <select name="sort" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-[#b71c1c] outline-none">
                                        <option value="terbaru" {{ ($filters['sort'] ?? 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="terakhir_aktif" {{ ($filters['sort'] ?? '') == 'terakhir_aktif' ? 'selected' : '' }}>Terakhir Aktif</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-[#b71c1c] hover:bg-red-800 text-white px-8 py-2.5 rounded-xl text-sm font-bold transition self-end">
                                    PERBARUI
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Members Grid -->
            <div class="px-8 pb-8 flex-grow">
                @if($members->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($members as $member)
                            @php
                                $genderLabel = match($member->gender) {
                                    1 => 'Cowok',
                                    2 => 'Cewek',
                                    default => 'Keduanya',
                                };
                                $age = $member->birthyear > 0 ? (int) date('Y') - $member->birthyear : null;
                            @endphp
                            <div class="bg-white rounded-[14px] p-6 shadow-sm flex flex-col items-center text-center hover:shadow-md transition">
                                <!-- Avatar -->
                                <div class="w-20 h-20 rounded-full bg-[#f4dada] flex items-center justify-center overflow-hidden mb-3">
                                    @if($member->avatar)
                                        <img src="{{ asset('storage/avatars/' . $member->avatar) }}" alt="{{ $member->username }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[#b71c1c] font-bold text-2xl">{{ substr($member->fullname ?? $member->username, 0, 1) }}</span>
                                    @endif
                                </div>

                                <!-- Username -->
                                <a href="#" class="text-sm font-bold text-[#b71c1c] hover:text-red-800 transition">{{ $member->username }}</a>

                                <!-- Gender + Umur -->
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $genderLabel }}{{ $age !== null ? ', ' . $age : '' }}
                                </p>

                                <!-- Lokasi -->
                                @if($member->location)
                                    <div class="flex items-center gap-1 mt-2 text-xs text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $member->location }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $members->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-[14px] shadow-sm p-20 text-center">
                        <p class="text-gray-400 text-sm">Tidak ada member yang ditemukan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KONTEN KANAN: Sidebar -->
        <div class="w-full lg:w-[15%] space-y-6">
            <div class="bg-white p-8 rounded-[24px] shadow-sm flex flex-col items-center">
                <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                <div class="flex text-[#ffc107] mb-2 gap-1">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                <p class="text-xs text-blue-600 font-medium hover:underline cursor-pointer">532 Reviews</p>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm">
                <h3 class="text-[15px] font-bold text-gray-900 mb-4">Network Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">LinkedIn</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">phpBB Group</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">Facebook</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const lokasiInput = document.getElementById('lokasiInput');
    const lokasiValue = document.getElementById('lokasiValue');
    const lokasiDropdown = document.getElementById('lokasiDropdown');
    const lokasiSearch = document.getElementById('lokasiSearch');
    const lokasiList = document.getElementById('lokasiList');
    const allOptions = Array.from(document.querySelectorAll('.lokasi-option'));

    // Toggle dropdown
    lokasiInput.addEventListener('click', function(e) {
        e.stopPropagation();
        lokasiDropdown.classList.toggle('hidden');
        if (!lokasiDropdown.classList.contains('hidden')) {
            lokasiSearch.value = '';
            filterLokasi('');
            lokasiSearch.focus();
        }
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!document.getElementById('lokasiWrapper').contains(e.target)) {
            lokasiDropdown.classList.add('hidden');
        }
    });

    // Search filter
    lokasiSearch.addEventListener('input', function() {
        filterLokasi(this.value.toLowerCase());
    });

    function filterLokasi(query) {
        allOptions.forEach(opt => {
            const text = opt.getAttribute('data-value').toLowerCase();
            opt.style.display = text.includes(query) ? '' : 'none';
        });
    }

    // Select option
    allOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            lokasiValue.value = val;
            lokasiInput.value = val;
            lokasiDropdown.classList.add('hidden');
            // Highlight selected
            allOptions.forEach(o => {
                o.classList.remove('bg-[#f4dada]', 'text-[#b71c1c]', 'font-bold');
                o.classList.add('text-gray-700');
            });
            this.classList.remove('text-gray-700');
            this.classList.add('bg-[#f4dada]', 'text-[#b71c1c]', 'font-bold');
        });
    });

    // Clear on Escape
    lokasiSearch.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            lokasiDropdown.classList.add('hidden');
        }
    });
</script>
@endpush
