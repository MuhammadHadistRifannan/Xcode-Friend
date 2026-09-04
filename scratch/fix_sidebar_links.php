<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/profile/dinding.blade.php';
$content = file_get_contents($file);

// Perbaiki link PENGIKUT
$searchPengikut = <<<'EOD'
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">PENGIKUT</h4>
                    <a href="?tab=pengikut" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
EOD;

// Perbaiki link MENGIKUTI
$searchMengikuti = <<<'EOD'
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="?tab=pengikut" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
EOD;
$replaceMengikuti = <<<'EOD'
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">MENGIKUTI</h4>
                    <a href="?tab=mengikuti" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
EOD;
$content = str_replace($searchMengikuti, $replaceMengikuti, $content);

// Perbaiki link TEMAN
$searchTeman = <<<'EOD'
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">TEMAN</h4>
                    <a href="?tab=pengikut" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
EOD;
$replaceTeman = <<<'EOD'
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-[11px] font-bold text-neutral-800 uppercase tracking-wider">TEMAN</h4>
                    <a href="?tab=teman" class="text-[9px] font-bold text-red-700 hover:underline">Lihat Semua</a>
                </div>
EOD;
$content = str_replace($searchTeman, $replaceTeman, $content);

file_put_contents($file, $content);
echo "Sidebar links updated.\n";
