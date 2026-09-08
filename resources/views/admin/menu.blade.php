@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME &gt; ADMIN CP &gt; MENU</div>
            <h1 class="text-3xl font-black text-gray-900 uppercase">MENU MANAGEMENT</h1>
            <p class="text-gray-500 text-sm mt-1">Manage the navigation items displayed across the XCODE Technical Network.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="mb-6 bg-[#d4edda] text-[#155724] border border-[#c3e6cb] px-4 py-3 rounded text-[13px]">
                {{ session('success') }}
            </div>
        @endif

        <!-- CURRENT MENU ITEMS Section -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 uppercase">CURRENT MENU ITEMS</h2>
                <span class="text-[10px] text-gray-400 uppercase tracking-wide">DO NOT MAKE TRANSLATE THE MENU ITEMS, YOU SHOULD MAKE TRANSLATION <a href="#" class="text-red-500 font-bold hover:underline">HERE</a></span>
            </div>

            <form action="{{ route('admin.menu.updateBulk') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Community Menu Group -->
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">COMMUNITY MENU</h3>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        
                        <!-- Table Header -->
                        <div class="flex items-center gap-4 px-4 py-2 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <div class="w-8 text-center">ACTIVE</div>
                            <div class="w-16">WEIGHT</div>
                            <div class="flex-1">NAME</div>
                            <div class="flex-1">PATH/URL</div>
                            <div class="w-8"></div>
                        </div>

                        <!-- Items -->
                        @foreach($community_menu as $item)
                        <div class="flex items-center gap-4 px-4 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors group">
                            <!-- Red Drag Handle Block -->
                            <div class="w-4 h-4 bg-red-600 rounded-sm flex items-center justify-center cursor-move"></div>
                            
                            <div class="w-4 flex justify-center">
                                <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                                <input type="checkbox" name="items[{{ $item->id }}][actived]" value="1" {{ $item->actived ? 'checked' : '' }} class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                            </div>
                            
                            <div class="w-16">
                                <input type="number" name="items[{{ $item->id }}][weight]" value="{{ $item->weight }}" class="w-full text-center bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-2 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="flex-1">
                                <input type="text" name="items[{{ $item->id }}][name]" value="{{ $item->name }}" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="flex-1">
                                <input type="text" name="items[{{ $item->id }}][path]" value="{{ $item->path }}" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="w-8 flex justify-center">
                                <button type="button" onclick="if(confirm('Hapus menu ini?')) document.getElementById('delete-menu-{{ $item->id }}').submit();" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Personal Menu Group -->
                <div class="mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">PERSONAL MENU</h3>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        <!-- Items -->
                        @foreach($personal_menu as $item)
                        <div class="flex items-center gap-4 px-4 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors group">
                            <!-- Red Drag Handle Block -->
                            <div class="w-4 h-4 bg-red-600 rounded-sm flex items-center justify-center cursor-move"></div>
                            
                            <div class="w-4 flex justify-center">
                                <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                                <input type="checkbox" name="items[{{ $item->id }}][actived]" value="1" {{ $item->actived ? 'checked' : '' }} class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                            </div>
                            
                            <div class="w-16">
                                <input type="number" name="items[{{ $item->id }}][weight]" value="{{ $item->weight }}" class="w-full text-center bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-2 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="flex-1">
                                <input type="text" name="items[{{ $item->id }}][name]" value="{{ $item->name }}" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="flex-1">
                                <input type="text" name="items[{{ $item->id }}][path]" value="{{ $item->path }}" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                            </div>
                            
                            <div class="w-8 flex justify-center">
                                <button type="button" onclick="if(confirm('Hapus menu ini?')) document.getElementById('delete-menu-{{ $item->id }}').submit();" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Save Changes Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-6 py-2.5 rounded shadow-sm transition-colors">
                        SAVE CHANGES
                    </button>
                </div>
            </form>
            
            <!-- Hidden delete forms -->
            @foreach($community_menu as $item)
                <form id="delete-menu-{{ $item->id }}" action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" style="display: none;">
                    @csrf @method('DELETE')
                </form>
            @endforeach
            @foreach($personal_menu as $item)
                <form id="delete-menu-{{ $item->id }}" action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" style="display: none;">
                    @csrf @method('DELETE')
                </form>
            @endforeach
        </div>

        <hr class="border-gray-200 mb-10">

        <!-- NEW ITEM Section -->
        <div class="mb-10">
            <h2 class="text-lg font-bold text-gray-900 uppercase mb-4">NEW ITEM</h2>
            
            <form action="{{ route('admin.menu.store') }}" method="POST">
                @csrf
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1 mb-4">
                    <!-- Table Header -->
                    <div class="flex items-center gap-4 px-4 py-2 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                        <div class="w-8 text-center">ACTIVE</div>
                        <div class="w-16">WEIGHT</div>
                        <div class="flex-1">NAME</div>
                        <div class="flex-1">PATH/URL</div>
                        <div class="flex-1">TYPE</div>
                    </div>

                    <!-- New Item Row -->
                    <div class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <!-- Red Drag Handle Placeholder (Optional for new row, using invisible block to align) -->
                        <div class="w-4 h-4 invisible"></div>
                        
                        <div class="w-4 flex justify-center">
                            <input type="checkbox" name="actived" value="1" checked class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                        </div>
                        
                        <div class="w-16">
                            <input type="number" name="weight" value="10" placeholder="10" class="w-full text-center bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-2 py-1.5 text-xs text-gray-900 transition-colors">
                        </div>
                        
                        <div class="flex-1">
                            <input type="text" name="name" required placeholder="e.g. Documentation" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                        </div>
                        
                        <div class="flex-1">
                            <input type="text" name="path" required placeholder="docs" class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors">
                        </div>

                        <div class="flex-1">
                            <select name="type" required class="w-full bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded px-3 py-1.5 text-xs text-gray-900 transition-colors appearance-none cursor-pointer">
                                <option value="community">Community</option>
                                <option value="personal">Personal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Create Button -->
                <div class="flex justify-end pb-8">
                    <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-6 py-2.5 rounded shadow-sm transition-colors flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> CREATE
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
