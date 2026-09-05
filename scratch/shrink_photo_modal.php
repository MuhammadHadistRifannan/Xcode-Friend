<?php

$files = [
    'resources/views/pages/media.blade.php',
    'resources/views/groups/media.blade.php',
    'resources/views/pages/show.blade.php',
    'resources/views/groups/show.blade.php',
];

// Shrink max-w and constrain image height
$replacements = [
    'flex flex-col w-full max-w-5xl max-h-[90vh]'
        => 'flex flex-col w-full max-w-3xl',
    'object-contain rounded-b-lg shadow-2xl'
        => 'max-h-[72vh] object-contain rounded-b-lg shadow-2xl',
];

foreach ($files as $file) {
    $c = file_get_contents($file);
    foreach ($replacements as $old => $new) {
        $c = str_replace($old, $new, $c);
    }
    file_put_contents($file, $c);
    echo "Updated: $file\n";
}
echo "Done.\n";
