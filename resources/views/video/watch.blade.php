@extends('layouts.app')

@section('content')
<!-- Light Background Wrapper -->
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <div class="grid grid-cols-12 gap-6">
            
            <!-- Left Column: Video Player Area (col-span-12 lg:col-span-8) -->
            <div class="col-span-12 lg:col-span-8">
                
                <!-- Video Player Container -->
                <div class="w-full aspect-video bg-black rounded-xl border border-gray-300 flex flex-col items-center justify-center shadow-sm relative overflow-hidden">
                    <h2 class="text-white font-bold text-xl mb-4">Video terkunci</h2>
                    <!-- The image shows a blue button for "Login untuk menonton" -->
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Login untuk menonton
                    </button>
                </div>

                <!-- Video Info -->
                <div class="mt-6">
                    <!-- Video Title -->
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">
                        {{ $video['title'] }}
                    </h1>
                </div>

            </div>

            <!-- Right Column: Playlist Sidebar (col-span-12 lg:col-span-4) -->
            <div class="col-span-12 lg:col-span-4">
                
                <div class="bg-white rounded-xl p-4 border border-gray-200 max-h-[80vh] overflow-y-auto custom-scrollbar flex flex-col shadow-sm">
                    
                    <!-- Playlist Header -->
                    <div class="flex flex-col gap-3 mb-4 sticky top-0 bg-white pb-3 z-10 border-b border-gray-100">
                        
                        <!-- Album Title -->
                        <div class="font-bold text-gray-900 text-sm leading-tight">
                            {{ $video['title'] }}
                        </div>

                        <!-- Search Input -->
                        <div class="relative w-full">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" placeholder="Cari episode..." class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-red-500 bg-gray-50 focus:bg-white transition-colors">
                        </div>
                    </div>

                    <!-- Episode List from Same Album -->
                    <div class="flex flex-col gap-3">
                        @foreach($playlist as $ep)
                            @if($ep['is_active'])
                                <!-- Active Episode -->
                                <div class="bg-red-50/50 rounded-lg border-2 border-red-500 p-2 flex gap-3 cursor-pointer relative overflow-hidden group">
                                    <!-- Thumbnail -->
                                    <div class="relative w-28 shrink-0 aspect-video rounded overflow-hidden ring-2 ring-red-500 ring-offset-1">
                                        <img src="https://images.unsplash.com/photo-1542204165-65bf26472b9b?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover" alt="Thumb">
                                        <div class="absolute top-1 left-1 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                                            {{ $ep['ep'] }}
                                        </div>
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                            <div class="text-red-500 opacity-100"><i data-lucide="play" class="w-8 h-8 fill-current drop-shadow-md"></i></div>
                                        </div>
                                    </div>
                                    <!-- Info -->
                                    <div class="flex-1 flex flex-col justify-between py-0.5">
                                        <div>
                                            <h4 class="font-bold text-sm text-red-600 truncate w-[200px]">{{ $ep['title'] }}</h4>
                                            <p class="text-[10px] text-gray-600 line-clamp-2 mt-0.5 leading-tight">
                                                {{ $ep['desc'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-[10px] text-gray-500 font-medium">{{ $ep['date'] }}</span>
                                            <span class="text-[10px] font-bold text-red-600">Now Playing</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Inactive Episode -->
                                <div class="bg-white rounded-lg border border-gray-200 p-2 flex gap-3 cursor-pointer hover:border-gray-400 hover:shadow-sm transition-all group">
                                    <!-- Thumbnail -->
                                    <div class="relative w-28 shrink-0 aspect-video rounded overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1542204165-65bf26472b9b?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover" alt="Thumb">
                                        <div class="absolute top-1 left-1 bg-gray-900/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                                            {{ $ep['ep'] }}
                                        </div>
                                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="text-white opacity-90"><i data-lucide="play" class="w-8 h-8 fill-current drop-shadow-md"></i></div>
                                        </div>
                                    </div>
                                    <!-- Info -->
                                    <div class="flex-1 flex flex-col justify-between py-0.5">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900 truncate w-[200px] group-hover:text-red-600 transition-colors">{{ $ep['title'] }}</h4>
                                            <p class="text-[10px] text-gray-500 line-clamp-2 mt-0.5 leading-tight">
                                                {{ $ep['desc'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-[10px] text-gray-400 font-medium">{{ $ep['date'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* Custom scrollbar for the playlist (Light Theme) */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f3f4f6; /* gray-100 */
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1d5db; /* gray-300 */
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af; /* gray-400 */
}
</style>
@endsection
