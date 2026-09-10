@extends('layouts.app')

@section('title', 'Daftar Blokir')

@section('content')
<div class="pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto">

        <!-- Breadcrumb + Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-700 transition">HOME</a>
                <span>›</span>
                <a href="{{ route('friends.index') }}" class="hover:text-gray-700 transition">FRIENDS</a>
                <span>›</span>
                <span class="text-gray-700 font-medium">BLACKLIST</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">BLACKLIST</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-12">

            <!-- KONTEN KIRI: Daftar Block -->
            <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm">

                <!-- Header -->
                <div class="px-8 pt-8">
                    <div class="flex flex-col sm:flex-row justify-between items-end border-b border-gray-300/60">
                        <div class="flex space-x-8 w-full sm:w-[60%] mb-[-1px]">
                            <a href="{{ route('friends.index') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition">TEMAN</a>
                            <a href="{{ route('friends.requests') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition">PERMINTAAN PERTEMANAN</a>
                            <a href="{{ route('friends.blacklist') }}" class="pb-3 text-sm font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">BLACKLIST</a>
                        </div>
                    </div>
                </div>

                <!-- Daftar Block -->
                <div class="px-8 py-6 flex-grow">
                    @if($blockedUsers->count() > 0)
                        <div class="space-y-4">
                            @foreach($blockedUsers as $blocked)
                                <div class="bg-white rounded-[14px] p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                                    <div class="w-12 h-12 rounded-full bg-[#f4dada] flex items-center justify-center text-[#b71c1c] font-bold text-lg">
                                        {{ substr($blocked->fullname, 0, 1) }}
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $blocked->fullname }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ '@' . $blocked->username }}</p>
                                    </div>
                                    <form action="{{ route('friends.unblock', $blocked->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membuka blokir user ini?')">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition" title="Buka Blokir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20">
                            <p class="text-gray-400 text-sm">Anda belum memblokir siapapun.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- KONTEN KANAN: Sidebar -->
            <div class="w-full lg:w-[15%] space-y-6">
                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-200 flex flex-col items-center">
                    <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                    <div class="flex text-[#ffc107] mb-2 gap-1">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                    <p class="text-xs text-blue-600 font-medium">532 Reviews</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection