<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSpamController extends Controller
{
    /**
     * Display the spam log monitor page.
     */
    public function index(Request $request)
    {
        $query = DB::table('jcow_spam_log')
            ->leftJoin('jcow_accounts', 'jcow_spam_log.user_id', '=', 'jcow_accounts.id')
            ->select(
                'jcow_spam_log.*',
                'jcow_accounts.username',
                'jcow_accounts.fullname',
                'jcow_accounts.avatar'
            );

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('jcow_accounts.username', 'like', "%{$search}%")
                  ->orWhere('jcow_spam_log.message', 'like', "%{$search}%")
                  ->orWhere('jcow_spam_log.reasons', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('jcow_spam_log.created', 'desc')->paginate(25)->withQueryString();

        $stats = [
            'total'       => DB::table('jcow_spam_log')->count(),
            'today'       => DB::table('jcow_spam_log')->where('created', '>=', strtotime('today'))->count(),
            'this_week'   => DB::table('jcow_spam_log')->where('created', '>=', strtotime('-7 days'))->count(),
            'unique_users'=> DB::table('jcow_spam_log')->distinct('user_id')->count('user_id'),
        ];

        return view('admin.spam.index', compact('logs', 'stats'));
    }

    /**
     * Delete a single spam log entry.
     */
    public function destroy($id)
    {
        DB::table('jcow_spam_log')->where('id', $id)->delete();
        return back()->with('success', 'Log spam berhasil dihapus.');
    }

    /**
     * Clear all spam log entries.
     */
    public function clear()
    {
        DB::table('jcow_spam_log')->truncate();
        return back()->with('success', 'Seluruh riwayat spam log berhasil dibersihkan.');
    }
}
