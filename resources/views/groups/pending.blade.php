@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('groups.show', $group->id) }}" class="text-gray-400 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Menunggu Persetujuan: {{ $group->name }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 text-sm font-medium border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-700">Daftar Permintaan Bergabung ({{ $group->pendingMembers->count() }})</h3>
        </div>
        
        @if($group->pendingMembers->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p>Belum ada permintaan bergabung saat ini.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($group->pendingMembers as $member)
                    <li class="flex justify-between items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($member->username ?? $member->name) . '&background=random&color=fff' }}" alt="{{ $member->username ?? $member->name }}" class="w-12 h-12 rounded-full object-cover bg-gray-100 border border-gray-200">
                            <div>
                                <span class="text-gray-900 font-bold block">{{ $member->username ?? $member->name }}</span>
                                <span class="inline-block mt-1 text-xs text-gray-500">Menunggu persetujuan</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <form action="{{ route('groups.approve', $group->id) }}" method="POST">
                                @csrf 
                                <input type="hidden" name="uid" value="{{ $member->id }}">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors shadow-sm">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('groups.approve', $group->id) }}" method="POST">
                                @csrf 
                                <input type="hidden" name="uid" value="{{ $member->id }}">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" onclick="return confirm('Tolak permintaan bergabung ini?')" class="text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors border border-red-100">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
