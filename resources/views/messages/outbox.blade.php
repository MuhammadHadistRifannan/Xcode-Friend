@extends('layouts.app')

@section('content')
<div class="w-full bg-[#FAFAFA]">
    <div class="w-[98%] max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-[3.2fr_1fr] gap-4 items-start">

        <!-- KONTEN UTAMA: Kotak Keluar -->
        <div class="w-full h-[445px] bg-[#F6F1F2] shadow-sm rounded-lg overflow-hidden flex flex-col border border-[#eadfe0]">
            
            <!-- Header & Tabs -->
            <div class="px-4 pt-5 pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex space-x-7 border-b border-red-900/10 w-full sm:w-auto">
                    <a href="{{ route('messages.index') }}" class="pb-2 text-[11px] font-medium text-gray-500 hover:text-gray-700 transition">Kotak Masuk</a>
                    <a href="{{ route('messages.outbox') }}" class="pb-2 text-[11px] font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">Kotak Keluar</a>
                </div>
                <!-- Search Bar -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" placeholder="Search messages..." class="block w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded-md text-[9px] focus:ring-red-500 focus:border-red-500 bg-white shadow-sm">
                    </div>
                    <button aria-label="Filter messages" class="p-1.5 border border-gray-200 rounded-md bg-white text-gray-500 hover:bg-gray-50 transition shadow-sm">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </button>
                </div>
            </div>

            <!-- Tabel Kotak Keluar -->
            <div class="overflow-x-auto px-4 pb-3 h-[310px] flex-none">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[9px] text-gray-900 font-bold border-b border-red-900/5 bg-white/60">
                            <th class="p-2.5 w-8"><input type="checkbox" class="rounded border-gray-300 text-[#b71c1c] focus:ring-[#b71c1c] w-3 h-3"></th>
                            <th class="p-2.5">Kepada</th>
                            <th class="p-2.5">Pesan</th>
                            <th class="p-2.5 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($messages as $msg)
                            <!-- Route show mengarah ke to_id karena ini Outbox -->
                            <tr onclick="window.location='{{ route('messages.show', $msg->to_id) }}'" class="hover:bg-red-50/30 transition cursor-pointer bg-white">
                                <td class="p-2.5" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#b71c1c] focus:ring-[#b71c1c] w-3 h-3">
                                </td>
                                <td class="p-2.5 flex items-center space-x-3 text-[10px]">
                                    <span class="inline-flex items-center justify-center h-4 w-4 rounded-full bg-red-200 text-red-700 font-bold text-[8px] uppercase">
                                        {{ substr($msg->receiver->fullname ?? 'U', 0, 1) }}
                                    </span>
                                    <!-- Menggunakan fullname dari relasi receiver -->
                                    <span class="text-gray-900">{{ $msg->receiver->fullname ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-2.5 text-gray-700 truncate max-w-xs text-[10px]">
                                    <!-- Menampilkan Subject dari legacy -->
                                    <span class="font-bold text-gray-900 mr-1">{{ $msg->subject ?: 'Tanpa Subjek' }}</span>
                                    <span class="text-gray-500">- {{ Str::limit($msg->message, 40) }}</span>
                                </td>
                                <td class="p-2.5 text-right text-[9px] text-gray-500">
                                    <!-- Konversi Unix Timestamp (integer) ke format tanggal Carbon -->
                                    {{ \Carbon\Carbon::createFromTimestamp($msg->created)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400 text-xs">
                                    Belum ada pesan yang kamu kirim.
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
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16"></path></svg>
                    Hapus
                </button>
            </div>

        </div>

        <!-- SIDEBAR KANAN -->
        <div class="w-full space-y-4">
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

            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Network Links</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group"><span class="text-[10px] text-gray-700 font-medium">LinkedIn</span><span class="text-xs text-blue-600">→</span></a></li>
                    <li><a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group"><span class="text-[10px] text-gray-700 font-medium">phpBB Group</span><span class="text-xs text-blue-600">→</span></a></li>
                    <li><a href="#" class="flex items-center justify-between p-1 rounded-md hover:bg-gray-50 transition group"><span class="text-[10px] text-gray-700 font-medium">Facebook</span><span class="text-xs text-blue-600">→</span></a></li>
                </ul>
            </div>

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