@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
    <!-- Header Section -->
    <div class="mb-6 relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">My Pages</h1>
                <p class="text-sm text-gray-600">Manage and monitor your active deployments and assets.</p>
            </div>
            <a href="{{ route('pages.create') }}" class="bg-[#b91c1c] hover:bg-red-800 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 self-start md:self-auto transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create a page
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6 flex gap-6">
        <button id="tab-created" onclick="switchTab('created')" class="pb-3 text-sm font-bold text-red-600 border-b-2 border-red-600 transition-colors">
            Pages I created
        </button>
        <button id="tab-liked" onclick="switchTab('liked')" class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
            Pages I liked
        </button>
    </div>

    <!-- ===================== -->
    <!-- Tab: Pages I Created  -->
    <!-- ===================== -->
    <div id="panel-created">
        <!-- Table Header -->
        <div class="grid grid-cols-12 gap-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 px-4">
            <div class="col-span-1">LOGO</div>
            <div class="col-span-9">DESIGNATION &amp; OBJECTIVE</div>
            <div class="col-span-2 text-right">LAST UPDATED</div>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-gray-200 mb-4"></div>

        <!-- List Items -->
        <div class="space-y-4">
            @foreach($createdPages as $page)
            <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start gap-6 hover:shadow-sm transition-shadow">
                <div class="bg-gray-100 p-3 rounded-md shrink-0">
                    <i data-lucide="file-text" class="w-6 h-6 text-gray-500"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-gray-900">{{ $page['title'] }}</h3>
                        @if($page['status'] === 'active')
                            <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase">ACTIVE</span>
                        @elseif($page['status'] === 'verified')
                            <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200 uppercase">VERIFIED</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">{{ strtoupper($page['status']) }}</span>
                        @endif
                        <span class="text-xs text-gray-500 flex items-center gap-1">
                            <i data-lucide="heart" class="w-3 h-3"></i> {{ number_format($page['likes']) }} Likes
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">{{ $page['description'] }}</p>
                </div>
                <div class="text-xs text-gray-500 shrink-0">{{ $page['lastUpdate'] }}</div>
            </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        <div class="flex justify-center items-center gap-1 mt-10">
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-500 hover:bg-gray-50 bg-white">
                &lt; Previous
            </button>
            <button class="px-3 py-1 text-sm border border-red-700 bg-red-700 text-white rounded">
                1
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                2
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                3
            </button>
            <span class="px-2 text-gray-400">...</span>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                10
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                Next &gt;
            </button>
        </div>
    </div>

    <!-- ==================== -->
    <!-- Tab: Pages I Liked   -->
    <!-- ==================== -->
    <div id="panel-liked" class="hidden">
        <!-- Search Bar (same as pages/index) -->
        <div class="relative max-w-sm mb-6">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input 
                type="text" 
                id="liked-search"
                placeholder="Search liked pages..." 
                class="w-full bg-gray-100 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:bg-white transition-colors"
                oninput="filterLikedPages(this.value)"
            >
        </div>

        <!-- Grid Section (same layout as pages/index) -->
        <div id="liked-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($likedPages as $page)
                <x-page-card :page="$page" />
            @endforeach
        </div>

        <!-- Pagination Section -->
        <div class="flex justify-center items-center gap-1 mt-10">
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-500 hover:bg-gray-50 bg-white">
                &lt; Previous
            </button>
            <button class="px-3 py-1 text-sm border border-red-700 bg-red-700 text-white rounded">
                1
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                2
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                3
            </button>
            <span class="px-2 text-gray-400">...</span>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                5
            </button>
            <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50 bg-white">
                Next &gt;
            </button>
        </div>
    </div>

        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            <x-sidebar-right />
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const panels = ['created', 'liked'];
        panels.forEach(p => {
            document.getElementById('panel-' + p).classList.add('hidden');
            const btn = document.getElementById('tab-' + p);
            btn.classList.remove('text-red-600', 'border-b-2', 'border-red-600');
            btn.classList.add('text-gray-500', 'border-transparent');
        });

        document.getElementById('panel-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.add('text-red-600', 'border-b-2', 'border-red-600');
        activeBtn.classList.remove('text-gray-500', 'border-transparent');
    }

    function filterLikedPages(query) {
        const cards = document.querySelectorAll('#liked-grid > a');
        const q = query.toLowerCase();
        cards.forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc = card.querySelector('p')?.textContent.toLowerCase() || '';
            card.style.display = (title.includes(q) || desc.includes(q)) ? '' : 'none';
        });
    }
</script>
@endsection
