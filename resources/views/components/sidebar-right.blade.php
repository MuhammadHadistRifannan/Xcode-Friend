@props(['data'])

<div class="w-full space-y-4">
    <!-- Google Reviews Card -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 text-center shadow-sm">
        <h3 class="text-xs font-bold text-gray-600 mb-3 tracking-wider">Google Reviews</h3>
        <div class="flex justify-center gap-1 mb-2">
            @for ($i = 0; $i < 5; $i++)
                <i data-lucide="star" class="w-5 h-5 fill-yellow-400 text-yellow-400"></i>
            @endfor
        </div>
        <div class="text-2xl font-bold text-gray-900 mb-1">{{ $data['reviews']['rating'] }}</div>
        <a href="#" class="text-sm text-blue-600 hover:underline">{{ $data['reviews']['count'] }} Reviews</a>
    </div>

    <!-- Network Links Card -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-5">Network Links</h3>
        <ul class="space-y-4">
            @foreach($data['networkLinks'] as $index => $link)
                <li>
                    <a href="{{ $link['url'] }}" class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                @if($index === 0) <i data-lucide="briefcase" class="w-5 h-5"></i> @endif
                                @if($index === 1) <i data-lucide="users" class="w-5 h-5"></i> @endif
                                @if($index === 2) <i data-lucide="facebook" class="w-5 h-5"></i> @endif
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ $link['label'] }}</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-blue-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
