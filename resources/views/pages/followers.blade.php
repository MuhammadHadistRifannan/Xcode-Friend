@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('pages.show', $page->id) }}" class="text-gray-400 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pengikut Halaman: {{ $page->name }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-700">Daftar Pengikut ({{ $page->followers->count() }})</h3>
        </div>
        
        <ul class="divide-y divide-gray-100">
            @forelse($page->followers as $follower)
                <li class="flex justify-between items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <img src="{{ $follower->avatar_url }}" alt="{{ $follower->username }}" class="w-12 h-12 rounded-full object-cover bg-gray-100 border border-gray-200">
                        <div>
                            <span class="text-gray-900 font-bold block">{{ $follower->fullname ?: $follower->username }}</span>
                            @if($follower->id === $page->uid)
                                <span class="inline-block mt-1 text-[10px] uppercase tracking-wider font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Owner</span>
                            @else
                                <span class="inline-block mt-1 text-xs text-gray-500">Pengikut</span>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-gray-500 text-sm">
                    Belum ada pengikut.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
