<?php
$files = glob("resources/views/**/*.blade.php");
$files = array_merge($files, glob("resources/views/**/**/*.blade.php"));
$files = array_merge($files, glob("app/Http/Controllers/**/*.php"));
$files[] = 'resources/views/home/guest.blade.php';

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    if (strpos($content, 'ui-avatars.com') === false) {
        continue;
    }
    
    // Replace in blade: 'https://ui-avatars.com... }}  -> asset('assets/img/default.png') }}
    $content = preg_replace("/'https:\/\/ui-avatars\.com[^}]+}}/", "asset('assets/img/default.png') }}", $content);
    
    // Replace in PHP: 'https://ui-avatars.com/api/?name='.urlencode(...).'&background=E5E5E5&size=128'
    $content = preg_replace("/'https:\/\/ui-avatars\.com[^'\"]*?'\s*\.\s*urlencode\([^)]+\)(?:\s*\.\s*'[^'\"]*?')?/", "asset('assets/img/default.png')", $content);

    // For guest.blade.php onerror
    $content = preg_replace("/onerror=\"this\.src='https:\/\/ui-avatars\.com[^\"]+\"/", "onerror=\"this.src='{{ asset('assets/img/default.png') }}'\"", $content);
    
    // Sometimes there are other variants
    $content = preg_replace("/'https:\/\/ui-avatars\.com[a-zA-Z0-9?&=\.\(\)\$\-\>_ ]+'/", "asset('assets/img/default.png')", $content);

    file_put_contents($file, $content);
    echo "Updated $file\n";
}
