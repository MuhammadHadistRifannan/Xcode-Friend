@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('groups.show', $group->id) }}" class="text-gray-400 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Anggota Grup: {{ $group->name }}</h1>
    </div>

    <div x-data="{ search: '' }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="font-bold text-gray-700">Daftar Anggota ({{ $group->members->count() }})</h3>
            <!-- Kolom Pencarian -->
            <div class="relative w-full sm:w-64">
                <input type="text" x-model="search" placeholder="Cari nama member..." class="w-full bg-white border border-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block pl-4 pr-10 py-2 transition-colors">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <ul class="divide-y divide-gray-100">
            @foreach($group->members as $member)
                <li x-show="'{{ strtolower(addslashes($member->fullname ?: $member->username)) }}'.includes(search.toLowerCase())" class="flex justify-between items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('img/avatar.png') }}" alt="{{ $member->fullname ?: $member->username }}" class="w-12 h-12 rounded-full object-cover bg-gray-100 border border-gray-200">
                        <div>
                            <span class="text-gray-900 font-bold block">{{ $member->fullname ?: $member->username }}</span>
                            @if($member->id === $group->uid)
                                <span class="inline-block mt-1 text-[10px] uppercase tracking-wider font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Creator</span>
                            @else
                                <span class="inline-block mt-1 text-xs text-gray-500">Member</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(Auth::id() === $group->uid && $member->id !== Auth::id())
                        <form action="{{ route('groups.members.kick', [$group->id, $member->id]) }}" method="POST">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Keluarkan member ini?')" class="text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors">
                                Kick / Hapus
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
