<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = 1;
$count = \App\Models\Page::whereHas('followers', function ($q) use ($userId) {
    $q->where('jcow_page_users.uid', $userId);
})->count();

echo "Liked Pages Count: " . $count . "\n";
