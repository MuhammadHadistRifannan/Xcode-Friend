<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - XCODE')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @php
        $activeTheme = \App\Helpers\SettingHelper::get('theme_color', 'red');
    @endphp

    @if($activeTheme === 'dark')
    <style>
        body, main, aside { background-color: #121212 !important; color: #e5e5e5 !important; }
        .bg-white, .bg-\[\#f9f9f9\], .bg-\[\#f5f5f5\] { background-color: #1e1e1e !important; border-color: #333 !important; color: #e5e5e5 !important; }
        .text-gray-900, .text-neutral-900, .text-gray-800, .text-black, .text-gray-700, .text-gray-600 { color: #f5f5f5 !important; }
        .text-gray-500, .text-gray-400, .text-neutral-500 { color: #aaa !important; }
        .border-gray-200, .border-gray-100, .border-\[\#e5e5e5\] { border-color: #333 !important; }
        .bg-gray-50, .bg-\[\#f8f9fa\], .bg-gray-100, .bg-gray-200 { background-color: #2a2a2a !important; }
        .hover\:bg-gray-50:hover, .hover\:bg-gray-200:hover { background-color: #333 !important; }
    </style>
    @elseif($activeTheme === 'blue')
    <style>
        .text-red-600, .text-red-500, .text-red-700, .text-\[\#b90000\], .text-\[\#cc0000\] { color: #2563eb !important; }
        .bg-red-600, .bg-red-50, .bg-red-100, .bg-\[\#b90000\], .bg-\[\#cc0000\], .bg-\[\#990000\] { background-color: #2563eb !important; color: white !important; }
        .hover\:bg-red-700:hover, .hover\:bg-\[\#a00000\]:hover, .hover\:bg-red-100:hover { background-color: #1d4ed8 !important; }
        .hover\:text-red-500:hover, .hover\:text-red-400:hover { color: #60a5fa !important; }
        .border-red-700, .border-\[\#cc0000\] { border-color: #2563eb !important; }
        .focus\:border-\[\#cc0000\]:focus, .focus\:ring-\[\#cc0000\]:focus { border-color: #2563eb !important; --tw-ring-color: #2563eb !important; }
    </style>
    @endif
    <style> body { background-color: #f5f5f5; } </style>
</head>
<body class="flex flex-col h-screen overflow-hidden antialiased text-neutral-900">



    <!-- Layout Admin -->
    <div class="flex-grow flex w-full h-full overflow-hidden">
        
        <!-- Sidebar Management Tools -->
        <aside class="w-64 bg-white border-r border-gray-200 h-full overflow-y-auto hidden md:flex flex-col flex-shrink-0">
            <div class="p-6 flex-grow">
                <div class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4">Management Tools</div>
                <nav class="flex flex-col gap-1">
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard', 'admin.index') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard', 'admin.index') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.site-config') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.site-config') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="settings" class="w-4 h-4 {{ request()->routeIs('admin.site-config') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Site Config
                    </a>

                    <a href="{{ route('admin.modules') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.modules') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="box" class="w-4 h-4 {{ request()->routeIs('admin.modules') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Modules
                    </a>

                    <a href="{{ route('admin.menu') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.menu') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="list" class="w-4 h-4 {{ request()->routeIs('admin.menu') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Menu
                    </a>

                    
                    <a href="{{ route('admin.themes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.themes', 'admin.themes.blocks') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="palette" class="w-4 h-4 {{ request()->routeIs('admin.themes', 'admin.themes.blocks') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Themes & Blocks
                    </a>

                    <a href="{{ route('admin.members') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.members', 'admin.members.show') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.members', 'admin.members.show') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Members
                    </a>

                    <a href="{{ route('admin.stream-monitor') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stream-monitor') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="activity" class="w-4 h-4 {{ request()->routeIs('admin.stream-monitor') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Stream Monitor
                    </a>

                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.reports') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="alert-triangle" class="w-4 h-4 {{ request()->routeIs('admin.reports') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Reports
                    </a>

                    <a href="{{ route('admin.custom-fields') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.custom-fields') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="layout-list" class="w-4 h-4 {{ request()->routeIs('admin.custom-fields') ? 'text-red-500' : 'text-gray-400' }}"></i>
                        Custom Fields
                    </a>
                </nav>
            </div>

            <div class="p-6 border-t border-gray-200">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('beranda') }}" class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Tampilan Utama
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors bg-red-50 text-red-600 hover:bg-red-100">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Konten Utama Admin -->
        <main class="flex-grow relative h-full overflow-y-auto bg-[#f5f5f5]">
            @yield('content')
            

        </main>
    </div>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
