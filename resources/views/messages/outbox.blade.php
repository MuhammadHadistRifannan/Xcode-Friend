@extends('layouts.app')

@section('content')
<div class="pt-10 pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto flex flex-col lg:flex-row gap-6 lg:gap-12">

        <!-- KONTEN KIRI: Kotak Keluar -->
        <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm">
            
            <!-- Header dengan border abu-abu di dalam area padding -->
            <div class="px-8 pt-8 flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-center border-b border-gray-300/60">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('messages.index') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-800 border-b-2 border-transparent transition">Kotak Masuk</a>
                        <a href="{{ route('messages.outbox') }}" class="pb-3 text-sm font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">Kotak Keluar</a>
                    </div>
                    
                    <div class="flex items-center gap-4 py-3">
                        <a href="{{ route('messages.create') }}" class="flex items-center gap-1.5 text-sm font-bold text-white bg-[#b71c1c] hover:bg-red-800 px-4 py-2 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tulis Pesan
                        </a>
                        <form method="GET" action="{{ route('messages.outbox') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..." class="block w-full sm:w-[240px] pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#b71c1c] outline-none shadow-sm">
                            </div>
                            <button type="submit" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-500 hover:bg-gray-50 shadow-sm transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="px-8 mt-2 overflow-x-auto flex-grow flex flex-col">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="font-bold text-gray-900 border-b border-gray-300/60">
                            <th class="py-4 w-12"></th>
                            <th class="py-4 w-[25%]">Kepada</th>
                            <th class="py-4 w-auto">Pesan</th>
                            <th class="py-4 w-[15%] text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse($messages as $msg)
                            <tr onclick="window.location='{{ route('messages.show', $msg->id) }}'" class="group cursor-pointer transition-colors text-gray-600 hover:bg-[#ebe5e5] rounded-lg">
                                <td class="py-4 px-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" name="ids[]" value="{{ $msg->id }}" class="rounded border-gray-400 text-[#b71c1c] focus:ring-[#b71c1c]">
                                </td>
                                <td class="py-4 flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-200 text-gray-500 font-bold text-[10px] uppercase">
                                        {{ substr($msg->fullname ?? 'U', 0, 1) }}
                                    </span>
                                    <span class="truncate max-w-[200px]">{{ $msg->fullname ?? 'Unknown' }}</span>
                                </td>
                                <td class="py-4 truncate max-w-[400px]">
                                    <span class="font-bold text-gray-900">{{ $msg->subject ?: 'Tanpa Subjek' }}</span>
                                    <span class="ml-1">- {{ Str::limit($msg->message, 40) }}</span>
                                </td>
                                <td class="py-4 text-right text-xs font-medium">
                                    {{ \Carbon\Carbon::createFromTimestamp($msg->created)->format('M d, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-400 text-sm">
                                    Belum ada pesan yang kamu kirim.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer: Centang + Hapus -->
            <div class="m-8 mt-auto bg-white rounded-[14px] p-4 flex justify-between items-center shadow-sm">
                <label class="flex items-center gap-3 text-sm text-gray-700 font-medium cursor-pointer pl-2">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-400 text-[#b71c1c] focus:ring-[#b71c1c]">
                    <span>Centang/ Hilangkan semua centang</span>
                </label>
                <button type="button" onclick="confirmDelete()" class="bg-[#b71c1c] hover:bg-red-800 text-white px-8 py-2.5 rounded-[10px] text-sm font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus
                </button>
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

@push('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function confirmDelete() {
        const checked = document.querySelectorAll('input[name="ids[]"]:checked');
        if (checked.length === 0) {
            alert('Pilih pesan yang ingin dihapus.');
            return;
        }
        if (!confirm('Hapus ' + checked.length + ' pesan yang dipilih?')) {
            return;
        }
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("messages.bulkDelete") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const type = document.createElement('input');
        type.type = 'hidden';
        type.name = 'type';
        type.value = 'outbox';
        form.appendChild(type);
        
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
@endsection
