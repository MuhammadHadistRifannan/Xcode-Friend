@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header Title -->
    <div class="mb-8">
        <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">Home / Profile Design</div>
        <h1 class="text-3xl font-bold text-gray-900">DESAIN PROFIL</h1>
    </div>

    <!-- 3 Column Layout -->
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Left Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h3 class="text-xs font-bold text-gray-500 mb-4 tracking-wider uppercase">MEDIA X-CODE</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                            <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="globe" class="w-4 h-4"></i></div>
                            Website X-code
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                            <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="message-square" class="w-4 h-4"></i></div>
                            Forum X-code
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                            <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="book-open" class="w-4 h-4"></i></div>
                            Blog X-code
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black leading-tight">
                            <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="graduation-cap" class="w-4 h-4"></i></div>
                            Bootcamp Pentest &<br>Cyber Security Engineer
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-black">
                            <div class="bg-blue-50 p-2 rounded text-blue-600"><i data-lucide="laptop" class="w-4 h-4"></i></div>
                            X-code Webinar
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Center Content (col-span-6) -->
        <div class="col-span-12 lg:col-span-6">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800">PENGATURAN TAMPILAN</h2>
                </div>
                
                <div class="p-6 flex flex-col md:flex-row gap-8">
                    <!-- Left: Phone Mockup -->
                    <div class="shrink-0 flex justify-center">
                        <div class="w-36 h-64 border-4 border-gray-300 rounded-xl bg-gray-50 shadow-inner relative overflow-hidden flex flex-col">
                            <div class="bg-red-100 p-2 text-center text-[10px] font-bold text-red-800 border-b border-red-200">
                                Desain Page
                            </div>
                            <div class="flex-1 flex flex-col items-center justify-center gap-2 p-2 opacity-50">
                                <div class="w-16 h-16 bg-gray-200 rounded-full"></div>
                                <div class="w-20 h-2 bg-gray-200 rounded"></div>
                                <div class="w-16 h-2 bg-gray-200 rounded"></div>
                            </div>
                            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-8 h-1 bg-gray-300 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Right: Checkboxes -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="space-y-5">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" class="mt-1 w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Pemutar musik</span>
                                    <p class="text-xs text-gray-500">Aktifkan musik yg ku sukai di halaman profil</p>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" class="mt-1 w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Wallpaper</span>
                                    <p class="text-xs text-gray-500">Aktifkan kustom wallpaper</p>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" checked class="mt-1 w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Halaman Umum</span>
                                    <p class="text-xs text-gray-500">Jadikan halaman umum keboleh</p>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" class="mt-1 w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Judul Blok</span>
                                    <p class="text-xs text-gray-500">Aktifkan judul blok kustom</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="mt-8">
                            <button class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2 rounded-md font-bold text-sm w-full transition-colors shadow-sm">
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            @php
                $sidebarData = [
                    'reviews' => ['rating' => 4.9, 'count' => 532],
                    'networkLinks' => [
                        ['id' => 1, 'label' => 'LinkedIn', 'url' => '#'],
                        ['id' => 2, 'label' => 'phpBB Group', 'url' => '#'],
                        ['id' => 3, 'label' => 'Facebook', 'url' => '#']
                    ]
                ];
            @endphp
            <x-sidebar-right :data="$sidebarData" />
        </div>

    </div>
</div>
@endsection
