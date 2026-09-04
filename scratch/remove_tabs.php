<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/profile/dinding.blade.php';
$content = file_get_contents($file);

$searchTabs = <<<'EOD'
            <a href="?tab=teman" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'teman' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Teman</a>
            <a href="?tab=pengikut" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'pengikut' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Pengikut</a>
            <a href="?tab=mengikuti" class="text-xs font-bold pb-3 uppercase tracking-wider transition {{ $tab === 'mengikuti' ? 'text-red-700 border-b-2 border-red-700' : 'text-neutral-500 hover:text-neutral-800' }}">Mengikuti</a>
EOD;

$replaceTabs = ''; // Remove them

$content = str_replace($searchTabs, $replaceTabs, $content);

file_put_contents($file, $content);
echo "Removed extra tabs from horizontal menu.\n";
