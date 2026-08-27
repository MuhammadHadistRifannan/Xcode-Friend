@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="text-xs text-gray-500 font-medium mb-1">Home / Photos</div>
                <h1 class="text-3xl font-bold text-gray-900">FOTO</h1>
            </div>

            <!-- Tab Navigation & Action Button -->
            <div class="border-b border-gray-200 mb-6 flex items-center justify-between">
                <div class="flex gap-6 overflow-x-auto no-scrollbar">
                    <button class="pb-3 text-sm font-bold text-red-600 border-b-2 border-red-600 uppercase tracking-wider whitespace-nowrap">
                        MY PHOTOS
                    </button>
                    <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider transition-colors whitespace-nowrap">
                        COMMUNITY
                    </button>
                    <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider transition-colors whitespace-nowrap">
                        FOLLOWING
                    </button>
                    <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider transition-colors whitespace-nowrap">
                        FRIENDS
                    </button>
                    <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider transition-colors whitespace-nowrap">
                        VIDEOS
                    </button>
                </div>
                
                <a href="{{ route('photos.upload') }}" class="bg-[#b91c1c] hover:bg-red-800 text-white px-4 py-1.5 rounded-md font-bold text-xs flex items-center gap-2 transition-colors shrink-0 -translate-y-1">
                    <i data-lucide="plus" class="w-3 h-3"></i> UNGGAH
                </a>
            </div>

            <!-- Photos Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                
                <!-- Photo Card 1 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1614064641913-a520faff3ddf?auto=format&fit=crop&q=80&w=400" alt="Cyber" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">offsec</h4>
                    <p class="text-xs text-gray-500">by Alex</p>
                </div>

                <!-- Photo Card 2 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&q=80&w=400" alt="Code" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">album-0</h4>
                    <p class="text-xs text-gray-500">by Sarah C.</p>
                </div>

                <!-- Photo Card 3 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&q=80&w=400" alt="Server" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">Apa aja</h4>
                    <p class="text-xs text-gray-500">by yogyakarta</p>
                </div>

                <!-- Photo Card 4 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=400" alt="Hacker" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">foto</h4>
                    <p class="text-xs text-gray-500">by root</p>
                </div>

                <!-- Photo Card 5 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&q=80&w=400" alt="Setup" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">my-profile</h4>
                    <p class="text-xs text-gray-500">by zainiud</p>
                </div>

                <!-- Photo Card 6 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&q=80&w=400" alt="Tech" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">my-profile</h4>
                    <p class="text-xs text-gray-500">by cariilmu</p>
                </div>

                <!-- Photo Card 7 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=400" alt="Office" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">album-1</h4>
                    <p class="text-xs text-gray-500">by infosec</p>
                </div>

                <!-- Photo Card 8 -->
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square mb-2 hover:opacity-90 cursor-pointer transition-opacity">
                        <img src="https://images.unsplash.com/photo-1535223289827-42f1e9919769?auto=format&fit=crop&q=80&w=400" alt="Motherboard" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 leading-tight">album-5</h4>
                    <p class="text-xs text-gray-500">by cyberninja</p>
                </div>

            </div>

            <!-- Pagination Section -->
            <div class="flex justify-center items-center gap-2 mt-12 mb-8">
                <button class="px-2 py-1 text-gray-400 hover:text-gray-600">
                    &lt;
                </button>
                <button class="w-8 h-8 flex items-center justify-center text-sm font-bold bg-[#b91c1c] text-white rounded shadow-sm">
                    1
                </button>
                <button class="w-8 h-8 flex items-center justify-center text-sm font-medium text-gray-600 hover:bg-gray-100 rounded">
                    2
                </button>
                <button class="w-8 h-8 flex items-center justify-center text-sm font-medium text-gray-600 hover:bg-gray-100 rounded">
                    3
                </button>
                <span class="px-1 text-gray-400">...</span>
                <button class="w-8 h-8 flex items-center justify-center text-sm font-medium text-gray-600 hover:bg-gray-100 rounded">
                    12
                </button>
                <button class="px-2 py-1 text-gray-600 hover:text-gray-900 font-bold">
                    &gt;
                </button>
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
