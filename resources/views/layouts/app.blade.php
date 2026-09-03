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
    <style> body { background-color: #FAFAFA; } </style>
</head>
<body class="flex flex-col min-h-screen antialiased text-neutral-900">

    <!-- Memanggil Navbar -->
    @include('layouts.partials.navbar')

    <!-- Konten Utama -->
    <main class="flex-grow w-full py-10">
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
</body>
</html>
