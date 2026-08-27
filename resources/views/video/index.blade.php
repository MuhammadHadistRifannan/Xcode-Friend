@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Main Content (Left/Center) -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Videoku</h1>
                        <p class="text-sm text-gray-600">Manage and monitor your active deployments and assets.</p>
                    </div>
                    <a href="{{ route('videos.create') }}" class="bg-[#b91c1c] hover:bg-red-800 text-white px-4 py-2 rounded-md font-medium text-sm flex items-center gap-2 self-start md:self-auto transition-colors shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add a video
                    </a>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6 flex gap-6">
                <button class="pb-3 text-sm font-bold text-red-600 border-b-2 border-red-600">
                    Videoku
                </button>
                <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Mengikuti
                </button>
                <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Teman
                </button>
            </div>

            <!-- Video Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Video Card 1 -->
                <a href="{{ route('videos.watch', ['id' => 1]) }}" class="block bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative aspect-video bg-gray-200 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&q=80&w=800" alt="Video thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                            12:45
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">Advanced Penetration Testing Techniques: Beyond the Basics</h3>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Sarah+Connor&background=000&color=fff" alt="Avatar" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-medium text-gray-700">Sarah C.</span>
                            </div>
                            <div class="text-[10px] text-gray-500 text-right">
                                <div>1.2K views</div>
                                <div>2 days ago</div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Video Card 2 -->
                <a href="{{ route('videos.watch', ['id' => 2]) }}" class="block bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative aspect-video bg-gray-200 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800" alt="Video thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                            08:20
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">Cloud Security Architecture Fundamentals</h3>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Alex+Rivera&background=000&color=fff" alt="Avatar" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-medium text-gray-700">Alex Rivera</span>
                            </div>
                            <div class="text-[10px] text-gray-500 text-right">
                                <div>856 views</div>
                                <div>1 week ago</div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Video Card 3 -->
                <a href="{{ route('videos.watch', ['id' => 3]) }}" class="block bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative aspect-video bg-gray-200 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1614064641913-a520faff3ddf?auto=format&fit=crop&q=80&w=800" alt="Video thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                            45:10
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">Analyzing Malicious Traffic Patterns</h3>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=SecOps+Team&background=000&color=fff" alt="Avatar" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-medium text-gray-700">SecOps Team</span>
                            </div>
                            <div class="text-[10px] text-gray-500 text-right">
                                <div>3.4K views</div>
                                <div>1 month ago</div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Video Card 4 -->
                <a href="{{ route('videos.watch', ['id' => 4]) }}" class="block bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="relative aspect-video bg-gray-200 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?auto=format&fit=crop&q=80&w=800" alt="Video thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                            22:15
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">Reverse Engineering Masterclass: Part 1</h3>
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=David+K&background=000&color=fff" alt="Avatar" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-medium text-gray-700">David K.</span>
                            </div>
                            <div class="text-[10px] text-gray-500 text-right">
                                <div>5.1K views</div>
                                <div>2 months ago</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            @php
                // Mock data for right sidebar
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
