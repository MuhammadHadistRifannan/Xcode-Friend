<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$musics = App\Models\Stream::where('app', 'music')->get();
echo "Streams: " . count($musics) . "\n";
foreach($musics as $m) {
    $likes = DB::table('jcow_liked')->where('stream_id', $m->id)->get();
    echo "ID: {$m->id}, msg: {$m->message}, likes count: " . count($likes) . "\n";
    foreach($likes as $l) {
        echo " - Liked by UID: {$l->uid}\n";
    }
}

$user = App\Models\User::first();
$profileRaw = $user->profile;
$theme = $profileRaw ? json_decode($profileRaw->custom_css ?? '{}', true) : [];
$musicplayer = $theme['musicplayer'] ?? false;
echo "Profile musicplayer: " . ($musicplayer ? 'true' : 'false') . "\n";
