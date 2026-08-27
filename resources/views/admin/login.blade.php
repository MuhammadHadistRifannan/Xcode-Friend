<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - XCODE</title>
    @vite('resources/css/app.css')
    <!-- HAPUS FILE INI JIKA MIDDLEWARE SUDAH AKTIF -->
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Simulasi Login Admin</h1>
            <p class="text-sm text-gray-500 mt-2">Masuk ke XCODE Admin Control Panel (Mode Simulasi)</p>
        </div>

        <form action="{{ route('admin.login.process') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex justify-center items-center gap-2">
                Masuk ke Admin Panel
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            </button>
        </form>
    </div>

</body>
</html>
