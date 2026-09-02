@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">My Pages</h1>
                <p class="text-sm text-gray-600">Kelola halaman yang kamu buat dan sukai.</p>
            </div>
            <a href="{{ route('pages.create') }}"
               class="bg-[#b91c1c] hover:bg-red-800 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 self-start md:self-auto transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create a Page
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6 flex gap-6">
        <button id="tab-created" onclick="switchTab('created')"
            class="pb-3 text-sm font-bold text-red-600 border-b-2 border-red-600 transition-colors">
            Pages I Created
            @if($createdPages->count() > 0)
                <span class="ml-1 bg-red-100 text-red-700 text-[10px] px-1.5 py-0.5 rounded-full">{{ $createdPages->count() }}</span>
            @endif
        </button>
        <button id="tab-liked" onclick="switchTab('liked')"
            class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-colors">
            Pages I Liked
            @if($likedPages->count() > 0)
                <span class="ml-1 bg-gray-100 text-gray-600 text-[10px] px-1.5 py-0.5 rounded-full">{{ $likedPages->count() }}</span>
            @endif
        </button>
    </div>

    <!-- ========================= -->
    <!-- TAB: Pages I Created      -->
    <!-- ========================= -->
    <div id="panel-created">
        @if($createdPages->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="bg-gray-100 p-5 rounded-full mb-4">
                    <i data-lucide="file-plus" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-700 mb-1">Kamu belum membuat halaman</h3>
                <p class="text-sm text-gray-500 mb-4">Mulai buat halaman pertamamu sekarang!</p>
                <a href="{{ route('pages.create') }}" class="bg-[#b91c1c] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-800">
                    Buat Page
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-bold w-20">Logo</th>
                                <th class="px-6 py-4 font-bold">Nama &amp; Deskripsi</th>
                                <th class="px-6 py-4 font-bold text-center">Likes</th>
                                <th class="px-6 py-4 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($createdPages as $page)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    @if($page->logo_url)
                                        <img src="{{ $page->logo_url }}" alt="{{ $page->name }}" class="w-12 h-12 rounded-md object-cover border border-gray-200">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center border border-gray-200">
                                            <i data-lucide="file-text" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('pages.show', $page->id) }}" class="font-bold text-gray-900 hover:text-red-700 transition-colors text-sm block truncate mb-1">
                                        {{ $page->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 truncate mb-1">{{ $page->description ?: 'Tidak ada deskripsi.' }}</p>
                                    <p class="text-[10px] text-gray-400">
                                        Diperbarui: {{ $page->updated ? date('d M Y', $page->updated) : '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-bold text-gray-800">{{ number_format($page->followers_count) }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-wide">likes</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('pages.edit', $page->id) }}"
                                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-md transition-colors"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus halaman {{ addslashes($page->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-md transition-colors"
                                                title="Hapus">
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
            </div>
        @endif
    </div>

    <!-- ========================= -->
    <!-- TAB: Pages I Liked        -->
    <!-- ========================= -->
    <div id="panel-liked" class="hidden">

        <!-- Search -->
        <div class="relative max-w-sm mb-6">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input
                type="text"
                placeholder="Cari halaman yang disukai..."
                oninput="filterLikedPages(this.value)"
                class="w-full bg-gray-100 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:bg-white transition-colors"
            >
        </div>

        @if($likedPages->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="bg-gray-100 p-5 rounded-full mb-4">
                    <i data-lucide="heart" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-700 mb-1">Kamu belum menyukai halaman apapun</h3>
                <p class="text-sm text-gray-500 mb-4">Jelajahi halaman yang ada dan mulai beri likes!</p>
                <a href="{{ route('pages.index') }}" class="bg-[#b91c1c] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-800">
                    Jelajahi Pages
                </a>
            </div>
        @else
            <!-- Grid — sama seperti pages/index -->
            <div id="liked-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @foreach($likedPages as $page)
                    <x-page-card :page="$page" />
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
    function switchTab(tab) {
        ['created', 'liked'].forEach(p => {
            document.getElementById('panel-' + p).classList.add('hidden');
            const btn = document.getElementById('tab-' + p);
            btn.classList.remove('text-red-600', 'border-red-600', 'border-b-2');
            btn.classList.add('text-gray-500', 'border-transparent');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const active = document.getElementById('tab-' + tab);
        active.classList.add('text-red-600', 'border-b-2', 'border-red-600');
        active.classList.remove('text-gray-500', 'border-transparent');
    }

    function filterLikedPages(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('#liked-grid > a').forEach(card => {
            const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('p')?.textContent.toLowerCase() || '';
            card.style.display = (title.includes(q) || desc.includes(q)) ? '' : 'none';
        });
    }
</script>
@endsection
