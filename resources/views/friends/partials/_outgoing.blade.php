<!-- Section Ke Lainnya (Outgoing Requests) -->
<div class="mb-8">
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
            <p class="text-sm text-gray-500 text-center">Kamu memiliki 0 permintaan tertunda</p>
        </div>
    @endif
</div>
