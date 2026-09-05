<?php
/**
 * Fix photo modal layout: use flex-col instead of absolute for the top bar,
 * so the bar and image stack vertically inside the modal.
 */

$files = [
    'resources/views/pages/media.blade.php',
    'resources/views/groups/media.blade.php',
    'resources/views/pages/show.blade.php',
    'resources/views/groups/show.blade.php',
];

// Pattern with 16-space indent (media.blade.php files)
$patterns = [
    // media.blade.php (16 space indent for outer div)
    [
        'old' => '                <div class="relative w-full h-full max-w-5xl max-h-[90vh] flex items-center justify-center" @click.away="photoUrl = null">
                    <!-- Top bar: uploader left, close button right -->
                    <div class="absolute -top-12 left-0 right-0 flex items-center justify-between bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-lg">
                        <div class="flex items-center gap-2">
                            <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                            <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                        </div>
                        <button type="button" @click="photoUrl = null" class="text-white hover:text-red-400 transition-colors flex items-center gap-1.5 text-sm font-bold">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <img :src="photoUrl" class="w-full h-full object-contain rounded-lg shadow-2xl">
                </div>',
        'new' => '                <div class="flex flex-col w-full max-w-5xl max-h-[90vh]" @click.away="photoUrl = null">
                    <!-- Top bar: uploader left, close button right -->
                    <div class="flex items-center justify-between bg-white/10 backdrop-blur-md px-4 py-2 rounded-t-lg mb-2">
                        <div class="flex items-center gap-2">
                            <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                            <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                        </div>
                        <button type="button" @click="photoUrl = null" class="text-white hover:text-red-400 transition-colors flex items-center gap-1.5 text-sm font-bold">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <img :src="photoUrl" class="w-full h-full object-contain rounded-b-lg shadow-2xl">
                </div>',
    ],
    // pages/show.blade.php and groups/show.blade.php (20 space indent)
    [
        'old' => '                    <div class="relative w-full h-full max-w-5xl max-h-[90vh] flex items-center justify-center" @click.away="photoUrl = null">
                        <!-- Top bar: uploader left, close button right -->
                        <div class="absolute -top-12 left-0 right-0 flex items-center justify-between bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-lg">
                            <div class="flex items-center gap-2">
                                <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                                <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                            </div>
                            <button type="button" @click="photoUrl = null" class="text-white hover:text-red-400 transition-colors flex items-center gap-1.5 text-sm font-bold">
                                Tutup <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <img :src="photoUrl" class="w-full h-full object-contain rounded-lg shadow-2xl">
                    </div>',
        'new' => '                    <div class="flex flex-col w-full max-w-5xl max-h-[90vh]" @click.away="photoUrl = null">
                        <!-- Top bar: uploader left, close button right -->
                        <div class="flex items-center justify-between bg-white/10 backdrop-blur-md px-4 py-2 rounded-t-lg mb-2">
                            <div class="flex items-center gap-2">
                                <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                                <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                            </div>
                            <button type="button" @click="photoUrl = null" class="text-white hover:text-red-400 transition-colors flex items-center gap-1.5 text-sm font-bold">
                                Tutup <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <img :src="photoUrl" class="w-full h-full object-contain rounded-b-lg shadow-2xl">
                    </div>',
    ],
];

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    foreach ($patterns as $pair) {
        $content = str_replace($pair['old'], $pair['new'], $content);
    }
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
        $count++;
    } else {
        echo "No match: $file\n";
    }
}

echo "Done. Updated $count file(s).\n";
