<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stream;

class AdminStreamController extends Controller
{
    public function index(Request $request)
    {
        $query = Stream::with(['user', 'targetPage', 'targetWallUser']);

        if ($request->input('status') === 'hidden') {
            $query->where('hide', 1);
        } else {
            $query->where('hide', 0);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('attachment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%")
                         ->orWhere('fullname', 'like', "%{$search}%");
                  });
            });
        }

        if ($type = $request->input('type')) {
            if ($type === '1' || $type === 'text') {
                $query->where(function ($q) {
                    $q->where('type', 1)
                      ->orWhere(function ($q2) {
                          $q2->where('type', 0)
                             ->where(function ($q3) {
                                 $q3->whereNull('attachment')->orWhere('attachment', '');
                             })
                             ->whereNotIn('app', ['photo', 'photos', 'video', 'videos', 'blogs']);
                      });
                });
            } elseif ($type === '2' || $type === 'photo') {
                $query->where(function ($q) {
                    $q->where('type', 2)
                      ->orWhereIn('app', ['photo', 'photos'])
                      ->orWhere(function ($q2) {
                          $q2->where('attachment', 'like', '%"photos"%')
                             ->orWhere('attachment', 'like', '%"photo"%')
                             ->orWhere('attachment', 'like', '%s:6:"photos"%')
                             ->orWhere('attachment', 'like', '%s:5:"thumb"%');
                      });
                });
            } elseif ($type === '3' || $type === 'video') {
                $query->where(function ($q) {
                    $q->where('type', 3)
                      ->orWhereIn('app', ['video', 'videos'])
                      ->orWhere('attachment', 'like', 'youtube:%')
                      ->orWhere('attachment', 'like', '%"video_url"%');
                });
            } elseif ($type === 'blog') {
                $query->where('app', 'blogs');
            }
        }

        $streams = $query->orderBy('created', 'desc')->paginate(25)->withQueryString();

        $typeStats = [
            'all'   => Stream::where('hide', 0)->count(),
            'text'  => Stream::where('hide', 0)->where(function ($q) {
                $q->where('type', 1)
                  ->orWhere(function ($q2) {
                      $q2->where('type', 0)
                         ->where(function ($q3) {
                             $q3->whereNull('attachment')->orWhere('attachment', '');
                         })
                         ->whereNotIn('app', ['photo', 'photos', 'video', 'videos', 'blogs']);
                  });
            })->count(),
            'photo' => Stream::where('hide', 0)->where(function ($q) {
                $q->where('type', 2)
                  ->orWhereIn('app', ['photo', 'photos'])
                  ->orWhere(function ($q2) {
                      $q2->where('attachment', 'like', '%"photos"%')
                         ->orWhere('attachment', 'like', '%"photo"%')
                         ->orWhere('attachment', 'like', '%s:6:"photos"%')
                         ->orWhere('attachment', 'like', '%s:5:"thumb"%');
                  });
            })->count(),
            'video' => Stream::where('hide', 0)->where(function ($q) {
                $q->where('type', 3)
                  ->orWhereIn('app', ['video', 'videos'])
                  ->orWhere('attachment', 'like', 'youtube:%')
                  ->orWhere('attachment', 'like', '%"video_url"%');
            })->count(),
            'blog'  => Stream::where('hide', 0)->where('app', 'blogs')->count(),
        ];
        $typeStats['1'] = $typeStats['text'];
        $typeStats['2'] = $typeStats['photo'];
        $typeStats['3'] = $typeStats['video'];

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
