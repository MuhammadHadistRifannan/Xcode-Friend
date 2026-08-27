@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME &gt; ADMIN CP &gt; MODULES</div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">MODULES</h1>
                <p class="text-gray-500 text-sm mt-1">Manage the active modules and features available across the XCODE Technical Network.</p>
            </div>
            
            <button class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-xs font-bold px-4 py-2 rounded transition-colors shadow-sm whitespace-nowrap">
                System modules
            </button>
        </div>

        <!-- Alert Banner -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                <span class="text-xs font-bold text-red-800 uppercase tracking-wide">
                    IF YOU MODIFIED SOME MODULE FILES, YOU NEED TO CLICK THE 'UPDATE MODULES' TO MAKE THEM EFFECTIVE.
                </span>
            </div>
            <button class="bg-white hover:bg-red-50 border border-red-200 text-red-600 text-[10px] font-bold px-3 py-1.5 rounded transition-colors whitespace-nowrap">
                Update modules
            </button>
        </div>

        <!-- Modules Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-500 bg-gray-50/50">
                            <th class="px-6 py-4 font-bold w-20 text-center">ACTIVE</th>
                            <th class="px-6 py-4 font-bold">MODULE</th>
                            <th class="px-6 py-4 font-bold w-24">TYPE</th>
                            <th class="px-6 py-4 font-bold w-24">VERSION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        
                        <!-- CATEGORY: COMMUNITY MODULES -->
                        <tr>
                            <td colspan="4" class="px-6 py-4 bg-red-50/30">
                                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">COMMUNITY MODULES</span>
                            </td>
                        </tr>
                        @foreach($community_modules as $mod)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" checked class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $mod['name'] }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ $mod['desc'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-500 uppercase">{{ $mod['type'] }}</td>
                            <td class="px-6 py-4 text-xs text-gray-400">{{ $mod['version'] }}</td>
                        </tr>
                        @endforeach

                        <!-- CATEGORY: CORE MODULES -->
                        <tr>
                            <td colspan="4" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">CORE MODULES</span>
                            </td>
                        </tr>
                        @foreach($core_modules as $mod)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" checked disabled class="w-4 h-4 text-gray-300 bg-gray-200 border-gray-300 rounded cursor-not-allowed">
                            </td>
                            <td class="px-6 py-4 opacity-75">
                                <div class="font-bold text-gray-700">{{ $mod['name'] }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $mod['desc'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-400 uppercase opacity-75">{{ $mod['type'] }}</td>
                            <td class="px-6 py-4 text-xs text-gray-400 opacity-75">{{ $mod['version'] }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between pb-12">
            <button class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-6 py-2.5 rounded shadow-sm transition-colors">
                UPDATE MODULES
            </button>
            <a href="#" class="text-xs font-bold text-red-600 hover:text-red-800 uppercase tracking-wide transition-colors">
                + BROWSE MORE MODULES...
            </a>
        </div>

    </div>
</div>
@endsection
