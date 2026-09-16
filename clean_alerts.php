<?php

$dir = new RecursiveDirectoryIterator('d:\Magang_Xcode\Xcode\Xcode-Friend\resources\views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$successPattern = '/@if\s*\(\s*session\(\'success\'\)\s*\).*?@endif/is';
$errorPattern = '/@if\s*\(\s*session\(\'error\'\)\s*\).*?@endif/is';
$errorsPattern = '/@if\s*\(\s*\$errors->any\(\)\s*\).*?@endif/is';

$count = 0;

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // We do NOT want to remove them from layout files if they contain the new SweetAlert logic
    if (strpos($path, 'app.blade.php') !== false || strpos($path, 'admin.blade.php') !== false) {
        continue;
    }

    $content = preg_replace($successPattern, '', $content);
    $content = preg_replace($errorPattern, '', $content);
    $content = preg_replace($errorsPattern, '', $content);

    if ($content !== $original) {
        file_put_contents($path, $content);
        $count++;
        echo "Cleaned $path\n";
    }
}

echo "Done. Cleaned $count files.\n";
