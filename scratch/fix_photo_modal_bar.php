<?php

$files = [
    'resources/views/pages/media.blade.php',
    'resources/views/groups/media.blade.php',
    'resources/views/pages/show.blade.php',
    'resources/views/groups/show.blade.php',
];

// The old pattern (absolute positioned, overlapping)
$oldPattern = <<<EOD
                    <button type="button" @click="photoUrl = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md z-10">
                        Tutup <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    
                    <!-- Uploader Info top-left -->
                    <div class="absolute -top-12 left-0 flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-lg">
                        <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                        <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                    </div>
EOD;

// The new pattern: single bar with uploader left, close button right
$newPattern = <<<EOD
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
EOD;

// Also handle pages/show.blade.php and groups/show.blade.php which use different indentation
$oldPatternIndented = <<<EOD
                        <button type="button" @click="photoUrl = null" class="absolute -top-12 right-0 text-white hover:text-red-500 transition-colors flex items-center gap-2 text-sm font-bold bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md z-10">
                            Tutup <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        
                        <!-- Uploader Info top-left -->
                        <div class="absolute -top-12 left-0 flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-lg">
                            <img :src="photoUploader.avatar" class="w-7 h-7 rounded-full border-2 border-white/50 object-cover shrink-0">
                            <span class="text-white text-sm font-semibold" x-text="photoUploader.name"></span>
                        </div>
EOD;

$newPatternIndented = <<<EOD
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
EOD;

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $new = str_replace($oldPattern, $newPattern, $content);
    $new = str_replace($oldPatternIndented, $newPatternIndented, $new);
    if ($new !== $content) {
        file_put_contents($file, $new);
        echo "Updated: $file\n";
        $count++;
    } else {
        echo "No match: $file\n";
    }
}

echo "Done. Updated $count file(s).\n";
