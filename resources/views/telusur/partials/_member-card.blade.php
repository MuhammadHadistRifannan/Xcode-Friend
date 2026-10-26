@php
    $genderLabel = match($member->gender) {
        1 => 'Cowok',
        0 => 'Cewek',
        default => 'Keduanya',
    };
    $age = $member->birthyear > 0 ? (int) date('Y') - $member->birthyear : null;
@endphp
<a href="{{ route('profile.show', $member->username) }}" class="group bg-white rounded-[14px] p-4 sm:p-5 shadow-sm flex flex-col items-center text-center hover:shadow-md hover:-translate-y-1 transition-all duration-200 w-full h-full border border-gray-200 animate-fade-in-up justify-between">
    <div class="flex flex-col items-center w-full">
        <!-- Avatar -->
        <div class="w-16 h-16 rounded-full bg-[#f4dada] flex items-center justify-center overflow-hidden mb-3 shrink-0">
            <img src="{{ $member->avatar_url }}" alt="{{ $member->username }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('assets/img/default.png') }}';">
        </div>

        <!-- Username -->
        <span class="text-sm font-bold text-[#b71c1c] group-hover:underline truncate max-w-full block" title="{{ $member->username }}">{{ $member->username }}</span>

        <!-- Bio / About Me -->
        @if(!empty($member->about_me))
            <p class="text-[11px] text-gray-500 mt-1 truncate max-w-full" title="{{ $member->about_me }}">{{ Str::limit($member->about_me, 24) }}</p>
        @endif

        <!-- Gender + Umur -->
        <p class="text-xs text-gray-500 mt-1">
            {{ $genderLabel }}{{ $age !== null ? ', ' . $age : '' }}
        </p>
    </div>

    <!-- Lokasi -->
    @if($member->location)
        <div class="flex items-center justify-center gap-1 mt-3 pt-2 border-t border-gray-100 text-xs text-gray-400 w-full truncate">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="truncate">{{ $member->location }}</span>
        </div>
    @endif
</a>
