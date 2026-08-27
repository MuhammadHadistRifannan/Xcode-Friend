<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('video.index');
    }

    public function create()
    {
        return view('video.create');
    }

    public function watch($id)
    {
        $video = [
            'title' => 'Advanced Penetration Testing Techniques: Beyond the Basics',
        ];

        $playlist = [
            ['id' => 1, 'ep' => 'Part 1', 'title' => 'Advanced Penetration Testing Techniques: Beyond the Basics', 'desc' => 'Mempelajari teknik pemindaian jaringan lanjutan untuk mengidentifikasi kerentanan.', 'date' => '9 Jul 2026', 'is_active' => true],
            ['id' => 2, 'ep' => 'Part 2', 'title' => 'Exploitation and Privilege Escalation', 'desc' => 'Langkah-langkah eksploitasi dan cara meningkatkan hak akses di dalam sistem target.', 'date' => '16 Jul 2026', 'is_active' => false],
            ['id' => 3, 'ep' => 'Part 3', 'title' => 'Post-Exploitation and Pivoting', 'desc' => 'Tindakan pasca-eksploitasi dan pergerakan lateral (pivoting) di jaringan.', 'date' => '23 Jul 2026', 'is_active' => false],
            ['id' => 4, 'ep' => 'Part 4', 'title' => 'Covering Tracks and Reporting', 'desc' => 'Cara membersihkan jejak eksploitasi dan menyusun laporan penetration testing.', 'date' => '30 Jul 2026', 'is_active' => false],
        ];

        return view('video.watch', compact('id', 'video', 'playlist'));
    }
}
