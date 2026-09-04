<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/routes/web.php';
$content = file_get_contents($file);

$search = "Route::patch('admin/reports/{id}/resolve', [AdminController::class, 'reportsResolve'])->name('admin.reports.resolve');";
$replace = $search . "\n        Route::delete('admin/reports/{id}', [AdminController::class, 'reportsDestroy'])->name('admin.reports.destroy');";

if (strpos($content, "Route::delete('admin/reports/{id}'") === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added admin.reports.destroy route.\n";
} else {
    echo "Route already exists.\n";
}
