@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto">

        <!-- Breadcrumb + Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-700 transition">HOME</a>
                <span>›</span>
                <span class="text-gray-700 font-medium">MESSAGES</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">MESSAGES</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-12">

            <!-- KONTEN KIRI: Chat List -->
            <div class="w-full lg:w-[85%] bg-[#f6f3f3] rounded-[24px] flex flex-col min-h-[650px] shadow-sm border border-gray-200">
            
            <!-- Header + Search -->
            <div class="px-8 pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-end border-b border-gray-300/60">
                    <div class="flex space-x-8 w-full sm:w-[60%] mb-[-1px]">
                        <span class="pb-3 text-sm font-bold text-[#b71c1c] border-b-2 border-[#b71c1c]">PERCAKAPAN</span>
                    </div>
                </div>
            </div>

            <!-- Search + Bulk Actions -->
            <div class="px-8 py-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
                <form action="{{ route('messages.index') }}" method="GET" class="w-full sm:w-auto">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari percakapan..."
                            class="w-full sm:w-80 px-4 py-2 pl-10 bg-white rounded-[14px] text-sm focus:outline-none focus:ring-2 focus:ring-[#b71c1c]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>

                <div class="flex gap-2">
                    <button type="button" id="selectAllBtn" class="px-4 py-2 bg-white text-gray-600 text-xs font-medium rounded-[14px] hover:bg-gray-100 transition">
                        Pilih Semua
                    </button>
                    <button type="button" id="deleteSelectedBtn" class="px-4 py-2 bg-[#b71c1c] text-white text-xs font-bold rounded-[14px] hover:bg-red-800 transition hidden">
                        Hapus Terpilih
                    </button>
                </div>
            </div>

            <!-- Chat List -->
            <div class="px-8 pb-6 flex-grow">
                @if(count($conversations) > 0)
                    <div class="space-y-2">
                        @foreach($conversations as $conv)
                            @php
                                $friend = $conv['user'];
                                $lastMsg = $conv['lastMessage'];
                                $unread = $conv['unreadCount'];
                                $isUnread = $unread > 0;
                            @endphp
                            <div class="chat-item bg-white rounded-[14px] p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition cursor-pointer {{ $isUnread ? 'border-l-4 border-[#b71c1c]' : '' }}"
                                data-user-id="{{ $friend->id }}">
                                
                                <!-- Checkbox -->
                                <input type="checkbox" class="chat-checkbox w-4 h-4 text-[#b71c1c] rounded focus:ring-[#b71c1c]" value="{{ $friend->id }}">
                                
                                <!-- Avatar -->
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-full bg-[#f4dada] flex items-center justify-center text-[#b71c1c] font-bold text-lg">
                                        {{ substr($friend->fullname ?? 'U', 0, 1) }}
                                    </div>
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-grow min-w-0" onclick="window.location='{{ route('messages.conversation', $friend->id) }}'">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $friend->fullname }}</p>
                                        @if($lastMsg)
                                            <p class="text-xs text-gray-400 flex-shrink-0 ml-2">
                                                {{ \Carbon\Carbon::createFromTimestamp($lastMsg->created)->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        @if($lastMsg)
                                            <p class="text-xs text-gray-500 truncate">
                                                @if($lastMsg->from_id == Auth::id())
                                                    <span class="text-gray-400">Kamu: </span>
                                                @endif
                                                {{ $lastMsg->message }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 italic">Belum ada pesan</p>
                                        @endif
                                        
                                        @if($unread > 0)
                                            <span class="flex-shrink-0 ml-2 bg-[#b71c1c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full py-20">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm mb-4">Belum ada percakapan. Mulai chat dengan temanmu!</p>
                        <a href="{{ route('telusur.index') }}" class="px-6 py-2 bg-[#b71c1c] text-white text-sm font-bold rounded-[14px] hover:bg-red-800 transition">
                            Telusuri Teman
                        </a>
                    </div>
                @endif
            </div>

            </div>

            <!-- SIDEBAR KANAN -->
            <div class="w-full lg:w-[15%] space-y-6">
                <div class="bg-white p-8 rounded-[24px] shadow-sm flex flex-col items-center border border-gray-200">
                    <p class="text-[13px] font-bold text-gray-900 mb-2">Google Reviews</p>
                    <div class="flex text-[#ffc107] mb-2 gap-1">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">4.9</p>
                    <p class="text-xs text-blue-600 font-medium">532 Reviews</p>
                </div>

                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-200">
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
</div>

@push('scripts')
<script>
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const checkboxes = document.querySelectorAll('.chat-checkbox');
    let allSelected = false;

    selectAllBtn.addEventListener('click', () => {
        allSelected = !allSelected;
        checkboxes.forEach(cb => {
            cb.checked = allSelected;
        });
        updateDeleteButton();
        selectAllBtn.textContent = allSelected ? 'Batal Pilih' : 'Pilih Semua';
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedCount = document.querySelectorAll('.chat-checkbox:checked').length;
        if (checkedCount > 0) {
            deleteSelectedBtn.classList.remove('hidden');
            deleteSelectedBtn.textContent = `Hapus ${checkedCount} Percakapan`;
        } else {
            deleteSelectedBtn.classList.add('hidden');
        }
    }

    deleteSelectedBtn.addEventListener('click', () => {
        const selectedIds = Array.from(document.querySelectorAll('.chat-checkbox:checked'))
            .map(cb => parseInt(cb.value));
        
        if (selectedIds.length === 0) return;

        if (!confirm(`Yakin ingin menghapus ${selectedIds.length} percakapan?`)) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("messages.bulkDelete") }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
</script>
@endpush
@endsection
