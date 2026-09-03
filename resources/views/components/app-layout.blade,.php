<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XCODE-FRIENDS (Preview Bima)</title>
    <!-- Tailwind CSS (Menggunakan CDN sementara agar desainmu langsung terlihat rapi) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-100">
    
    <!-- Navbar Giska Nanti Akan Berada di Sini -->
    <nav class="bg-black border-b border-red-900/50 p-4 shadow-md text-center text-red-500 font-bold tracking-widest">
        [ NAVBAR SEMENTARA ]
    </nav>

    <!-- KONTEN UTAMA KAMU MASUK KE SINI -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer Giska Nanti Akan Berada di Sini -->
    <footer class="bg-black border-t border-red-900/50 p-6 text-center mt-12 text-gray-500 text-sm">
        [ FOOTER SEMENTARA ]
    </footer>

</body>
</html>