<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = optional(\App\Models\User::first())->id ?? 1;
echo "User ID: " . $userId . "\n";

$page = \App\Models\Page::first();
echo "Before detach likes: " . $page->followers()->count() . "\n";

$res = $page->followers()->detach($userId);
echo "Detach result: " . $res . "\n";

echo "After detach likes: " . $page->followers()->count() . "\n";
