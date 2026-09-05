<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$streams = App\Models\Stream::orderBy('created', 'desc')->take(10)->get();
foreach($streams as $s) {
    echo $s->id . ' - app: ' . ($s->app ?? 'null') . ', att: ' . $s->attachment . "\n";
}
