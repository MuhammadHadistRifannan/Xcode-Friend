@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Title & Navigation -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 flex items-center gap-2 mb-4">
            <svg class="w-8 h-8 text-gray-700 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Groups
        </h1>

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('groups.mine') }}" class="border-red-600 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Mine
                </a>
                <a href="{{ route('groups.browse') }}" class="border-transparent text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 hover:border-gray-300 dark:hover:border-neutral-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Search
                </a>
            </nav>
        </div>
    </div>

    <!-- Layout Container: Left (Main) and Right (Sidebar) -->
    <div class="flex flex-col lg:flex-row gap-8 mt-8">
        
        <!-- MAIN CONTENT (Left) -->
        <div class="w-full lg:w-2/3">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-neutral-200">GROUPS I CREATED</h2>
                <a href="{{ route('groups.create') }}" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                    + Create a group
                </a>
            </div>

            <!-- Groups I Created -->
            <div class="mb-10">
                @if($createdGroups->isEmpty())
                    <div class="bg-gray-50 dark:bg-neutral-800/50 rounded-lg p-10 text-center border border-gray-200 dark:border-neutral-700 shadow-inner">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-neutral-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-100">No Groups Created</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">You haven't created any groups yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Create New Group
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($createdGroups as $group)
                            <a href="{{ route('groups.show', $group->id) }}" class="bg-white dark:bg-neutral-800 rounded-md border border-gray-200 dark:border-neutral-700 p-4 flex gap-4 shadow-sm hover:shadow-md hover:border-red-300 dark:hover:border-red-500/50 transition group block cursor-pointer">
                                <div class="flex-shrink-0">
                                    <img src="{{ $group->logo_url ?: asset('assets/img/default.png') }}"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                         class="w-16 h-16 rounded border border-gray-100 dark:border-neutral-700 bg-gray-100 dark:bg-neutral-700 object-cover" alt="{{ $group->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 group-hover:text-red-600 dark:group-hover:text-red-400 leading-tight block truncate">{{ $group->name }}</h3>
                                        <span class="inline-block bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 text-[10px] uppercase font-bold px-2 py-0.5 rounded">Active</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2 line-clamp-2">{{ $group->description ?: 'No description provided.' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-500 mt-2">{{ $group->members_count ?? $group->members()->count() }} members</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-gray-200 dark:border-neutral-700 mb-8">

            <!-- Groups I Joined -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-neutral-200">GROUPS I JOINED</h2>
            </div>
            
            <div>
                @if($joinedGroups->isEmpty())
                    <div class="bg-gray-50 dark:bg-neutral-800/50 rounded-lg p-10 text-center border border-gray-200 dark:border-neutral-700 shadow-inner">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-neutral-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-100">No Groups Joined</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">You haven't joined any groups yet. Explore communities to join.</p>
                        <div class="mt-6">
                            <a href="{{ route('groups.browse') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-neutral-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-gray-50 dark:hover:bg-neutral-700">
                                Browse Groups
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($joinedGroups as $group)
                            <a href="{{ route('groups.show', $group->id) }}" class="bg-white dark:bg-neutral-800 rounded-md border border-gray-200 dark:border-neutral-700 p-4 flex gap-4 shadow-sm hover:shadow-md hover:border-red-300 dark:hover:border-red-500/50 transition group block cursor-pointer">
                                <div class="flex-shrink-0">
                                    <img src="{{ $group->logo_url ?: asset('assets/img/default.png') }}"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';"
                                         class="w-16 h-16 rounded border border-gray-100 dark:border-neutral-700 bg-gray-100 dark:bg-neutral-700 object-cover" alt="{{ $group->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 group-hover:text-red-600 dark:group-hover:text-red-400 leading-tight block truncate">{{ $group->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2 line-clamp-2">{{ $group->description ?: 'No description provided.' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-500 mt-2">{{ $group->members_count ?? $group->members()->count() }} members</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="w-full lg:w-1/3 space-y-6">
            <x-sidebar-right />
        </div>

    </div>
</div>
@endsection
