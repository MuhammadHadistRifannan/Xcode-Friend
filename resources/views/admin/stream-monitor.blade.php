@extends('layouts.admin')
@section('title', 'Stream Monitor - Admin Panel XCODE')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a> &gt;
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">ADMIN CP</a> &gt; STREAM MONITOR
                </div>
                <h1 class="text-3xl font-black text-gray-900">STREAM MONITOR</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau seluruh aktivitas postingan, media, dan teks di seluruh jaringan.</p>
            </div>

            <div class="w-full sm:w-72 mt-4 sm:mt-0">
                <form action="{{ route('admin.stream-monitor') }}" method="GET" class="relative">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari konten atau username..." class="w-full bg-white border border-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block pl-10 pr-4 py-2.5 shadow-sm transition-colors">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.stream-monitor', request()->except('search')) }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Type Filter --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.stream-monitor', request()->except('type', 'page')) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ !request('type') ? 'bg-gray-800 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                All Streams ({{ number_format($typeStats['all']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 1])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ request('type') == '1' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">
                <i data-lucide="file-text" class="w-4 h-4 inline-block mr-1"></i> Teks ({{ number_format($typeStats['1']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 2])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ request('type') == '2' ? 'bg-pink-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-pink-50 hover:text-pink-600 hover:border-pink-200' }}">
                <i data-lucide="image" class="w-4 h-4 inline-block mr-1"></i> Foto ({{ number_format($typeStats['2']) }})
            </a>
            <a href="{{ route('admin.stream-monitor', array_merge(request()->except('page'), ['type' => 3])) }}" 
               class="px-4 py-2 rounded-lg text-sm font-bold transition-colors {{ request('type') == '3' ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200' }}">
                <i data-lucide="video" class="w-4 h-4 inline-block mr-1"></i> Video ({{ number_format($typeStats['3']) }})
            </a>
        </div>

        {{-- Stream Feed --}}
        <div class="space-y-4">
            @forelse($streams as $stream)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-start gap-3">
                            <img src="{{ $stream->avatar ? asset('storage/'.$stream->avatar) : asset('img/default-avatar.png') }}" alt="{{ $stream->username }}" class="w-10 h-10 rounded-full border border-gray-200 object-cover bg-gray-100">
                            <div>
                                <div class="font-bold text-gray-900 text-sm">
                                    <a href="{{ route('profile.show', $stream->username) }}" target="_blank" class="hover:underline">{{ $stream->fullname ?: $stream->username }}</a>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::createFromTimestamp($stream->created)->diffForHumans() }} &bull; 
                                    ID: {{ $stream->id }} &bull; 
                                    @if($stream->type == 1)
                                        <span class="text-blue-600 font-semibold"><i data-lucide="file-text" class="w-3 h-3 inline"></i> Teks</span>
                                    @elseif($stream->type == 2)
                                        <span class="text-pink-600 font-semibold"><i data-lucide="image" class="w-3 h-3 inline"></i> Foto</span>
                                    @elseif($stream->type == 3)
                                        <span class="text-orange-600 font-semibold"><i data-lucide="video" class="w-3 h-3 inline"></i> Video</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Delete Button --}}
                        <form action="{{ route('admin.stream-monitor.destroy', $stream->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus Postingan">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Content --}}
                    @if($stream->message)
                        <p class="text-gray-800 text-sm mb-3 whitespace-pre-wrap">{{ $stream->message }}</p>
                    @endif

                    @if($stream->attachment)
                        <div class="mt-3">
                            @if($stream->type == 3 && str_starts_with($stream->attachment, 'youtube:'))
                                @php $ytId = str_replace('youtube:', '', $stream->attachment); @endphp
                                <iframe class="w-full max-w-2xl aspect-video rounded-xl" src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0"></iframe>
                            @elseif($stream->type == 2 || $stream->type == 3)
                                <img src="{{ asset('storage/'.$stream->attachment) }}" class="rounded-xl max-h-80 object-contain bg-gray-50 border border-gray-100">
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i data-lucide="activity" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-sm text-gray-500">Tidak ada postingan yang sesuai dengan filter Anda.</p>
                </div>
            @endforelse
        </div>

        @if($streams->hasPages())
            <div class="mt-6">
                {{ $streams->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
