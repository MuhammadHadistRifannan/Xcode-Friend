<?php
$file = 'd:/Magang_Xcode/Xcode/Xcode-Friend/app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

$search = <<<'EOD'
    public function reportsResolve($id)
    {
        \App\Models\Report::where('id', $id)->update(['hasread' => 1]);
        return back()->with('success', 'Laporan telah ditandai sebagai telah diselesaikan.');
    }
}
EOD;

$replace = <<<'EOD'
    public function reportsResolve($id)
    {
        \App\Models\Report::where('id', $id)->update(['hasread' => 1]);
        return back()->with('success', 'Laporan telah ditandai sebagai telah diselesaikan.');
    }

    public function reportsDestroy($id)
    {
        \App\Models\Report::where('id', $id)->delete();
        return back()->with('success', 'Laporan telah dihapus.');
    }
}
EOD;

$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "Added reportsDestroy to AdminController.\n";
