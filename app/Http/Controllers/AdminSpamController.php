<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpamLog;

class AdminSpamController extends Controller
{
    /**
     * Display the spam log monitor page.
     */
    public function index(Request $request)
    {
        $query = SpamLog::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('username', 'like', "%{$search}%");
                })
                ->orWhere('message', 'like', "%{$search}%")
                ->orWhere('reasons', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created', 'desc')->paginate(25)->withQueryString();

        $stats = [
            'total'       => SpamLog::count(),
            'today'       => SpamLog::where('created', '>=', strtotime('today'))->count(),
            'this_week'   => SpamLog::where('created', '>=', strtotime('-7 days'))->count(),
            'unique_users'=> SpamLog::distinct('user_id')->count('user_id'),
        ];

        return view('admin.spam.index', compact('logs', 'stats'));
    }

    /**
     * Delete a single spam log entry.
     */
    public function destroy($id)
    {
        SpamLog::where('id', $id)->delete();
        return back()->with('success', 'Log spam berhasil dihapus.');
    }

    /**
     * Clear all spam log entries.
     */
    public function clear()
    {
        SpamLog::truncate();
        return back()->with('success', 'Seluruh riwayat spam log berhasil dibersihkan.');
    }
}
