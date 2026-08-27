<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pesan - XCODE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <!-- NAVBAR SEMENTARA -->
    <nav class="bg-black p-4 shadow-md text-center text-red-500 font-bold tracking-widest border-b border-red-900/50">
        [ NAVBAR GISKA NANTI DI SINI ]
    </nav>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">

            <!-- KONTEN UTAMA: Baca Pesan -->
            <div class="w-full md:w-2/3 bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
                
                <!-- Header Tabs -->
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex space-x-6">
                        <a href="{{ route('messages.index') }}" class="pb-2 text-sm font-bold text-gray-900 border-b-2 border-red-600">Kotak Masuk</a>
                        <a href="{{ route('messages.outbox') }}" class="pb-2 text-sm font-medium text-gray-500 hover:text-gray-700">Kotak Keluar</a>
                    </div>
                </div>

                <!-- Detail Isi Pesan -->
                <div class="p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">Helo</h1>
                    
                    <div class="text-xs text-gray-500 mb-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-gray-700">From:</span> 
                            <span class="text-red-600 font-medium">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span>
                                @if(isset($messageItem) && $messageItem->created_at)
                                    {{ $messageItem->created_at->format('M jS Y, H:i') }}
                                @else
                                    Aug 18th 2026, 11:31 pm
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Teks Pesan -->
                    <div class="text-sm text-gray-700 leading-relaxed mb-8 space-y-4">
                        @if(isset($messages) && $messages->count() > 0)
                            @foreach($messages as $msg)
                                <div class="p-3 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-red-50 text-right' : 'bg-gray-50 text-left' }}">
                                    <p class="text-xs text-gray-400 mb-1">{{ $msg->sender_id === auth()->id() ? 'Anda' : $user->name }}</p>
                                    <p class="text-gray-800">{{ $msg->message }}</p>
                                </div>
                            @endforeach
                        @else
                            <p>Morbi elementum risus in ligula bibendum pretium. Praesent non efficitur odio. Maecenas et lacus vitae nisl convallis finibus.</p>
                        @endif
                    </div>

                    <!-- Tombol Aksi Bawah (Hapus & Balas) -->
                    <div class="flex justify-end items-center space-x-3 pt-4 border-t border-gray-100">
                        <button class="flex items-center space-x-1 px-4 py-2 border border-gray-300 rounded-md text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>HAPUS</span>
                        </button>
                        <button onclick="document.getElementById('reply-box').classList.toggle('hidden')" class="flex items-center space-x-1 px-5 py-2 bg-red-600 hover:bg-red-700 rounded-md text-xs font-bold text-white transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            <span>BALAS</span>
                        </button>
                    </div>

                    <!-- Kotak Form Balas Pesan -->
                    <div id="reply-box" class="hidden mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('messages.store', $user->id) }}" method="POST">
                            @csrf
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Balas Pesan ke {{ $user->name }}</label>
                            <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-md p-3 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Tulis balasanmu di sini..."></textarea>
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="bg-gray-900 hover:bg-black text-white px-5 py-2 rounded-md text-xs font-bold transition">
                                    Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

            <!-- SIDEBAR KANAN -->
            <div class="w-full md:w-1/3 space-y-6">
                <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-800 mb-2">Google Reviews</p>
                    <p class="text-3xl font-bold text-gray-900 text-yellow-400">★★★★★ 4.9</p>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER SEMENTARA -->
    <footer class="bg-black p-6 text-center text-gray-500 text-sm border-t border-red-900/50">
        [ FOOTER GISKA NANTI DI SINI ]
    </footer>

</body>
</html>