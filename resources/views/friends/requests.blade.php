@extends('layouts.app')

@section('content')
<div class="pt-10 pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto flex flex-col lg:flex-row gap-6 lg:gap-12">

        <!-- KONTEN KIRI: Permintaan Pertemanan -->
        <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm">
            
            <!-- Header -->
            <div class="px-8 pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-end border-b border-gray-300/60">
                    <div class="flex space-x-8 w-full sm:w-[60%] mb-[-1px]">
                        <a href="{{ route('friends.index') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition">TEMAN</a>
                        <a href="{{ route('friends.requests') }}" class="pb-3 text-sm font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">PERMINTAAN PERTEMANAN</a>
                    </div>
                </div>
            </div>

            <!-- Section Ke Lainnya (Outgoing) -->
            <div class="px-8 py-6">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Ke lainnya</h2>
                </div>
                
                @if($outgoing->count() > 0)
                    <div class="bg-white rounded-[14px] p-4 shadow-sm">
                        @foreach($outgoing as $req)
                            <div class="flex items-center gap-4 py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <div class="w-10 h-10 rounded-full bg-[#f4dada] flex items-center justify-center text-[#b71c1c] font-bold text-sm">
                                    {{ substr($req->fullname, 0, 1) }}
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-gray-900">{{ $req->fullname }}</p>
                                    <p class="text-xs text-gray-500">Permintaan terkirim</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($req->created)->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-[14px] p-6 shadow-sm">
                        <p class="text-sm text-gray-500 text-center">Kamu memiliki {{ $outgoing->count() }} permintaan tertunda</p>
                    </div>
                @endif
            </div>

            <!-- Section Ke Anda (Incoming) -->
            <div class="px-8 pb-8">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Ke anda</h2>
                    @if($incoming->count() > 0)
                        <span class="bg-[#b71c1c] text-white text-xs font-bold px-3 py-1 rounded-full">{{ $incoming->count() }} PENDING</span>
                    @endif
                </div>

                @if($incoming->count() > 0)
                    <div class="space-y-4">
                        @foreach($incoming as $req)
                            <div class="bg-white rounded-[14px] p-4 shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-[#f4dada] flex items-center justify-center text-[#b71c1c] font-bold text-lg">
                                        {{ substr($req->fullname, 0, 1) }}
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-sm font-bold text-gray-900">@{{ $req->username }}</p>
                                        <p class="text-xs text-gray-500">INCOMING HANDSHAKE REQUEST</p>
                                        @if($req->msg)
                                            <p class="text-xs text-gray-600 mt-1 italic">"{{ $req->msg }}"</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('friends.reject', $req->uid) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 border border-gray-300 rounded-[10px] text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                                                TOLAK
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.accept', $req->uid) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-[#b71c1c] hover:bg-red-800 text-white rounded-[10px] text-xs font-bold transition">
                                                TERIMA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-[14px] p-6 shadow-sm">
                        <p class="text-sm text-gray-500 text-center">Tidak ada permintaan pertemanan masuk.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KONTEN KANAN: Sidebar -->
        <div class="w-full lg:w-[15%] space-y-6">
            <div class="bg-white p-8 rounded-[24px] shadow-sm flex flex-col items-center">
                <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                <div class="flex text-[#ffc107] mb-2 gap-1">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                <p class="text-xs text-blue-600 font-medium hover:underline cursor-pointer">532 Reviews</p>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm">
                <h3 class="text-[15px] font-bold text-gray-900 mb-4">Network Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">LinkedIn</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">phpBB Group</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-[12px] transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#f0f5fa] p-2.5 rounded-[10px] text-[#3b82f6]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                </div>
                                <span class="text-[13px] text-gray-700 font-semibold">Facebook</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
