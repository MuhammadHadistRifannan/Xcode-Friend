@extends('layouts.admin')

@section('content')
<div class="bg-[#f9f9f9] min-h-[calc(100vh-64px)] py-8 font-sans">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="text-[11px] font-medium text-gray-400 mb-1">Home &gt; Admin Panel &gt; Custom fields</div>
            <h1 class="text-[28px] font-normal text-gray-800 tracking-tight mb-2">CUSTOM FIELDS</h1>
            <p class="text-[#666666] text-[13px] max-w-2xl leading-relaxed">
                This setting is optional. You can ask your members some questions when they signing up<br>
                or changing profile. Their answers will be displayed on their profile.
            </p>
        </div>

        <!-- Alert Box -->
        <div class="bg-[#e9ecef] rounded text-[#495057] text-[13px] px-4 py-3 flex items-center gap-2 mb-10">
            <div class="w-4 h-4 rounded-full border border-[#495057] flex items-center justify-center text-[10px] font-bold shrink-0">i</div>
            <span>Go to 'Admin CP' &rarr; 'Themes' &rarr; 'Manage Blocks' to edit this message.</span>
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

        <!-- SECTION 1: EXAMPLE CONFIGURATION -->
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-3.5 bg-[#cc0000]"></div>
                <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">EXAMPLE CONFIGURATION</h2>
            </div>
            
            <div class="bg-white rounded border border-[#e5e5e5] p-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">QUESTION</label>
                            <input type="text" readonly value="Do you Smoke?" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded block p-2.5 outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">FIELD TYPE</label>
                            <select disabled class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded block p-2.5 outline-none appearance-none">
                                <option>Select Box</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">DEFAULT VALUE(S)</label>
                        <textarea readonly rows="4" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded block p-2.5 outline-none">Yes&#10;No</textarea>
                        <p class="text-[11px] text-[#888888] mt-1.5">One option per line for select boxes.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: FIELD SETUP -->
        <div class="relative">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-3.5 bg-[#cc0000]"></div>
                <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">FIELD SETUP</h2>
            </div>
            
            <form action="{{ route('admin.custom-fields.store') }}" method="POST">
                @csrf
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm">
                    
                    <!-- Header with SAVE CHANGES button -->
                    <div class="p-4 border-b border-[#e5e5e5] flex justify-end bg-white rounded-t">
                        <button type="submit" class="bg-[#b90000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-6 rounded transition-colors uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            SAVE CHANGES
                        </button>
                    </div>
                    
                    <!-- 7 Fixed Slots -->
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $field = isset($fields[$i]) ? $fields[$i] : null;
                        @endphp
                        <div class="p-6 {{ $i < 6 ? 'border-b border-[#e5e5e5]' : '' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">FIELD NAME {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</label>
                                        <input type="text" name="fields[{{ $i }}][name]" value="{{ $field ? $field->name : '' }}" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2.5 outline-none transition-all">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">TYPE</label>
                                        <select name="fields[{{ $i }}][type]" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2.5 outline-none transition-all">
                                            <option value="Disabled" {{ ($field && $field->type == 'Disabled') || !$field ? 'selected' : '' }}>Disabled</option>
                                            <option value="Text Box" {{ $field && $field->type == 'Text Box' ? 'selected' : '' }}>Text Box</option>
                                            <option value="Select Box" {{ $field && $field->type == 'Select Box' ? 'selected' : '' }}>Select Box</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="flex items-center gap-2 cursor-pointer mt-1">
                                            <input type="checkbox" name="fields[{{ $i }}][required]" value="1" {{ $field && $field->required ? 'checked' : '' }} class="w-4 h-4 text-[#cc0000] bg-white border-[#cccccc] rounded focus:ring-0">
                                            <span class="text-[13px] text-[#666666]">Member must fill in this field</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">OPTIONS FOR SELECT BOX</label>
                                        <textarea name="fields[{{ $i }}][options]" rows="3" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2.5 outline-none transition-all">{{ $field ? $field->options : '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#888888] tracking-widest uppercase mb-2">DESCRIPTION</label>
                                        <input type="text" name="fields[{{ $i }}][description]" value="{{ $field ? $field->description : '' }}" class="w-full bg-[#f8f9fa] border border-[#e5e5e5] text-[#333333] text-[13px] rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] block p-2.5 outline-none transition-all" placeholder="Help text shown to users">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
