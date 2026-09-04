@extends('layouts.app')

@section('content')
<div class="pb-6 bg-[#fafafa]">
    <div class="w-full px-4 lg:px-20 mx-auto">

        <!-- Breadcrumb + Header (di luar container) -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-gray-700 transition">HOME</a>
                <span>›</span>
                <a href="{{ route('messages.index') }}" class="hover:text-gray-700 transition">MESSAGE</a>
                <span>›</span>
                <span class="text-gray-700 font-medium">BUAT SEBUAH PESAN</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">BUAT SEBUAH PESAN</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-12">

            <!-- KONTEN KIRI: Form Buat Pesan -->
            <div class="w-full lg:w-[80%] bg-[#f6f3f3] rounded-[24px] flex flex-col shadow-sm">

            <!-- Form -->
            <form action="{{ route('messages.store') }}" method="POST" class="px-8 py-6 flex-grow">
                @csrf

                @if($blocked)
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        Pengguna ini telah memblokir Anda. Anda tidak dapat mengirim pesan.
                    </div>
                @endif
                
                <!-- Tujuan -->
                <div class="mb-6">
                    <label for="recipient_id" class="block text-sm font-bold text-gray-900 mb-2">TUJUAN</label>
                    <select name="recipient_id" id="recipient_id" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#b71c1c] focus:border-[#b71c1c] outline-none shadow-sm @error('recipient_id') border-red-500 @enderror">
                        <option value="">Penerima...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('recipient_id', $toId ?? null) == $user->id ? 'selected' : '' }}>
                                {{ $user->fullname }} (@{{ $user->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('recipient_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subyek -->
                <div class="mb-6">
                    <label for="subject" class="block text-sm font-bold text-gray-900 mb-2">SUBYEK (OPSIONAL)</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                        placeholder="Title of your message..."
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#b71c1c] focus:border-[#b71c1c] outline-none shadow-sm @error('subject') border-red-500 @enderror">
                    @error('subject')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pesan -->
                <div class="mb-6">
                    <label for="message" class="block text-sm font-bold text-gray-900 mb-2">PESAN</label>
                    <textarea name="message" id="message" rows="12"
                        placeholder="Type your message here..."
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#b71c1c] focus:border-[#b71c1c] outline-none shadow-sm resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-auto">
                    <a href="{{ route('messages.index') }}" 
                        class="px-8 py-2.5 border border-gray-300 rounded-[10px] text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        BATAL
                    </a>
                    <button type="submit" 
                        class="bg-[#b71c1c] hover:bg-red-800 text-white px-8 py-2.5 rounded-[10px] text-sm font-bold transition flex items-center gap-2">
                        KIRIM
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
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

            <div class="bg-[#faecec] p-8 rounded-[24px] shadow-sm">
                <p class="text-[11px] font-black text-[#8b1818] uppercase tracking-wider mb-4">System Status</p>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-2.5 h-2.5 bg-[#10b981] rounded-full"></span>
                    <span class="text-[13px] font-bold text-gray-900">All Systems Operational</span>
                </div>
                <div class="flex justify-between text-[11px] font-bold text-gray-600 pt-4 border-t border-red-900/10">
                    <span>Uptime: 99.99%</span>
                    <span>Latency: 24ms</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
