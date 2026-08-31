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

        $albums = Album::where('app', $type)
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
            'type' => 'required|in:photos,videos',
        ]);

        $album = Album::create([
            'gid'         => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description ?? '',
            'app'         => $request->type,
        ]);

        return response()->json([
            'id'   => $album->id,
            'name' => $album->name,
        ], 201);
    }
}
