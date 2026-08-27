@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header Section -->
    <div class="mb-8">
        <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">PAGES > CREATE</div>
        <h1 class="text-3xl font-bold text-gray-900">CREATE A PAGE</h1>
        <p class="text-sm text-gray-600 mt-1">Establish your presence on the X-CODE network.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        <!-- Left Column: Main Form (col-span-9) -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                <form action="#" method="POST" class="space-y-6">
                    
                    <!-- Page Address -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PAGE ADDRESS</label>
                        <div class="flex rounded-md border border-gray-300 overflow-hidden bg-gray-50">
                            <span class="px-4 py-2 text-sm text-gray-500 border-r border-gray-300">https://friends.xcode.co.id/index.php?p=page/</span>
                            <input type="text" placeholder="your-page-url" class="flex-1 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 italic">(0-9, a-z). Example: your-custom-name</p>
                    </div>

                    <!-- Page Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PAGE NAME</label>
                        <input type="text" placeholder="Enter the display name for your page" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500">
                    </div>

                    <!-- Page Description -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">PAGE DESCRIPTION (OPTIONAL)</label>
                        <textarea rows="4" placeholder="Briefly describe what this page is about..." class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 resize-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-3 px-4 rounded-md uppercase tracking-wider text-sm transition-colors shadow-sm flex justify-center items-center gap-2">
                            AJUKAN <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- Right Column: Sidebar Info (col-span-3) -->
        <div class="col-span-12 lg:col-span-3 space-y-6">
            
            <!-- Card 1: Guidelines -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Creation Guidelines</h3>
                <ul class="space-y-3 text-xs text-gray-600">
                    <li class="flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>Unique URL required</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>Alphanumeric characters only</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>Public visibility by default</span>
                    </li>
                </ul>
            </div>

            <!-- Card 2: System Info -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">System Info</h3>
                <div class="space-y-3 text-xs font-medium">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Network Status</span>
                        <span class="text-red-600 font-bold">OPERATIONAL</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Node ID</span>
                        <span class="text-gray-900">XC-772-B</span>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</div>
@endsection
