@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8" x-data="{ tab: 'mine' }">
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Header Section -->
            <div class="mb-6 flex items-center gap-3">
                <i data-lucide="users" class="w-6 h-6 text-gray-800"></i>
                <h1 class="text-2xl font-bold text-gray-900">Groups</h1>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6 flex gap-6">
                <button 
                    @click="tab = 'mine'" 
                    :class="tab === 'mine' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm font-bold transition-colors">
                    Mine
                </button>
                <button 
                    @click="tab = 'search'" 
                    :class="tab === 'search' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm font-bold transition-colors">
                    Search
                </button>
            </div>

            <!-- TAB: MINE -->
            <div x-show="tab === 'mine'">
                <a href="{{ route('groups.create') }}" class="bg-[#b91c1c] hover:bg-red-800 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 mb-6 transition-colors shadow-sm inline-flex">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Create a group
                </a>

                <div class="space-y-6">
                    <!-- Groups I Created -->
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Groups I Created</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-10 flex flex-col items-center justify-center text-gray-500">
                            <i data-lucide="folder-x" class="w-10 h-10 mb-3 text-gray-400"></i>
                            <p class="text-sm font-medium">You haven't created any groups yet.</p>
                        </div>
                    </div>

                    <!-- Groups I Joined -->
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Groups I Joined</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-10 flex flex-col items-center justify-center text-gray-500">
                            <i data-lucide="folder-x" class="w-10 h-10 mb-3 text-gray-400"></i>
                            <p class="text-sm font-medium">You haven't joined any groups yet.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: SEARCH -->
            <div x-show="tab === 'search'" style="display: none;">
                <div class="flex gap-2 mb-8">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" placeholder="Search groups..." class="w-full bg-white border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    </div>
                    <button class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2 rounded-md font-medium text-sm transition-colors">
                        Cari
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Dummy Search Result 1 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="bg-gray-100 p-2 rounded shrink-0">
                                <i data-lucide="users" class="w-6 h-6 text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 line-clamp-1">Hololine</h4>
                                <span class="text-xs text-gray-500 block mb-2">5 members</span>
                                <p class="text-xs text-gray-600 line-clamp-2">Private sector threat analysis and response coordination...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dummy Search Result 2 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="bg-gray-100 p-2 rounded shrink-0">
                                <i data-lucide="users" class="w-6 h-6 text-gray-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 line-clamp-1">Firewall trick tricks</h4>
                                <span class="text-xs text-gray-500 block mb-2">18 members</span>
                                <p class="text-xs text-gray-600 line-clamp-2">External facing honeypot monitoring and log analysis...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dummy Search Result 3 -->
                    <div class="bg-white border border-red-200 rounded-lg p-4 hover:shadow-md transition-shadow relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-1 bg-red-600 h-full"></div>
                        <div class="flex items-start gap-3">
                            <div class="bg-gray-900 p-2 rounded shrink-0">
                                <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 line-clamp-1">Yogyakarta X-code</h4>
                                <span class="text-xs text-red-600 font-medium block mb-2">27 members</span>
                                <p class="text-xs text-gray-600 line-clamp-2">Primary tactical defense and exploit research collective.</p>
                                <button class="mt-2 text-xs font-bold text-gray-400 hover:text-gray-700">JOIN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            @php
                $sidebarData = [
                    'reviews' => ['rating' => 4.9, 'count' => 532],
                    'networkLinks' => [
                        ['id' => 1, 'label' => 'LinkedIn', 'url' => '#'],
                        ['id' => 2, 'label' => 'phpBB Group', 'url' => '#'],
                        ['id' => 3, 'label' => 'Facebook', 'url' => '#']
                    ]
                ];
            @endphp
            <x-sidebar-right :data="$sidebarData" />
        </div>

    </div>
</div>
@endsection
