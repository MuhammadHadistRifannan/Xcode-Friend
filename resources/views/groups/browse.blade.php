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
                <a href="{{ route('groups.mine') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Mine
                </a>
                <a href="{{ route('groups.browse') }}" class="border-red-600 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Search
                </a>
            </nav>
        </div>
    </div>

    <!-- Layout Container -->
    <div class="flex flex-col lg:flex-row gap-8 mt-8">
        
        <!-- MAIN CONTENT (Left) -->
        <div class="w-full lg:w-2/3">
            <!-- Search Box -->
            <div class="bg-white p-6 rounded border border-gray-200 shadow-sm mb-8">
                <form action="{{ route('groups.browse') }}" method="GET" class="flex gap-4">
                    <input type="text" name="q" placeholder="Cari Grup..." class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded font-medium shadow-sm">Cari</button>
                </form>
            </div>

            <!-- Group Results -->
            <div class="space-y-4">
                @if($groups->isEmpty())
                    <div class="text-center py-10 text-gray-500">
                        Tidak ada grup yang ditemukan.
                    </div>
                @else
                    @foreach($groups as $group)
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
