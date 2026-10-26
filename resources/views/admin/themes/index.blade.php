@extends('layouts.admin')

@section('content')
<div class="bg-[#f9f9f9] min-h-[calc(100vh-64px)] py-8 font-sans">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="text-[11px] font-medium text-gray-400 mb-1">Home &gt; Admin Panel &gt; Themes</div>
            <h1 class="text-[28px] font-normal text-gray-800 tracking-tight mb-2">THEMES</h1>
            <p class="text-[#666666] text-[13px] max-w-2xl leading-relaxed">
                Manage the visual appearance of your community.<br>
                Customize overall styling and colors.
            </p>
        </div>

        

        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-3.5 bg-[#cc0000]"></div>
            <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">ACTIVE THEME</h2>
        </div>

        <div class="bg-white rounded border border-[#e5e5e5] p-6 shadow-sm mb-10">
            <form action="{{ route('admin.themes.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @php
                        $themeConfigs = [
                            'red' => [
                                'border_checked' => 'peer-checked:border-[#cc0000] peer-checked:ring-1 peer-checked:ring-[#cc0000]',
                                'bg_overlay' => 'background-color: #fee2e2 !important; opacity: 0.5;',
                                'dot_style' => 'background-color: #cc0000 !important;',
                                'dot_class' => 'ring-2 ring-white',
                                'check_style' => 'background-color: #cc0000 !important;',
                                'text_class' => 'text-gray-800',
                            ],
                            'blue' => [
                                'border_checked' => 'peer-checked:border-[#2563eb] peer-checked:ring-1 peer-checked:ring-[#2563eb]',
                                'bg_overlay' => 'background-color: #dbeafe !important; opacity: 0.5;',
                                'dot_style' => 'background-color: #2563eb !important;',
                                'dot_class' => 'ring-2 ring-white',
                                'check_style' => 'background-color: #2563eb !important;',
                                'text_class' => 'text-gray-800',
                            ],
                            'dark' => [
                                'border_checked' => 'peer-checked:border-gray-800 peer-checked:ring-1 peer-checked:ring-gray-800',
                                'bg_overlay' => 'background-color: #111827 !important; opacity: 0.95;',
                                'dot_style' => 'background-color: #374151 !important;',
                                'dot_class' => 'border border-gray-600 ring-2 ring-gray-800',
                                'check_style' => 'background-color: #1f2937 !important;',
                                'text_class' => 'text-white',
                            ],
                        ];
                    @endphp

                    @foreach($availableThemes as $key => $label)
                    @php
                        $cfg = $themeConfigs[$key] ?? [
                            'border_checked' => 'peer-checked:border-gray-400 peer-checked:ring-1 peer-checked:ring-gray-400',
                            'bg_overlay' => 'background-color: #f3f4f6 !important;',
                            'dot_style' => 'background-color: #6b7280 !important;',
                            'dot_class' => 'ring-2 ring-white',
                            'check_style' => 'background-color: #374151 !important;',
                            'text_class' => 'text-gray-800',
                        ];
                    @endphp
                    <!-- Theme Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="theme_color" value="{{ $key }}" class="peer sr-only" {{ $activeTheme === $key ? 'checked' : '' }}>
                        <div class="h-32 rounded-lg border-2 border-gray-200 group-hover:border-gray-300 {{ $cfg['border_checked'] }} flex flex-col items-center justify-center transition-all relative overflow-hidden bg-gray-50 peer-checked:bg-white no-theme">
                            
                            <div class="absolute inset-0 no-theme pointer-events-none" style="{{ $cfg['bg_overlay'] }}"></div>
                            <div class="w-8 h-8 rounded-full mb-2 shadow-sm z-10 no-theme {{ $cfg['dot_class'] }}" style="{{ $cfg['dot_style'] }}"></div>
                            
                            <span class="text-[13px] font-bold z-10 no-theme {{ $cfg['text_class'] }}">{{ $label }}</span>
                            
                            <!-- Checkmark for active -->
                            <div class="absolute top-3 right-3 w-5 h-5 text-white rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity z-10 shadow-sm no-theme" style="{{ $cfg['check_style'] }}">
                                <i data-lucide="check" class="w-3 h-3 text-white"></i>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-[#cc0000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-6 rounded transition-colors uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        SAVE THEME
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
