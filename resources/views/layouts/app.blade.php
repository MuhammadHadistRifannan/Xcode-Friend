<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'XCODE-FRIENDS')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Montserrat (Gotham Bold alternative) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800;900&display=swap" rel="stylesheet">
    @php
        $activeTheme = \App\Helpers\SettingHelper::get('theme_color', 'red');
        $headerCode = \App\Helpers\SettingHelper::get('theme_block_header_code', '');
        $footerCode = \App\Helpers\SettingHelper::get('theme_block_footer_code', '');
    @endphp

    @if($activeTheme === 'dark')
    <style>
        body, main { background-color: #121212 !important; color: #e5e5e5 !important; }
        .bg-white, .bg-\[\#f9f9f9\] { background-color: #1e1e1e !important; border-color: #333 !important; color: #e5e5e5 !important; }
        .text-gray-900, .text-neutral-900, .text-gray-800, .text-black { color: #f5f5f5 !important; }
        .text-gray-500, .text-gray-600, .text-neutral-500 { color: #aaa !important; }
        .border-gray-200, .border-gray-100, .border-\[\#e5e5e5\] { border-color: #333 !important; }
        .bg-gray-50, .bg-\[\#f8f9fa\] { background-color: #2a2a2a !important; }
    </style>
    @elseif($activeTheme === 'blue')
    <style>
        .text-red-600, .text-red-500, .text-red-700, .text-\[\#b90000\], .text-\[\#cc0000\] { color: #2563eb !important; }
        .bg-red-600, .bg-red-50, .bg-red-100, .bg-\[\#b90000\], .bg-\[\#cc0000\], .bg-\[\#990000\] { background-color: #2563eb !important; color: white !important; }
        .hover\:bg-red-700:hover, .hover\:bg-\[\#a00000\]:hover { background-color: #1d4ed8 !important; }
        .hover\:text-red-500:hover, .hover\:text-red-400:hover { color: #60a5fa !important; }
        .border-red-700, .border-\[\#cc0000\] { border-color: #2563eb !important; }
        .focus\:border-\[\#cc0000\]:focus, .focus\:ring-\[\#cc0000\]:focus { border-color: #2563eb !important; --tw-ring-color: #2563eb !important; }
    </style>
    @endif

    <style> body { background-color: #FAFAFA; } </style>
    
</head>
<body class="flex flex-col min-h-screen antialiased text-neutral-900">

    {!! $headerCode !!}

    <!-- Memanggil Navbar -->
    @include('layouts.partials.navbar')

    <!-- Konten Utama -->
    <main class="flex-grow w-full py-10 relative">
        @yield('content')
    </main>

    <!-- Memanggil Footer -->
    @include('layouts.partials.footer')

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @stack('scripts')
    
    {!! $footerCode !!}
</body>
</html>
