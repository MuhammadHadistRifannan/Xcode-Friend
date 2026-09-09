<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();

if ($user) {
    // Buat data stream dummy untuk musik
    $stream = App\Models\Stream::create([
        'uid' => $user->id,
        'wall_id' => $user->id,
        'message' => 'Lagu Uji Coba (Dummy)',
        'app' => 'music',
        'type' => 1,
        'attachment' => 'sample_music.mp3',
        'aid' => 0,
        'hide' => 0,
        'likes' => 1,
        'created' => time(),
        'updated' => time(),
    ]);

    // Buat data like agar lagu ini muncul di pemutar musik
    \DB::table('jcow_liked')->insert([
        'uid' => $user->id,
        'stream_id' => $stream->id
    ]);

    // Update profile user untuk enable music player
    $profile = \App\Models\Profile::firstOrCreate(
        ['id' => $user->id],
        [
            'style_ids' => '',
            'custom_css' => '{}',
            'background' => '',
            'videoid' => 0,
            'favorites' => 0,
            'views' => 0
        ]
    );
    $theme = json_decode($profile->custom_css ?? '{}', true);
    if(!is_array($theme)) $theme = [];
    $theme['musicplayer'] = true;
    $profile->custom_css = json_encode($theme);
    $profile->save();

    echo "Dummy music created and liked by user {$user->username}. Music player enabled in profile.\n";
} else {
    echo "No user found.\n";
}
