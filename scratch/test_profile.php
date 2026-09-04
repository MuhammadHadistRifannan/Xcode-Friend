<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = App\Models\Profile::firstOrCreate(['id' => 999], [
        'style_ids' => '',
        'custom_css' => '',
        'background' => 'test',
        'videoid' => 0,
        'favorites' => 0,
        'views' => 0
    ]);
    echo 'Success';
} catch (\Exception $e) {
    echo $e->getMessage();
}
