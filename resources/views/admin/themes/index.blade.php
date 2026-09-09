@extends('layouts.admin')

@section('content')
<div class="bg-[#f9f9f9] min-h-[calc(100vh-64px)] py-8 font-sans">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="text-[11px] font-medium text-gray-400 mb-1">Home &gt; Admin Panel &gt; Themes</div>
            <h1 class="text-[28px] font-normal text-gray-800 tracking-tight mb-2">THEMES &amp; BLOCKS</h1>
            <p class="text-[#666666] text-[13px] max-w-2xl leading-relaxed">
                Manage the visual appearance of your community.<br>
                Customize layout blocks, widgets, and overall styling.
            </p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-6 bg-[#d4edda] text-[#155724] border border-[#c3e6cb] px-4 py-3 rounded text-[13px]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-3.5 bg-[#cc0000]"></div>
            <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">ACTIVE THEME</h2>
        </div>

        <div class="bg-white rounded border border-[#e5e5e5] p-6 shadow-sm mb-10">
            <form action="{{ route('admin.themes.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @foreach($availableThemes as $key => $label)
                    <!-- Theme Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="theme_color" value="{{ $key }}" class="peer sr-only" {{ $activeTheme === $key ? 'checked' : '' }}>
                        <div class="h-32 rounded-lg border-2 border-gray-200 group-hover:border-gray-300 peer-checked:border-[#cc0000] peer-checked:ring-1 peer-checked:ring-[#cc0000] flex flex-col items-center justify-center transition-all relative overflow-hidden bg-gray-50 peer-checked:bg-white">
                            
                            @if($key === 'red')
                            <div class="absolute inset-0 bg-red-100 opacity-50"></div>
                            <div class="w-8 h-8 rounded-full bg-[#cc0000] mb-2 shadow-sm z-10 ring-2 ring-white"></div>
                            @elseif($key === 'blue')
                            <div class="absolute inset-0 bg-blue-100 opacity-50"></div>
                            <div class="w-8 h-8 rounded-full bg-blue-600 mb-2 shadow-sm z-10 ring-2 ring-white"></div>
                            @elseif($key === 'dark')
                            <div class="absolute inset-0 bg-gray-900 opacity-90"></div>
                            <div class="w-8 h-8 rounded-full bg-gray-700 border border-gray-600 mb-2 shadow-sm z-10 ring-2 ring-gray-800"></div>
                            @endif
                            
                            <span class="text-[13px] font-bold z-10 {{ $key === 'dark' ? 'text-white' : 'text-gray-800' }}">{{ $label }}</span>
                            
                            <!-- Checkmark for active -->
                            <div class="absolute top-3 right-3 w-5 h-5 bg-[#cc0000] text-white rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity z-10 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
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
                    <a href="{{ route('admin.themes.blocks') }}" class="text-gray-500 hover:text-gray-700 text-[11px] font-bold py-2.5 px-4 rounded border border-gray-200 hover:bg-gray-50 transition-colors uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="layout-template" class="w-3.5 h-3.5"></i>
                        MANAGE BLOCKS
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
