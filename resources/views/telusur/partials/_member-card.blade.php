@php
    $genderLabel = match($member->gender) {
        1 => 'Cowok',
        0 => 'Cewek',
        default => 'Keduanya',
    };
    $age = $member->birthyear > 0 ? (int) date('Y') - $member->birthyear : null;
@endphp
<a href="{{ route('profile.show', $member->username) }}" class="bg-white rounded-[14px] p-6 shadow-sm flex flex-col items-center text-center hover:shadow-md transition w-full max-w-[220px] block border border-gray-200">
    <!-- Avatar -->
    <div class="w-16 h-16 rounded-full bg-[#f4dada] flex items-center justify-center overflow-hidden mb-3">
        @if($member->avatar)
            <img src="{{ asset('storage/avatars/' . $member->avatar) }}" alt="{{ $member->username }}" class="w-full h-full object-cover">
        @else
            <span class="text-[#b71c1c] font-bold text-2xl">{{ substr($member->fullname ?? $member->username, 0, 1) }}</span>
        @endif
    </div>

    <!-- Username -->
    <span class="text-sm font-bold text-[#b71c1c]">{{ $member->username }}</span>

    <!-- Gender + Umur -->
    <p class="text-xs text-gray-500 mt-1">
        {{ $genderLabel }}{{ $age !== null ? ', ' . $age : '' }}
    </p>

    <!-- Lokasi -->
    @if($member->location)
        <div class="flex items-center gap-1 mt-2 text-xs text-gray-400">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ $member->location }}</span>
        </div>
    @endif
</a>
