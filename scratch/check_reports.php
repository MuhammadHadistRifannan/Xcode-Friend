<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach (\App\Models\Report::all() as $r) {
    echo $r->id . ' - ' . $r->url . "\n";
}
