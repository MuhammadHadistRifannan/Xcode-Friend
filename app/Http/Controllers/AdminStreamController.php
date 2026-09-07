<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stream;

class AdminStreamController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('jcow_streams')
            ->join('jcow_accounts', 'jcow_streams.uid', '=', 'jcow_accounts.id')
            ->select(
                'jcow_streams.*',
                'jcow_accounts.username',
                'jcow_accounts.fullname',
                'jcow_accounts.avatar'
            );

        if ($search = $request->input('search')) {
            $query->where('jcow_streams.message', 'like', "%{$search}%")
                  ->orWhere('jcow_accounts.username', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('jcow_streams.type', $type);
        }

        $streams = $query->orderBy('jcow_streams.created', 'desc')->paginate(25)->withQueryString();

        $typeStats = [
            'all'    => DB::table('jcow_streams')->count(),
            '1'      => DB::table('jcow_streams')->where('type', 1)->count(),
            '2'      => DB::table('jcow_streams')->where('type', 2)->count(),
            '3'      => DB::table('jcow_streams')->where('type', 3)->count(),
        ];

        return view('admin.stream-monitor', compact('streams', 'typeStats'));
    }

    public function destroy($id)
    {
        // Delete stream and its comments
        DB::table('jcow_comments')->where('stream_id', $id)->delete();
        DB::table('jcow_liked')->where('stream_id', $id)->delete();
        DB::table('jcow_streams')->where('id', $id)->delete();

        return back()->with('success', 'Postingan berhasil dihapus.');
    }
}
