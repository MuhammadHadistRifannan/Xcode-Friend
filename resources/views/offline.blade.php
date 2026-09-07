<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Sedang Offline - X-CODE</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111111] text-gray-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full text-center">
        <!-- Logo/Icon -->
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-900/30 mb-8 border border-red-500/20">
            <i data-lucide="power" class="w-12 h-12 text-red-500"></i>
        </div>
        
        <h1 class="text-4xl sm:text-5xl font-black text-white mb-6 uppercase tracking-tight">Offline Mode</h1>
        
        <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-gray-800 shadow-2xl mb-8">
            <p class="text-gray-400 text-lg leading-relaxed">
                {{ $reason ?? 'Situs X-CODE sedang dalam masa pemeliharaan sistem. Silakan kembali lagi nanti.' }}
            </p>
        </div>

        <div class="text-sm text-gray-600 font-bold uppercase tracking-widest">
            X-CODE NETWORK INFRASTRUCTURE
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
