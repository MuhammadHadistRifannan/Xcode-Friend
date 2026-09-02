@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME > ADMIN CP > MODULES</div>
            <h1 class="text-3xl font-black text-gray-900">MODULES</h1>
            <p class="text-gray-500 text-sm mt-2">Manage the active modules and features available across the XCODE Technical Network.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex flex-col gap-1">
                <div class="flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span>Ada kesalahan:</span>
                </div>
                <ul class="list-disc pl-8 font-normal text-xs mt-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- Alert Banner -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <i data-lucide="triangle-alert" class="w-6 h-6 text-red-600 shrink-0"></i>
                <p class="text-xs font-bold text-gray-600 tracking-wide uppercase">IF YOU MODIFIED SOME MODULE FILES, YOU NEED TO CLICK THE 'UPDATE MODULES' TO MAKE THEM EFFECTIVE.</p>
            </div>
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2.5 px-6 rounded-md transition-colors shrink-0 border border-gray-200 text-center leading-tight">
                Update<br>modules
            </button>
        </div>

        <form action="{{ route('admin.modules.toggle') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-gray-100 bg-white">
                    <div class="col-span-1 text-[10px] font-bold text-gray-400 tracking-widest uppercase text-center">ACTIVE</div>
                    <div class="col-span-7 text-[10px] font-bold text-gray-400 tracking-widest uppercase">MODULE</div>
                    <div class="col-span-2 text-[10px] font-bold text-gray-400 tracking-widest uppercase text-center">TYPE</div>
                    <div class="col-span-2 text-[10px] font-bold text-gray-400 tracking-widest uppercase text-right">VERSION</div>
                </div>

                <!-- COMMUNITY MODULES SECTION -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/30">
                    <h3 class="text-xs font-black text-red-600 tracking-widest uppercase">COMMUNITY MODULES</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($community_modules as $module)
                    <div class="grid grid-cols-12 gap-4 px-6 py-5 items-center hover:bg-gray-50 transition-colors">
                        <!-- Checkbox -->
                        <div class="col-span-1 flex justify-center">
                            <!-- We use a hidden input to ensure 0 is sent if unchecked, but we are doing updateOrInsert on checked ones anyway. Actually, we update all to 0, then 1 for checked, so just the checkbox is fine -->
                            <input type="checkbox" name="modules[{{ $module['name'] }}]" value="1" 
                                   {{ $module['actived'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#b90000] bg-gray-100 border-gray-300 rounded focus:ring-[#b90000] cursor-pointer">
                        </div>
                        
                        <!-- Module Info -->
                        <div class="col-span-7">
                            <h4 class="text-sm font-bold text-gray-900">{{ $module['name'] }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $module['desc'] }}</p>
                        </div>
                        
                        <!-- Type -->
                        <div class="col-span-2 text-center text-xs font-bold text-gray-400">
                            {{ $module['type'] }}
                        </div>
                        
                        <!-- Version -->
                        <div class="col-span-2 text-right text-xs font-bold text-gray-600">
                            {{ $module['version'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- CORE MODULES SECTION -->
                <div class="px-6 py-4 border-y border-gray-100 bg-gray-50/30 mt-2">
                    <h3 class="text-xs font-black text-gray-500 tracking-widest uppercase">CORE MODULES</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($core_modules as $module)
                    <div class="grid grid-cols-12 gap-4 px-6 py-5 items-center bg-white opacity-80">
                        <!-- Lock Icon -->
                        <div class="col-span-1 flex justify-center">
                            <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center">
                                <i data-lucide="lock" class="w-3 h-3 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Module Info -->
                        <div class="col-span-7">
                            <h4 class="text-sm font-bold text-gray-600">{{ $module['name'] }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $module['desc'] }}</p>
                        </div>
                        
                        <!-- Type -->
                        <div class="col-span-2 text-center text-xs font-bold text-gray-300">
                            {{ $module['type'] }}
                        </div>
                        
                        <!-- Version -->
                        <div class="col-span-2 text-right text-xs font-bold text-gray-400">
                            {{ $module['version'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between mt-8">
                <button type="submit" class="bg-[#b90000] hover:bg-red-700 text-white text-xs font-bold py-3 px-6 rounded transition-colors">
                    UPDATE MODULES
                </button>
                <a href="#" class="text-xs font-bold text-[#b90000] hover:text-red-700 transition-colors uppercase">
                    + Browse More Modules...
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
