@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    
    <!-- Header Section -->
    <div class="mb-8 relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Pages</h1>
                <p class="text-sm text-gray-600">Manage and explore available system nodes.</p>
            </div>
        </div>
        
        <div class="relative max-w-sm mt-4 border-t border-gray-200 pt-6">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 mt-3 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input 
                type="text" 
                placeholder="Search pages..." 
                class="w-full bg-gray-100 border border-gray-200 rounded-md pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:bg-white transition-colors"
            >
        </div>
    </div>

    <!-- Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach($pages as $page)
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
@endsection
