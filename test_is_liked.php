<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = \App\Models\Page::first();
$userId = 1;

try {
    $isLiked = $page->followers()->where('uid', $userId)->exists();
    echo "Is Liked: " . ($isLiked ? "Yes" : "No") . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
