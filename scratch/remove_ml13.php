<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/resources/views/home/beranda.blade.php';
$content = file_get_contents($file);

$content = str_replace('ml-13', '', $content);
$content = preg_replace('/ +/', ' ', $content); // clean up double spaces
$content = str_replace('class=" "', 'class=""', $content);

file_put_contents($file, $content);
echo "Removed ml-13 from beranda.\n";
