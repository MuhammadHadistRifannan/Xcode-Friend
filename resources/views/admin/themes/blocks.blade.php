@extends('layouts.admin')

@section('content')
<div class="bg-[#f9f9f9] min-h-[calc(100vh-64px)] py-8 font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="text-[11px] font-medium text-gray-400 mb-1">Home &gt; Admin Panel &gt; Themes &gt; Manage Blocks</div>
            <h1 class="text-[28px] font-normal text-gray-800 tracking-tight mb-2">MANAGE BLOCKS</h1>
            <p class="text-[#666666] text-[13px] max-w-2xl leading-relaxed">
                Configure the layout of your community pages.<br>
                Assign available widgets and blocks to specific columns on the site.
            </p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-6 bg-[#d4edda] text-[#155724] border border-[#c3e6cb] px-4 py-3 rounded text-[13px]">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 bg-[#f8d7da] text-[#721c24] border border-[#f5c6cb] px-4 py-3 rounded text-[13px]">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.themes.blocks.update') }}" method="POST">
            @csrf
            
            <!-- Sticky Action Bar -->
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded border border-[#e5e5e5] shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-3.5 bg-[#cc0000]"></div>
                    <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">LAYOUT CONFIGURATION</h2>
                </div>
                <button type="submit" class="bg-[#b90000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-6 rounded transition-colors uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    SAVE BLOCKS
                </button>
            </div>

            <div class="space-y-6">
                <!-- Header Code -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">HEADER CODE</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Inserted just before the closing &lt;/head&gt; tag. Ideal for CSS, Meta tags, or tracking scripts.</p>
                    </div>
                    <div class="p-5">
                        <textarea name="header_code" rows="5" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none" placeholder="<!-- Your HTML or script here -->">{{ $blocks['header_code'] }}</textarea>
                    </div>
                </div>

                <!-- Footer Code -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">FOOTER CODE</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Inserted just before the closing &lt;/body&gt; tag. Ideal for Analytics or JS scripts.</p>
                    </div>
                    <div class="p-5">
                        <textarea name="footer_code" rows="5" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none" placeholder="<!-- Your HTML or script here -->">{{ $blocks['footer_code'] }}</textarea>
                    </div>
                </div>

                <!-- Left Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">LEFT COLUMN HTML</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Displays on the left sidebar (below the user menu).</p>
                    </div>
                    <div class="p-5">
                        <textarea name="left_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['left_column'] }}</textarea>
                    </div>
                </div>

                <!-- Center Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">CENTER COLUMN HTML</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Displays at the top of the main content feed.</p>
                    </div>
                    <div class="p-5">
                        <textarea name="center_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['center_column'] }}</textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">RIGHT COLUMN HTML</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Displays on the right sidebar (below sponsored ads/suggestions).</p>
                    </div>
                    <div class="p-5">
                        <textarea name="right_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['right_column'] }}</textarea>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
