<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// ── 1. Insert Album Video ──
$albumId = DB::table('jcow_story_categories')->insertGetId([
    'name'        => 'Kursus Penetration Testing',
    'description' => 'Seri lengkap belajar penetration testing dari dasar hingga mahir.',
    'app'         => 'video',
    'gid'         => 0,
    'weight'      => 1,
    'uri'         => '',
    'var1' => '', 'var2' => '', 'var3' => '', 'var4' => '', 'var5' => '',
]);

echo "Album inserted, ID: $albumId\n";

// ── 2. Insert Video-video demo ──
$videos = [
    [
        'title'   => 'Advanced Penetration Testing: Beyond the Basics',
        'var1'    => 'https://www.youtube.com/watch?v=3Kq1MIfTWCE',
        'content' => 'Mempelajari teknik pemindaian jaringan lanjutan untuk mengidentifikasi kerentanan yang tersembunyi.',
        'created' => strtotime('2026-07-09'),
    ],
    [
        'title'   => 'Exploitation and Privilege Escalation',
        'var1'    => 'https://www.youtube.com/watch?v=2TofunAI6fU',
        'content' => 'Langkah-langkah eksploitasi dan cara meningkatkan hak akses di dalam sistem target setelah initial access.',
        'created' => strtotime('2026-07-16'),
    ],
    [
        'title'   => 'Post-Exploitation and Pivoting',
        'var1'    => 'https://www.youtube.com/watch?v=nzD6f9-zNjM',
        'content' => 'Tindakan pasca-eksploitasi termasuk pergerakan lateral (pivoting) di jaringan internal target.',
        'created' => strtotime('2026-07-23'),
    ],
    [
        'title'   => 'Covering Tracks and Reporting',
        'var1'    => 'https://www.youtube.com/watch?v=5FbQCaLMhwA',
        'content' => 'Cara membersihkan jejak eksploitasi dan menyusun laporan penetration testing yang profesional.',
        'created' => strtotime('2026-07-30'),
    ],
];

$firstVideoId = null;
foreach ($videos as $v) {
    $id = DB::table('jcow_stories')->insertGetId([
        'cid'           => $albumId,
        'uid'           => 1,
        'app'           => 'video',
        'sticky'        => 0,
        'closed'        => 0,
        'title'         => $v['title'],
        'content'       => $v['content'],
        'var1'          => $v['var1'],
        'var2'          => '', 'var3' => '', 'var4' => '', 'var5' => '',
        'thumbnail'     => '',
        'views'         => rand(100, 999),
        'comments'      => 0,
        'stream_id'     => 0,
        'digg'          => 0,
        'dugg'          => 0,
        'photos'        => 0,
        'featured'      => 0,
        'tags'          => 'security,hacking',
        'lastreply'     => 0,
        'lastreplyuname'=> '',
        'lastreplyuid'  => 0,
        'updated'       => $v['created'],
        'created'       => $v['created'],
        'text1'         => '',
        'text2'         => '',
        'blob1'         => '',
        'rating'        => '',
        'page_id'       => 0,
        'page_type'     => '',
    ]);
    if (!$firstVideoId) $firstVideoId = $id;
    echo "  - Video inserted: [{$id}] {$v['title']}\n";
}

echo "\nDone! Visit: http://127.0.0.1:8000/videos/{$firstVideoId}/watch\n";
