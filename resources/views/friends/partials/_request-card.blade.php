<!-- Request Card -->
<div class="bg-white rounded-[14px] p-4 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-[#f4dada] flex items-center justify-center overflow-hidden shrink-0">
            <img src="{{ \App\Models\User::avatarUrl($req->avatar ?? null) }}" alt="{{ $req->fullname ?? $req->username }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';">
        </div>
        <div class="flex-grow">
                <p class="text-sm font-bold text-gray-900">{{ $req->username }}</p>
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
