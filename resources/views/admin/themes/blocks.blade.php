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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">LEFT COLUMN</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Usually contains navigation and stats.</p>
                    </div>
                    <div class="p-5 space-y-4">
                        @for($i = 0; $i < 5; $i++)
                            @php $currentBlock = $blocks['left_column'][$i] ?? ''; @endphp
                            <div>
                                <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-1.5">SLOT 0{{ $i + 1 }}</label>
                                <select name="blocks[left_column][]" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2 outline-none">
                                    <option value="">-- None --</option>
                                    @foreach($availableBlocks as $b)
                                        <option value="{{ $b }}" {{ $currentBlock == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- CENTER COLUMN -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">CENTER COLUMN</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Main content area (Feed, Posts).</p>
                    </div>
                    <div class="p-5 space-y-4">
                        @for($i = 0; $i < 5; $i++)
                            @php $currentBlock = $blocks['center_column'][$i] ?? ''; @endphp
                            <div>
                                <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-1.5">SLOT 0{{ $i + 1 }}</label>
                                <select name="blocks[center_column][]" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2 outline-none">
                                    <option value="">-- None --</option>
                                    @foreach($availableBlocks as $b)
                                        <option value="{{ $b }}" {{ $currentBlock == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4">
                        <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">RIGHT COLUMN</h3>
                        <p class="text-[11px] text-gray-500 mt-1">Ideal for suggestions and ads.</p>
                    </div>
                    <div class="p-5 space-y-4">
                        @for($i = 0; $i < 5; $i++)
                            @php $currentBlock = $blocks['right_column'][$i] ?? ''; @endphp
                            <div>
                                <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-1.5">SLOT 0{{ $i + 1 }}</label>
                                <select name="blocks[right_column][]" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2 outline-none">
                                    <option value="">-- None --</option>
                                    @foreach($availableBlocks as $b)
                                        <option value="{{ $b }}" {{ $currentBlock == $b ? 'selected' : '' }}>{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
