<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stream;

class AdminStreamController extends Controller
{
    public function index(Request $request)
    {
        $query = Stream::with('user');

        if ($search = $request->input('search')) {
            $query->where('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%");
                  });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $streams = $query->orderBy('created', 'desc')->paginate(25)->withQueryString();

        $typeStats = [
            'all'    => Stream::count(),
            '1'      => Stream::where('type', 1)->count(),
            '2'      => Stream::where('type', 2)->count(),
            '3'      => Stream::where('type', 3)->count(),
        ];

        return view('admin.stream-monitor', compact('streams', 'typeStats'));
    }

    public function destroy($id)
    {
        $stream = Stream::find($id);
        if ($stream) {
            $stream->comments()->delete();
            $stream->likedBy()->delete();
            $stream->delete();
        }

        return back()->with('success', 'Postingan berhasil dihapus.');
    }
}
