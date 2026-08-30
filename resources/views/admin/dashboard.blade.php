@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">HOME > ADMIN CP</div>
            <h1 class="text-3xl font-black text-gray-900">ADMIN CONTROL PANEL</h1>
            <p class="text-gray-500 text-sm mt-1">Manage the XCODE community, members, content, and platform settings with precision and authority.</p>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            <!-- Left Column: Main Content (col-span-8) -->
            <div class="col-span-12 lg:col-span-8">
                
                <!-- Bagian A: OVERVIEW -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        OVERVIEW
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Card 1: MEMBERS -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-[10px] tracking-wider">MEMBERS</h3>
                                <div class="text-red-500 bg-red-50 p-1.5 rounded-lg">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-2xl font-black text-gray-900 mb-1">8</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Total registered members</div>
                            </div>
                        </div>

                        <!-- Card 2: PENDING MEMBERS -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-[10px] tracking-wider">PENDING MEMBERS</h3>
                                <div class="text-red-500 bg-red-50 p-1.5 rounded-lg">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-2xl font-black text-gray-900 mb-1">0</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Awaiting approval</div>
                            </div>
                        </div>

                        <!-- Card 3: PHOTOS -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-[10px] tracking-wider">PHOTOS</h3>
                                <div class="text-red-500 bg-red-50 p-1.5 rounded-lg">
                                    <i data-lucide="image" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-2xl font-black text-gray-900 mb-1">0</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Uploaded photos</div>
                            </div>
                        </div>

                        <!-- Card 4: VIDEOS -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-[10px] tracking-wider">VIDEOS</h3>
                                <div class="text-red-500 bg-red-50 p-1.5 rounded-lg">
                                    <i data-lucide="video" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-2xl font-black text-gray-900 mb-1">0</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Uploaded videos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian B: MANAGEMENT TOOLS -->
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-4">MANAGEMENT TOOLS</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        
                        <!-- Tool 1: Site Config -->
                        <a href="{{ route('admin.site-config') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Site Config</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Global settings, metadata, and core platform...</p>
                        </a>

                        <!-- Tool 2: Modules -->
                        <a href="{{ route('admin.modules') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="box" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Modules</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Enable or disable core features and add-ons...</p>
                        </a>

                        <!-- Tool 3: Menu -->
                        <a href="{{ route('admin.menu') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="list" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Menu</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Customize navigation structure and links...</p>
                        </a>

                        <!-- Tool 4: Emailing -->
                        <a href="#" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Emailing</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Configure SMTP and template dispatch logic...</p>
                        </a>

                        <!-- Tool 5: Themes -->
                        <a href="#" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="palette" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Themes</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Manage visual identity and UI assets...</p>
                        </a>

                        <!-- Tool 6: Members -->
                        <a href="#" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Members</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Review, edit, or ban user accounts...</p>
                        </a>

                        <!-- Tool 7: Reports -->
                        <a href="{{ route('admin.reports') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-red-300 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm mb-1 group-hover:text-red-600 transition-colors">Reports</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">Review and resolve user reports...</p>
                        </a>

                    </div>
                </div>

            </div>

            <!-- Right Column: Sidebar Status (col-span-4) -->
            <div class="col-span-12 lg:col-span-4">
                <h2 class="text-lg font-bold text-gray-900 mb-4">PLATFORM STATUS</h2>
                
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    
                    <ul class="flex flex-col gap-5 mb-6">
                        <!-- Item 1 -->
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i>
                                Website Status
                            </div>
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">ONLINE</span>
                        </li>
                        
                        <!-- Item 2 -->
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <i data-lucide="database" class="w-4 h-4 text-gray-400"></i>
                                Database
                            </div>
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">CONNECTED</span>
                        </li>

                        <!-- Item 3 -->
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <i data-lucide="activity" class="w-4 h-4 text-gray-400"></i>
                                Community
                            </div>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">ACTIVE</span>
                        </li>

                        <!-- Item 4 -->
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <i data-lucide="shield-check" class="w-4 h-4 text-gray-400"></i>
                                Moderation
                            </div>
                            <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">NO PENDING</span>
                        </li>
                    </ul>

                    <!-- Refresh Button -->
                    <button class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        REFRESH STATUS
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
