<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = optional(\App\Models\User::first())->id ?? 1;

$page = \App\Models\Page::first();
echo "Testing Page: {$page->id}\n";

// Emulate Like
$page->followers()->syncWithoutDetaching([$userId]);
$isLiked = $page->followers()->where('uid', $userId)->exists();
echo "After Like, isLiked = " . ($isLiked ? "Yes" : "No") . "\n";

// Emulate Unlike
$page->followers()->detach($userId);
$isLiked = $page->followers()->where('uid', $userId)->exists();
echo "After Unlike, isLiked = " . ($isLiked ? "Yes" : "No") . "\n";

// Check pivot table directly
$pivot = \DB::table('jcow_page_users')->where('pid', $page->id)->where('uid', $userId)->count();
echo "Pivot table rows: " . $pivot . "\n";
