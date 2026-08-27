@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    
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
        <button class="pb-3 text-sm font-bold text-red-600 border-b-2 border-red-600">
            Pages I created
        </button>
        <button class="pb-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
            Pages I liked
        </button>
    </div>

    <!-- Table Header -->
    <div class="grid grid-cols-12 gap-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 px-4">
        <div class="col-span-1">LOGO</div>
        <div class="col-span-9">DESIGNATION & OBJECTIVE</div>
        <div class="col-span-2 text-right">LAST UPDATED</div>
    </div>
    
    <!-- Divider -->
    <div class="border-t border-gray-200 mb-4"></div>

    <!-- List Items -->
    <div class="space-y-4">
        <!-- Item 1 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start gap-6 hover:shadow-sm transition-shadow">
            <div class="bg-gray-100 p-3 rounded-md shrink-0">
                <i data-lucide="file-text" class="w-6 h-6 text-gray-500"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold text-gray-900">Alpha Protocol</h3>
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">ACTIVE</span>
                    <span class="text-xs text-gray-500 flex items-center gap-1"><i data-lucide="heart" class="w-3 h-3"></i> 1.2K Likes</span>
                </div>
                <p class="text-sm text-gray-500">Primary defense layer configuration and active monitoring dashboard.</p>
            </div>
            <div class="text-xs text-gray-500 shrink-0">2 hours ago</div>
        </div>

        <!-- Item 2 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start gap-6 hover:shadow-sm transition-shadow">
            <div class="bg-gray-100 p-3 rounded-md shrink-0">
                <i data-lucide="file-text" class="w-6 h-6 text-gray-500"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold text-gray-900">Bravo Node</h3>
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">OFFLINE</span>
                    <span class="text-xs text-gray-500 flex items-center gap-1"><i data-lucide="heart" class="w-3 h-3"></i> 842 Likes</span>
                </div>
                <p class="text-sm text-gray-500">Secondary authentication and identity management sector.</p>
            </div>
            <div class="text-xs text-gray-500 shrink-0">5 hours ago</div>
        </div>

        <!-- Item 3 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-start gap-6 hover:shadow-sm transition-shadow">
            <div class="bg-gray-100 p-3 rounded-md shrink-0">
                <i data-lucide="file-text" class="w-6 h-6 text-gray-500"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold text-gray-900">Charlie Ops</h3>
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">DRAFT</span>
                    <span class="text-xs text-gray-500 flex items-center gap-1"><i data-lucide="heart" class="w-3 h-3"></i> 45 Likes</span>
                </div>
                <p class="text-sm text-gray-500">Experimental deployment scripts and command-line tools.</p>
            </div>
            <div class="text-xs text-gray-500 shrink-0">1 day ago</div>
        </div>
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
@endsection
