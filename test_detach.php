<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\DB::enableQueryLog();
$page = \App\Models\Page::find(2);
$page->followers()->detach(1);
dump(\DB::getQueryLog());
