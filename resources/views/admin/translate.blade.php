@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME &gt; ADMIN PANEL &gt; TRANSLATE</div>
            <h1 class="text-3xl font-black text-gray-900 uppercase">TRANSLATE</h1>
        </div>

        <div class="space-y-6">
            <!-- TRANSLATE Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8">
                    
                    <div class="flex items-center gap-4 max-w-lg">
                        <label class="block text-sm font-medium text-gray-700 whitespace-nowrap">Add a language</label>
                        
                        <div class="relative flex-1">
                            <select class="w-full bg-white border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-md pl-4 pr-10 py-2.5 text-sm text-gray-900 transition-colors appearance-none cursor-pointer">
                                <option>Amharic</option>
                                <option>Arabic</option>
                                <option>Bengali</option>
                                <option>Chinese</option>
                                <option>English</option>
                                <option>French</option>
                                <option>German</option>
                                <option>Hindi</option>
                                <option>Indonesian</option>
                                <option>Japanese</option>
                                <option>Russian</option>
                                <option>Spanish</option>
                            </select>
                            <!-- Custom dropdown arrow -->
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <button class="bg-red-700 hover:bg-red-800 text-white text-sm font-bold px-8 py-2.5 rounded-md shadow-sm transition-colors">
                            ADD
                        </button>
                    </div>

                </div>
            </div>
            
            <!-- Footer Note -->
            <div class="mt-4">
                <span class="text-[11px] text-gray-400">Go to <span class="text-red-400 italic">'Admin CP &gt; Themes &gt; Manage Blocks'</span> to edit this message</span>
            </div>
        </div>

    </div>
</div>
@endsection
