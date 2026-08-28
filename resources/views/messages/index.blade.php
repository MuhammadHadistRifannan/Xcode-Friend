@extends('layouts.app')

@section('content')
<div class="w-full bg-[#FAFAFA]">
    <div class="w-[98%] max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-[3.2fr_1fr] gap-4 items-start">

        <!-- KONTEN UTAMA: Kotak Masuk (Lebar 68%) -->
        <!-- Background diubah ke kemerahan sangat muda (#FDF6F6) dengan sudut lebih melengkung -->
        <div class="w-full h-[445px] bg-[#F6F1F2] shadow-sm rounded-lg overflow-hidden flex flex-col border border-[#eadfe0]">
            
            <!-- Header & Tabs -->
            <div class="px-4 pt-5 pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex space-x-7 border-b border-red-900/10 w-full sm:w-auto">
                    <a href="{{ route('messages.index') }}" class="pb-2 text-[11px] font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">Kotak Masuk</a>
                    <a href="{{ route('messages.outbox') }}" class="pb-2 text-[11px] font-medium text-gray-500 hover:text-gray-700 transition">Kotak Keluar</a>
                </div>
                
                <!-- Search Bar -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" placeholder="Search messages..." class="block w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded-md text-[9px] focus:ring-red-500 focus:border-red-500 bg-white shadow-sm">
                    </div>
                    <button aria-label="Filter messages" class="p-1.5 border border-gray-200 rounded-md bg-white text-gray-500 hover:bg-gray-50 transition shadow-sm">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </button>
                </div>
            </div>

            <!-- Tabel Kotak Masuk -->
            <div class="overflow-x-auto px-4 pb-3 h-[310px] flex-none">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[9px] text-gray-900 font-bold border-b border-red-900/5 bg-white/60">
                            <th class="p-2.5 w-8"><input type="checkbox" class="rounded border-gray-300 text-[#b71c1c] focus:ring-[#b71c1c] w-3 h-3"></th>
                            <th class="p-2.5">From</th>
                            <th class="p-2.5">Title</th>
                            <th class="p-2.5 text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/5 text-sm">
                        @forelse($messages as $msg)
                            <!-- Baris Unread menggunakan background pink solid (#F9EDED) -->
                            <tr onclick="window.location='{{ route('messages.show', $msg->from_id) }}'" class="hover:bg-red-50/80 transition cursor-pointer {{ $msg->hasread ? 'bg-transparent text-gray-600' : 'bg-[#F9EDED] font-semibold text-gray-900' }}">
                                <td class="p-2.5" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#b71c1c] focus:ring-[#b71c1c] w-3 h-3">
                                </td>
                                <td class="p-2.5 flex items-center space-x-3">
                                    <span class="inline-flex items-center justify-center h-4 w-4 rounded-full {{ $msg->hasread ? 'bg-gray-200 text-gray-500' : 'bg-red-200 text-red-700' }} font-bold text-[8px] uppercase">
                                        {{ substr($msg->sender->fullname ?? 'U', 0, 1) }}
                                    </span>
                                    <span>{{ $msg->sender->fullname ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-2.5 text-[10px]">
                                    <span>{{ $msg->subject ?: 'Tanpa Subjek' }}</span>
                                    @if(!$msg->hasread)
                                        <span class="inline-block w-2 h-2 bg-red-600 rounded-full ml-2"></span>
                                    @endif
                                </td>
                                <td class="p-2.5 text-right font-medium text-[9px]">
                                    {{ \Carbon\Carbon::createFromTimestamp($msg->created)->format('M d, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400 text-xs">
                                    Tidak ada pesan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Aksi Tabel -->
            <div class="p-3 border-t border-red-900/5 bg-white/75 flex justify-between items-center m-2 rounded-md">
                <label class="flex items-center space-x-1.5 text-[9px] text-gray-700 font-medium cursor-pointer pl-1">
                    <input type="checkbox" class="rounded border-gray-300 text-[#b71c1c] focus:ring-[#b71c1c] w-3 h-3">
                    <span>Centang/ Hilangkan semua centang</span>
                </label>
                <button class="bg-[#b71c1c] hover:bg-red-800 text-white px-4 py-2 rounded-md text-[10px] font-bold transition flex items-center gap-1.5 shadow-md">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus
                </button>
            </div>

        </div>

        <!-- SIDEBAR KANAN (Lebar 32%) -->
        <div class="w-full space-y-4">
            
            <!-- Widget 1: Google Reviews -->
            <div class="bg-white p-4 rounded-lg shadow-sm text-center border border-gray-100">
                <p class="text-[9px] font-bold text-gray-900 mb-1">Google Reviews</p>
                <div class="flex justify-center items-center text-yellow-400 mb-0.5 space-x-0.5">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-xl font-bold text-gray-900 mb-0.5">4.9</p>
                <p class="text-[9px] text-blue-600 font-medium hover:underline cursor-pointer">532 Reviews</p>
            </div>

            <!-- Widget 2: Network Links -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Network Links</h3>
                <ul class="space-y-2">
                    <li>
                            <a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-50 p-1.5 rounded-md text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </div>
                                <span class="text-[10px] text-gray-700 font-medium">LinkedIn</span>
                            </div>
                            <svg class="w-3 h-3 text-blue-600 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-50 p-1.5 rounded-md text-blue-600">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-[10px] text-gray-700 font-medium">phpBB Group</span>
                            </div>
                            <svg class="w-3 h-3 text-blue-600 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-50 p-1.5 rounded-md text-blue-600">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                </div>
                                <span class="text-[10px] text-gray-700 font-medium">Facebook</span>
                            </div>
                            <svg class="w-3 h-3 text-blue-600 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Widget 3: System Status -->
            <!-- Background merah bata sangat muda (#FBEAEA) -->
            <div class="bg-[#FBEAEA] p-4 rounded-lg shadow-sm relative overflow-hidden">
                <p class="text-[9px] font-black text-[#8c1c1c] uppercase tracking-wider mb-3">System Status</p>
                <div class="flex items-center space-x-2 mb-6">
                    <span class="w-2 h-2 bg-[#10b981] rounded-full"></span>
                    <span class="text-[10px] font-bold text-gray-900">All Systems Operational</span>
                </div>
                <div class="flex justify-between text-[8px] font-bold text-gray-500 pt-3 border-t border-red-900/10">
                    <span>Uptime: 99.99%</span>
                    <span>Latency: 24ms</span>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection