{{--
    Komponen Page Card — dipakai di pages/index dan pages/mine (liked tab).

    $page dapat berupa:
    - Eloquent Model App\Models\Page (dari DB, memiliki followers_count dari withCount)
    - Array asosiatif (dari mock data lama — backward compat)
--}}
@props(['page'])

@php
    // Support baik Eloquent Model maupun array (backward compat)
    $isModel   = is_object($page);
    $id        = $isModel ? $page->id        : $page['id'];
    $title     = $isModel ? $page->name      : ($page['title'] ?? $page['name'] ?? '');
    $desc      = $isModel ? $page->description : ($page['description'] ?? '');
    $likes     = $isModel
                    ? ($page->followers_count ?? $page->users ?? 0)
                    : ($page['likes'] ?? 0);
    $lastUpdate = $isModel
                    ? ($page->updated ? date('d M Y', $page->updated) : '-')
                    : ($page['lastUpdate'] ?? '-');
    $status    = $isModel ? ($page->status ?? 'normal') : ($page['status'] ?? 'normal');
    $logoUrl   = $isModel ? $page->logo_url  : null;
    $isActive  = $status === 'active';
@endphp

<a href="{{ route('pages.show', $id) }}"
   class="block bg-white rounded-lg border {{ $isActive ? 'border-l-4 border-l-red-600 border-t-gray-200 border-r-gray-200 border-b-gray-200' : 'border-gray-200' }} p-5 cursor-pointer hover:shadow-md transition-shadow group">

    <div class="flex items-start gap-4">

        <!-- Logo / Placeholder -->
        <div class="shrink-0">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $title }}"
                     class="w-12 h-12 rounded-md object-cover border border-gray-200 group-hover:opacity-90 transition-opacity">
            @else
                <div class="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center border border-gray-200">
                    <i data-lucide="file-text" class="w-5 h-5 text-gray-400"></i>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-1 gap-2">
                <div class="flex items-center gap-2 flex-wrap min-w-0">
                    <h3 class="font-semibold text-gray-800 text-base group-hover:text-red-700 transition-colors truncate">
                        {{ $title }}
                    </h3>
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full border border-gray-200 whitespace-nowrap shrink-0">
                        <i data-lucide="heart" class="w-3 h-3 inline-block -mt-0.5 mr-0.5"></i>
                        {{ number_format($likes) }}
                    </span>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">
                    {{ $lastUpdate }}
                </span>
            </div>

            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                {{ $desc ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>

    </div>
</a>
