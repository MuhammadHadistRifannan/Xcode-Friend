<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$users = User::all();
foreach ($users as $user) {
    $count = DB::table('jcow_followers')->where('uid', $user->id)->count();
    $user->followers = $count;
    $user->save();
}
echo "Done fixing followers count.\n";
