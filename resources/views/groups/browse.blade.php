@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Title & Navigation -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 flex items-center gap-2 mb-4">
            <svg class="w-8 h-8 text-gray-700 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Groups
        </h1>

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('groups.mine') }}" class="border-transparent text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 hover:border-gray-300 dark:hover:border-neutral-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Mine
                </a>
                <a href="{{ route('groups.browse') }}" class="border-red-600 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Search
                </a>
            </nav>
        </div>

        <!-- Search Bar (Sesuai gaya form Pages) -->
        <div class="relative max-w-sm mt-6">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-neutral-500 pointer-events-none flex items-center">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input
                type="text"
                id="group-search"
                value="{{ $q ?? request('q', '') }}"
                placeholder="Cari grup..."
                oninput="filterCards(this.value)"
                class="w-full bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:bg-white dark:focus:bg-neutral-800 transition-colors"
            >
        </div>
    </div>

    <!-- Layout Container -->
    <div class="flex flex-col lg:flex-row gap-8 mt-6">
        
        <!-- MAIN CONTENT (Left) -->
        <div class="w-full lg:w-2/3">
            <!-- Group Results -->
            <div id="groups-list" class="space-y-4">
                @if($groups->isEmpty())
                    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-gray-200 dark:border-neutral-700 p-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-neutral-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="users" class="w-8 h-8 text-gray-400 dark:text-neutral-500"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-2">Belum ada Grup</h3>
                        <p class="text-gray-500 dark:text-neutral-400">Saat ini belum ada grup yang tersedia.</p>
                    </div>
                @else
                    @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group->id) }}" class="group-card bg-white dark:bg-neutral-800 rounded-md border border-gray-200 dark:border-neutral-700 p-4 flex gap-4 shadow-sm hover:shadow-md hover:border-red-300 dark:hover:border-red-500/50 transition group block cursor-pointer">
                            <div class="flex-shrink-0">
                                <img src="{{ $group->logo_url ?: asset('assets/img/default.png') }}"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                     class="w-16 h-16 rounded border border-gray-100 dark:border-neutral-700 bg-gray-100 dark:bg-neutral-700 object-cover" alt="{{ $group->name }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 group-hover:text-red-600 dark:group-hover:text-red-400 leading-tight block truncate">{{ $group->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2 line-clamp-2">{{ $group->description ?: 'No description provided.' }}</p>
                                <p class="text-xs text-gray-500 dark:text-neutral-500 mt-2">{{ $group->members_count ?? $group->members()->count() }} members</p>
                            </div>
                        </a>
                    @endforeach

                    <!-- Dynamic Empty State when client search has no match -->
                    <div id="no-search-results" style="display: none;" class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-gray-200 dark:border-neutral-700 p-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-neutral-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="search-x" class="w-8 h-8 text-gray-400 dark:text-neutral-500"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-2">Grup Tidak Ditemukan</h3>
                        <p class="text-gray-500 dark:text-neutral-400">Tidak ada grup yang cocok dengan kata kunci pencarian Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="w-full lg:w-1/3 space-y-6">
            <x-sidebar-right />
        </div>

    </div>
</div>

<script>
    function filterCards(query) {
        const q = (query || '').toLowerCase().trim();
        const cards = document.querySelectorAll('#groups-list > a.group-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('p')?.textContent.toLowerCase() || '';
            const match = title.includes(q) || desc.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        const emptySearchState = document.getElementById('no-search-results');
        if (emptySearchState) {
            emptySearchState.style.display = (visibleCount === 0 && cards.length > 0) ? '' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('group-search');
        if (searchInput && searchInput.value) {
            filterCards(searchInput.value);
        }
    });
</script>
@endsection
