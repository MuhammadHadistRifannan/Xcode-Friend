<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Search album milik user (untuk dropdown AJAX).
     * GET /api/albums?q=keyword&type=photos|videos
     */
    public function search(Request $request)
    {
        $uid  = auth()->id();
        $q    = $request->get('q', '');
        $type = $request->get('type', 'photos'); // photos atau videos

        if (in_array($type, ['video', 'videos']) && !\App\Helpers\ModuleHelper::isActive('VIDEOS')) {
            return response()->json([]);
        }
        if (in_array($type, ['photo', 'photos']) && !\App\Helpers\ModuleHelper::isActive('PHOTOS')) {
            return response()->json([]);
        }

        $apps = in_array($type, ['video', 'videos']) ? ['video', 'videos'] : ['photos', 'photo'];

        $albums = Album::whereIn('app', $apps)
            ->where('gid', $uid)
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        return response()->json($albums);
    }

    /**
     * Buat album baru.
     * POST /api/albums
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:photos,photo,video,videos',
        ]);

        if (in_array($request->type, ['video', 'videos']) && !\App\Helpers\ModuleHelper::isActive('VIDEOS')) {
            abort(404, 'Modul video sedang dinonaktifkan.');
        }
        if (in_array($request->type, ['photos', 'photo']) && !\App\Helpers\ModuleHelper::isActive('PHOTOS')) {
            abort(404, 'Modul foto sedang dinonaktifkan.');
        }

        $appType = in_array($request->type, ['video', 'videos']) ? 'video' : 'photos';

        $album = Album::create([
            'gid'         => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description ?? '',
            'app'         => $appType,
            'var1'        => $request->privacy ?? 'public', // Menyimpan privasi di var1
        ]);

        return response()->json([
            'success' => true,
            'album'   => [
                'id'   => $album->id,
                'name' => $album->name,
            ]
        ], 201);
    }
}
