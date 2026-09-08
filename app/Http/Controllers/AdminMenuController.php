<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMenuController extends Controller
{
    public function index()
    {
        $community_menu = DB::table('jcow_menu')->where('type', 'community')->orderBy('weight')->get();
        $personal_menu  = DB::table('jcow_menu')->where('type', 'personal')->orderBy('weight')->get();
        
        return view('admin.menu', compact('community_menu', 'personal_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'path' => 'required|string|max:255',
            'type' => 'required|in:community,personal',
            'weight' => 'nullable|integer',
            'actived' => 'nullable|boolean'
        ]);

        DB::table('jcow_menu')->insert([
            'name' => $request->name,
            'path' => $request->path,
            'type' => $request->type,
            'weight' => $request->weight ?? 10,
            'actived' => $request->actived ? 1 : 0,
            'tab_name' => '',
            'app' => '',
            'protected' => 0,
            'allowed_roles' => '',
            'icon' => '',
            'parent' => 0
        ]);

        return back()->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function updateBulk(Request $request)
    {
        $request->validate([
            'items' => 'nullable|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.path' => 'required|string|max:255',
            'items.*.weight' => 'required|integer',
            'items.*.actived' => 'nullable|boolean'
        ]);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $id => $data) {
                DB::table('jcow_menu')
                    ->where('id', $id)
                    ->update([
                        'name' => $data['name'],
                        'path' => $data['path'],
                        'weight' => $data['weight'],
                        'actived' => isset($data['actived']) ? 1 : 0
                    ]);
            }
        }

        return back()->with('success', 'Perubahan menu berhasil disimpan.');
    }

    public function destroy($id)
    {
        // Cegah penghapusan menu inti jika ada, tapi karena Jcow flexibel, kita izinkan
        DB::table('jcow_menu')->where('id', $id)->delete();
        
        return back()->with('success', 'Menu berhasil dihapus.');
    }
}
