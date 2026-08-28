@extends('layouts.app')

@section('content')
<div class="py-12 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Tombol Kembali & Info Lawan Bicara -->
        <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100 text-red-700 font-bold text-sm uppercase">
                    {{ substr($user->fullname, 0, 1) }}
                </span>
                <div>
                    <h2 class="font-bold text-gray-900">{{ $user->fullname }}</h2>
                    <p class="text-xs text-gray-500">@<span>{{ $user->username }}</span></p>
                </div>
            </div>
            <a href="{{ route('messages.index') }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md font-medium transition">
                &larr; Kembali ke Inbox
            </a>
        </div>

        <!-- Kotak Riwayat Percakapan -->
        <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm space-y-4 max-h-[500px] overflow-y-auto">
            @forelse($messages as $msg)
                <div class="flex flex-col {{ $msg->from_id === Auth::id() ? 'items-end' : 'items-start' }}">
                    <div class="max-w-md px-4 py-3 rounded-lg text-sm {{ $msg->from_id === Auth::id() ? 'bg-red-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                        @if($msg->subject)
                            <p class="text-xs font-bold mb-1 opacity-90">{{ $msg->subject }}</p>
                        @endif
                        <p>{{ $msg->message }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1">
                        {{ \Carbon\Carbon::createFromTimestamp($msg->created)->format('d M Y, H:i') }}
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-400 text-sm py-6">Belum ada percakapan. Mulai kirim pesan di bawah!</p>
            @endforelse
        </div>

        <!-- Form Kirim Balasan (Memenuhi kolom subject & message) -->
        <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
            <form action="{{ route('messages.store', $user->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="subject" placeholder="Subjek Pesan..." class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" value="Balasan" required>
                </div>
                <div>
                    <textarea name="message" rows="3" placeholder="Tulis balasan pesan..." class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md text-xs font-bold transition">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection