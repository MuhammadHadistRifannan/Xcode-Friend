<!-- Section Ke Anda (Incoming Requests) -->
<div class="mb-8">
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
                @include('friends.partials._request-card', ['req' => $req])
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-[14px] p-6 shadow-sm">
            <p class="text-sm text-gray-500 text-center">Tidak ada permintaan pertemanan masuk.</p>
        </div>
    @endif
</div>
