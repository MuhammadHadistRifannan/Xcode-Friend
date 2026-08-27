@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">HOME > VIDEOS > ADD VIDEO</div>
        <h1 class="text-3xl font-bold text-gray-900">Add Video</h1>
        <p class="text-sm text-gray-600 mt-1">Share a video with your friends and the XCODE community.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        <!-- Left Column: Main Form (col-span-9) -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                <!-- Wrapper Alpine.js -->
                <div x-data="{ step: 1, playlistSelection: '' }">
                    <form action="#" method="POST">
                        
                        <!-- ================= STEP 1 ================= -->
                        <div x-show="step === 1" class="space-y-6">
                            
                            <!-- Title -->
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 flex items-center gap-1">
                                    <i data-lucide="type" class="w-3.5 h-3.5 text-gray-500"></i> Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" placeholder="Enter your video title" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500">
                            </div>

                            <!-- Video Description -->
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-gray-500"></i> Video Description
                                </label>
                                <textarea rows="4" placeholder="Tell people what this video is about..." class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-gray-50 focus:bg-white resize-none"></textarea>
                            </div>

                            <!-- Tags -->
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label class="block text-xs font-bold text-gray-900 flex items-center gap-1">
                                        <i data-lucide="hash" class="w-3.5 h-3.5 text-gray-500"></i> Tags
                                    </label>
                                    <span class="text-[10px] text-gray-400">Separate multiple tags with commas</span>
                                </div>
                                <input type="text" placeholder="e.g. security, tutorial, network" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500">
                            </div>

                            <!-- YouTube Video URL -->
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label class="block text-xs font-bold text-gray-900 flex items-center gap-1">
                                        <i data-lucide="youtube" class="w-3.5 h-3.5 text-gray-500"></i> YouTube Video URL <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[10px] text-gray-400">Paste the URL of the YouTube video.</span>
                                </div>
                                <div class="relative">
                                    <i data-lucide="link" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                                    <input type="url" placeholder="https://www.youtube.com/watch?v=..." class="w-full border border-gray-300 rounded-md pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500">
                                </div>
                            </div>

                            <!-- Privacy -->
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 flex items-center gap-1">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-gray-500"></i> Privacy
                                </label>
                                <div class="relative max-w-xs">
                                    <select class="w-full border border-gray-300 rounded-md pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 appearance-none bg-white">
                                        <option>Everyone</option>
                                        <option>Friends Only</option>
                                        <option>Only Me</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Action Footer Step 1 -->
                            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                                <button type="button" class="text-sm font-bold text-gray-600 hover:text-gray-900">
                                    Cancel
                                </button>
                                <button type="button" @click="step = 2" class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2.5 rounded-md font-bold text-sm shadow-sm transition-colors flex items-center gap-2">
                                    Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ================= STEP 2 ================= -->
                        <div x-show="step === 2" style="display: none;" class="space-y-8">
                            
                            <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">Add to Playlist/Album</h3>

                            <div class="space-y-6 max-w-lg">
                                <!-- Select Playlist -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">CHOOSE PLAYLIST</label>
                                    <select x-model="playlistSelection" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                                        <option value="">Select a playlist...</option>
                                        <option value="new" class="font-bold text-red-600">+ Create New Playlist</option>
                                        <option value="1">Cybersecurity Tutorials</option>
                                        <option value="2">Conference Talks</option>
                                        <option value="3">Vlogs</option>
                                    </select>
                                </div>

                                <!-- Create New Playlist Input -->
                                <div x-show="playlistSelection === 'new'" style="display: none;" class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">NEW PLAYLIST NAME</label>
                                    <input type="text" placeholder="Enter new playlist name" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                                </div>
                            </div>

                            <!-- Action Footer Step 2 -->
                            <div class="flex items-center justify-between pt-8 border-t border-gray-100">
                                <button type="button" @click="step = 1" class="text-sm font-bold text-gray-600 hover:text-gray-900 flex items-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                                </button>
                                <button type="submit" class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2.5 rounded-md font-bold text-sm flex items-center gap-2 shadow-sm transition-colors">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Publish Video
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Info (col-span-3) -->
        <div class="col-span-12 lg:col-span-3 space-y-6">
            
            <!-- Existing Sidebar Components -->
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

            <!-- Advertisement Space -->
            <div class="bg-gray-100 border border-gray-200 rounded-lg h-48 flex items-center justify-center">
                <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">ADVERTISEMENT SPACE</span>
            </div>
            
        </div>

    </div>
</div>
@endsection
