@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Pages</h1>
                        <p class="text-sm text-gray-600">Temukan dan ikuti halaman yang kamu sukai.</p>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="relative max-w-sm mt-4 border-t border-gray-200 pt-6">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 mt-3 text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input
                        type="text"
                        id="page-search"
                        placeholder="Cari pages..."
                        oninput="filterCards(this.value)"
                        class="w-full bg-gray-100 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:bg-white transition-colors"
                    >
                </div>
            </div>

            <!-- Flash Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Pages Grid -->
            @if($pages->count() > 0)
                <div id="pages-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    @foreach($pages as $page)
                        <x-page-card :page="$page" />
                    @endforeach
                </div>
                
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="file-text" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada Page</h3>
                    <p class="text-gray-500">Saat ini belum ada page yang tersedia.</p>
                </div>
            @endif
        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            <x-sidebar-right />
        </div>

    </div>
</div>

<script>
    function filterCards(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('#pages-grid > a').forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('p')?.textContent.toLowerCase() || '';
            card.style.display = (title.includes(q) || desc.includes(q)) ? '' : 'none';
        });
    }
</script>
@endsection
