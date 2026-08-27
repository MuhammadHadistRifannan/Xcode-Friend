<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kotak Keluar - XCODE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <!-- NAVBAR SEMENTARA -->
    <nav class="bg-black p-4 shadow-md text-center text-red-500 font-bold tracking-widest border-b border-red-900/50">
        [ NAVBAR GISKA NANTI DI SINI ]
    </nav>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">

            <!-- KONTEN UTAMA: Kotak Keluar -->
            <div class="w-full md:w-2/3 bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
                
                <!-- Header & Tabs -->
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex space-x-6">
                        <a href="{{ route('messages.index') }}" class="pb-2 text-sm font-medium text-gray-500 hover:text-gray-700">Kotak Masuk</a>
                        <a href="{{ route('messages.outbox') }}" class="pb-2 text-sm font-bold text-gray-900 border-b-2 border-red-600">Kotak Keluar</a>
                    </div>
                </div>

                <!-- Tabel Kotak Keluar -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase bg-gray-50/50">
                                <th class="p-4 w-10"><input type="checkbox" class="rounded border-gray-300 text-red-600"></th>
                                <th class="p-4 font-semibold">To (Penerima)</th>
                                <th class="p-4 font-semibold">Message Preview</th>
                                <th class="p-4 font-semibold text-right">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($messages as $msg)
                                <tr class="hover:bg-red-50/30 transition bg-white">
                                    <td class="p-4">
                                        <input type="checkbox" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    </td>
                                    <td class="p-4 flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-700 font-bold text-xs">
                                            {{ substr($msg->receiver->name ?? 'U', 0, 1) }}
                                        </span>
                                        <span class="font-bold text-gray-900">{{ $msg->receiver->name ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="p-4 text-gray-700 truncate max-w-xs">{{ Str::limit($msg->message, 40) }}</td>
                                    <td class="p-4 text-right text-xs text-gray-500">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 text-sm">
                                        Belum ada pesan yang dikirim (Kotak Keluar kosong).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer Aksi Tabel -->
                <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md text-xs font-bold transition">
                        Hapus Terpilih
                    </button>
                </div>

            </div>

            <!-- SIDEBAR KANAN -->
            <div class="w-full md:w-1/3 space-y-6">
                <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-800 mb-2">Google Reviews</p>
                    <p class="text-3xl font-bold text-gray-900 text-yellow-400">★★★★★ 4.9</p>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER SEMENTARA -->
    <footer class="bg-black p-6 text-center text-gray-500 text-sm border-t border-red-900/50">
        [ FOOTER GISKA NANTI DI SINI ]
    </footer>

</body>
</html>