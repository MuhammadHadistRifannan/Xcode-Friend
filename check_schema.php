<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apps = App\Models\Stream::select('app')->distinct()->pluck('app')->toArray();
echo "Apps in jcow_streams: " . implode(', ', $apps) . "\n";
$types = App\Models\Stream::select('type')->distinct()->pluck('type')->toArray();
echo "Types in jcow_streams: " . implode(', ', $types) . "\n";

$musicLikes = \DB::table('jcow_liked')->join('jcow_streams', 'jcow_liked.stream_id', '=', 'jcow_streams.id')->where('jcow_streams.app', 'music')->count();
echo "Music likes count: " . $musicLikes . "\n";
