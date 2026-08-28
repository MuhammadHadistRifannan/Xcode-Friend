<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - XCODE-FRIENDS</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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

</body>
</html>
