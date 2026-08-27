@props(['profile'])

<div class="w-full">
    <!-- Profile Info -->
    <div class="bg-white rounded-lg p-5 border border-gray-200 mb-4">
        <div class="flex items-center gap-3 mb-4">
            <img 
                src="{{ $profile['avatarUrl'] }}" 
                alt="{{ $profile['name'] }}" 
                class="w-12 h-12 rounded-full border border-red-100 p-0.5"
            >
            <div>
                <h2 class="font-bold text-lg leading-tight">{{ $profile['name'] }}</h2>
                @if($profile['isVerified'])
                    <span class="text-xs text-gray-500 font-medium">Verified Entity</span>
                @endif
            </div>
        </div>

        <div class="flex gap-2 mb-4">
            <button class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-sm font-medium py-2 rounded-md transition-colors">
                <i data-lucide="thumbs-up" class="w-4 h-4"></i> Menyukai
            </button>
            <button class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-sm font-medium py-2 rounded-md transition-colors">
                <i data-lucide="thumbs-down" class="w-4 h-4"></i> Tidak suka
            </button>
        </div>

        <div class="rounded-lg overflow-hidden mb-4 border border-gray-200 aspect-square">
            <img src="{{ $profile['coverUrl'] }}" alt="Cover" class="w-full h-full object-cover">
        </div>

        <div class="space-y-2">
            <button class="w-full bg-[#b91c1c] hover:bg-red-800 text-white text-sm font-medium py-2 rounded-md transition-colors flex items-center justify-center gap-2">
                <span class="w-4 h-4 border-2 border-white rounded-sm opacity-80"></span> Edit page profile
            </button>
            <button class="w-full bg-[#b91c1c] hover:bg-red-800 text-white text-sm font-medium py-2 rounded-md transition-colors flex items-center justify-center gap-2">
                <span class="w-4 h-4 border-2 border-white rounded-sm opacity-80"></span> Edit page Logo
            </button>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-lg p-5 border border-gray-200 mb-4">
        <h3 class="text-xs font-bold text-gray-500 mb-3 tracking-wider uppercase">DESKRIPSI</h3>
        <p class="text-sm text-gray-800 mb-4">{{ $profile['description'] }}</p>
        
        <div class="flex items-center justify-between text-xs text-gray-500 mb-4 border-t border-gray-100 pt-3">
            <span class="font-medium">{{ $profile['likesCount'] }} ORANG MENYUKAI INI</span>
            <a href="#" class="text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        
        <div class="flex">
            <img src="{{ $profile['avatarUrl'] }}" alt="Liked user" class="w-8 h-8 rounded-full border-2 border-white">
        </div>
    </div>

    <!-- Media Links -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-xs font-bold text-gray-500 mb-3 tracking-wider uppercase">MEDIA X-CODE</h3>
        <ul class="space-y-4">
            <li>
                <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                    <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="globe" class="w-4 h-4"></i></div>
                    Website X-code
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                    <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="message-square" class="w-4 h-4"></i></div>
                    Forum X-code
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                    <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="book-open" class="w-4 h-4"></i></div>
                    Blog X-code
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black leading-tight">
                    <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="graduation-cap" class="w-4 h-4"></i></div>
                    Bootcamp Pentest & Cyber Security Engineer
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                    <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="laptop" class="w-4 h-4"></i></div>
                    X-code Webinar
                </a>
            </li>
        </ul>
    </div>
</div>
