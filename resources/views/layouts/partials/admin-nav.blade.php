{{-- Admin navigation, shared by the desktop sidebar and the mobile drawer. Expects $navGroups from layouts.admin. --}}
@foreach($navGroups as $group => $items)
    <p class="px-4 pb-2 {{ $loop->first ? '' : 'pt-5' }} text-xs font-medium text-zinc-500">{{ $group }}</p>
    <div class="grid gap-1">
        @foreach($items as [$route, $label, $icon, $patterns])
            <a href="{{ route($route) }}" class="admin-link" @if(request()->routeIs(...$patterns)) aria-current="page" @endif>
                <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px] shrink-0"></i>
                <span class="truncate">{{ $label }}</span>
            </a>
        @endforeach
    </div>
@endforeach
