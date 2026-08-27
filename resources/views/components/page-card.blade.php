@props(['page'])

@php
    $isActive = $page['status'] === 'active';
    $isLink = str_contains($page['title'], 'http') || str_contains($page['title'], 'claim');
@endphp

<a href="{{ route('pages.show', $page['id']) }}" class="block bg-white rounded-lg border {{ $isActive ? 'border-l-4 border-l-red-600 border-t-gray-200 border-r-gray-200 border-b-gray-200' : 'border-gray-200' }} p-5 cursor-pointer hover:shadow-md transition-shadow">
    <div class="flex items-start gap-4">
        <div class="bg-gray-100 p-3 rounded-md">
            <i data-lucide="file-text" class="w-6 h-6 text-gray-500"></i>
        </div>
        <div class="flex-1">
            <div class="flex items-start justify-between mb-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-semibold text-gray-800 text-base">{{ $page['title'] }}</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full border border-gray-200">
                        {{ $page['likes'] }} orang menyukai ini
                    </span>
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap">
                    • Last update: {{ $page['lastUpdate'] }}
                </span>
            </div>
            <p class="text-sm {{ $isLink ? 'text-blue-500 hover:underline' : 'text-gray-500' }} mt-2">
                {{ $page['description'] }}
            </p>
        </div>
    </div>
</a>
