<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        Report::create([
            'uid' => Auth::id(),
            'url' => $request->url,
            'message' => $request->message,
            'hasread' => 0,
            'created' => time()
        ]);

        return back()->with('success', 'Laporan berhasil dikirim dan akan ditinjau oleh admin.');
    }
}
