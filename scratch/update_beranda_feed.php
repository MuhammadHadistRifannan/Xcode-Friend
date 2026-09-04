<?php
// 1. Remove the image from beranda.blade.php
$fileBlade = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/home/beranda.blade.php';
$contentBlade = file_get_contents($fileBlade);

$searchImage = <<<'EOD'
            <!-- Custom Image Bawah (Sesuai Desain) -->
            <div class="w-full h-72 bg-neutral-200 rounded-xl overflow-hidden shadow-sm mt-6">
                <img src="{{ asset('assets/img/hero-banner.jpg') }}" alt="Community" class="w-full h-full object-cover">
            </div>
EOD;

$contentBlade = str_replace($searchImage, '', $contentBlade);
file_put_contents($fileBlade, $contentBlade);
echo "Image removed from beranda.\n";

// 2. Modify HomeController.php to fetch all posts
$fileController = 'd:/Magang_Xcode/Xcode/Xcode-Friend/app/Http/Controllers/HomeController.php';
$contentController = file_get_contents($fileController);

$searchController = <<<'EOD'
        // Dapatkan ID user yang diikuti
        $followingIds = $user->following()->pluck('fid')->toArray();
        $followingIds[] = $user->id; // Tambahkan ID sendiri

        // Ambil Feed Berita (Mengecualikan postingan grup dan pages, hanya dari user yang diikuti)
        $streams = Stream::with(['user', 'comments.user'])
                    ->whereIn('uid', $followingIds)
                    ->where(function($query) {
                        $query->whereNotIn('app', ['group', 'page'])
                              ->orWhereNull('app');
                    })
                    ->orderBy('created', 'desc')
                    ->paginate(12);
EOD;

$replaceController = <<<'EOD'
        // Ambil Feed Berita (Seluruh unggahan dari semua pengguna secara global, diurutkan berdasarkan waktu)
        $streams = Stream::with(['user', 'comments.user'])
                    ->where(function($query) {
                        $query->whereNotIn('app', ['group', 'page'])
                              ->orWhereNull('app');
                    })
                    ->orderBy('created', 'desc')
                    ->paginate(12);
EOD;

$contentController = str_replace($searchController, $replaceController, $contentController);
file_put_contents($fileController, $contentController);
echo "HomeController updated to fetch global feed.\n";
