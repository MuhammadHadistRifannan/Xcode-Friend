<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userFirst = \App\Models\User::first();
if (!$userFirst) {
    echo "NO USERS IN DB!\n";
} else {
    echo "First User ID: " . $userFirst->id . "\n";
}

echo "\n--- Pages ---\n";
$pages = \App\Models\Page::all();
foreach ($pages as $p) {
    echo "Page: {$p->id} | {$p->name} | UID: {$p->uid}\n";
}

echo "\n--- Page Users (Likes) ---\n";
$likes = \DB::table('jcow_page_users')->get();
foreach ($likes as $l) {
    echo "Like: UID {$l->uid} -> PID {$l->pid}\n";
}
