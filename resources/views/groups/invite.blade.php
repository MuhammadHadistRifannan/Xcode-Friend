@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('groups.show', $group->id) }}" class="text-gray-400 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Undang Pengguna ke: {{ $group->name }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 text-sm font-medium border border-green-100">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 text-sm font-medium border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-700">Pilih Pengguna untuk Diundang ({{ $users->count() }} pengguna tersedia)</h3>
        </div>
        
        <form action="{{ route('groups.sendInvite', $group->id) }}" method="POST">
            @csrf
            
            @if($users->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <p>Semua pengguna sudah bergabung atau diundang ke grup ini.</p>
                </div>
            @else
                <div class="max-h-[500px] overflow-y-auto p-4">
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($users as $user)
                            <li class="flex items-center gap-4 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                                <input type="checkbox" name="uids[]" value="{{ $user->id }}" id="user_{{ $user->id }}" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <label for="user_{{ $user->id }}" class="flex items-center gap-3 cursor-pointer w-full">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username ?? $user->name) . '&background=random&color=fff' }}" alt="{{ $user->username ?? $user->name }}" class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-200">
                                    <span class="text-gray-900 font-bold block">{{ $user->fullname ?? $user->username }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition-colors text-sm">
                        Kirim Undangan via Pesan
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
