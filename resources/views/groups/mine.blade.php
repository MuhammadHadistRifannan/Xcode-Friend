@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Title & Navigation -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2 mb-4">
            <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Groups
        </h1>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('groups.mine') }}" class="border-red-600 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Mine
                </a>
                <a href="{{ route('groups.browse') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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
                <h2 class="text-xl font-bold text-gray-800">GROUPS I CREATED</h2>
                <a href="{{ route('groups.create') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    + Create a group
                </a>
            </div>

            <!-- Groups I Created -->
            <div class="mb-10">
                @if($createdGroups->isEmpty())
                    <div class="bg-gray-50 rounded-lg p-10 text-center border border-gray-200 shadow-inner">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">No Groups Created</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't created any groups yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Create New Group
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($createdGroups as $group)
                            <a href="{{ route('groups.show', $group->id) }}" class="bg-white rounded-md border border-gray-200 p-4 flex gap-4 shadow-sm hover:shadow-md hover:border-red-300 transition group block cursor-pointer">
                                <div class="flex-shrink-0">
                                    <img src="{{ $group->logo ? asset('storage/'.$group->logo) : asset('img/default-group.png') }}" class="w-16 h-16 rounded border bg-gray-100 object-cover" alt="Group Logo">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-lg font-bold text-gray-900 group-hover:text-red-600 leading-tight block">{{ $group->name }}</span>
                                        <span class="inline-block bg-red-100 text-red-800 text-[10px] uppercase font-bold px-2 py-0.5 rounded">Active</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $group->description ?: 'No description provided.' }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $group->members_count ?? $group->members()->count() }} members</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-gray-200 mb-8">

            <!-- Groups I Joined -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">GROUPS I JOINED</h2>
            </div>
            
            <div>
                @if($joinedGroups->isEmpty())
                    <div class="bg-gray-50 rounded-lg p-10 text-center border border-gray-200 shadow-inner">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">No Groups Joined</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't joined any groups yet. Explore communities to join.</p>
                        <div class="mt-6">
                            <a href="{{ route('groups.browse') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Browse Groups
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($joinedGroups as $group)
                            <a href="{{ route('groups.show', $group->id) }}" class="bg-white rounded-md border border-gray-200 p-4 flex gap-4 shadow-sm hover:shadow-md hover:border-red-300 transition group block cursor-pointer">
                                <div class="flex-shrink-0">
                                    <img src="{{ $group->logo ? asset('storage/'.$group->logo) : asset('img/default-group.png') }}" class="w-16 h-16 rounded border bg-gray-100 object-cover" alt="Group Logo">
                                </div>
                                <div class="flex-1">
                                    <span class="text-lg font-bold text-gray-900 group-hover:text-red-600 leading-tight block">{{ $group->name }}</span>
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $group->description ?: 'No description provided.' }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $group->members_count ?? $group->members()->count() }} members</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="w-full lg:w-1/3 space-y-6">
            <!-- Google Reviews Mock -->
            <div class="bg-white border border-gray-200 p-4 rounded shadow-sm text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="w-8 h-8" alt="Google">
                </div>
                <h3 class="font-bold text-gray-800 text-sm">Rating</h3>
                <div class="flex justify-center text-yellow-400 my-1">
                    ★★★★★
                </div>
                <p class="text-xs text-gray-500">5.0 Berdasarkan 51 ulasan</p>
            </div>

            <!-- Network Links -->
            <div class="bg-white border border-gray-200 p-0 rounded shadow-sm overflow-hidden">
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                    <div class="w-8 h-8 bg-blue-700 text-white rounded flex items-center justify-center mr-3">
                        in
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">LINKEDIN</div>
                        <div class="text-xs text-gray-500">Network</div>
                    </div>
                </a>
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded flex items-center justify-center mr-3 text-xs font-bold">
                        php
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">PHPBB</div>
                        <div class="text-xs text-gray-500">Forum</div>
                    </div>
                </a>
                <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 transition">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded flex items-center justify-center mr-3 text-xl font-bold">
                        f
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">FACEBOOK</div>
                        <div class="text-xs text-gray-500">Grup Facebook</div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
