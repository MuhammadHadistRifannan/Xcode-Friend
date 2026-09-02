<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stream;

class LikeController extends Controller
{
    public function toggle(Request $request, $streamId)
    {
        $userId = auth()->id();
        $stream = Stream::findOrFail($streamId);

        $existingLike = DB::table('jcow_liked')
            ->where('uid', $userId)
            ->where('stream_id', $streamId)
            ->first();

        $status = 'liked';

        if ($existingLike) {
            DB::table('jcow_liked')->where('id', $existingLike->id)->delete();
            $stream->decrement('likes');
            $status = 'unliked';
        } else {
            DB::table('jcow_liked')->insert([
                'uid' => $userId,
                'stream_id' => $streamId,
            ]);
            $stream->increment('likes');
        }

        // Ambil data terbaru
        $likesCount = DB::table('jcow_streams')->where('id', $streamId)->value('likes');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'likes' => $likesCount
            ]);
        }

        return back()->with('success_post', $status == 'liked' ? 'Menyukai postingan.' : 'Batal menyukai postingan.');
    }
}
