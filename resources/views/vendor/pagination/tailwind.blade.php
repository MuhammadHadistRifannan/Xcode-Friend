@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center w-full my-4">
        <div class="inline-flex items-center justify-center gap-1 sm:gap-1.5 flex-wrap">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg leading-none shadow-xs select-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg leading-none shadow-xs hover:bg-gray-50 hover:text-red-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors select-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-400 bg-transparent cursor-default leading-none select-none dark:text-neutral-500">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center min-w-[36px] h-9 sm:min-w-[40px] sm:h-10 px-3 text-xs sm:text-sm font-bold text-white bg-red-700 border border-red-700 rounded-lg leading-none shadow-xs select-none dark:bg-red-700 dark:border-red-700">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[36px] h-9 sm:min-w-[40px] sm:h-10 px-3 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg leading-none shadow-xs hover:bg-gray-50 hover:text-red-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors select-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg leading-none shadow-xs hover:bg-gray-50 hover:text-red-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors select-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg leading-none shadow-xs select-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif

        </div>
    </nav>
@endif
