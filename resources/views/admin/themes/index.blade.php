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
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                <!-- Thumbnail -->
                <div class="w-full md:w-1/3">
                    <div class="aspect-video bg-gray-100 rounded-lg border border-gray-200 overflow-hidden relative group">
                        <!-- Placeholder Image (Abstract/Gradient) -->
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                            <i data-lucide="layout" class="w-16 h-16 text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Theme Details -->
                <div class="w-full md:w-2/3 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $activeTheme }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200 animate-pulse">
                                ACTIVATED
                            </span>
                        </div>
                        <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">
                            This is the default system theme built for optimal performance and modern aesthetics. It provides a clean, responsive layout out of the box.
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.themes.blocks') }}" class="bg-[#cc0000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-6 rounded transition-colors uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="layout-template" class="w-3.5 h-3.5"></i>
                            MANAGE BLOCKS
                        </a>
                        <form action="{{ route('admin.themes.update') }}" method="POST" class="inline">
                            @csrf
                            <!-- Jika suatu saat ada pilihan tema, ini contoh inputnya -->
                            <input type="hidden" name="theme" value="System Standard Theme">
                            <button type="button" class="text-gray-500 hover:text-gray-700 text-[11px] font-bold py-2.5 px-4 rounded border border-gray-200 hover:bg-gray-50 transition-colors uppercase tracking-wide">
                                THEME SETTINGS
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
