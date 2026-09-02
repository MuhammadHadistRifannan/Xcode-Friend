<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminBlockController extends Controller
{
    private function getTable()
    {
        return Schema::hasTable('jcow_settings') ? 'jcow_settings' : 'jcow_gvars';
    }

    private function getKeyName()
    {
        return Schema::hasTable('jcow_settings') ? 'setting_name' : 'gkey';
    }

    private function getValueName()
    {
        return Schema::hasTable('jcow_settings') ? 'setting_value' : 'gvalue';
    }

    public function index()
    {
        $table = $this->getTable();
        $keyName = $this->getKeyName();
        $valName = $this->getValueName();

        // Ambil konfigurasi blok dari database
        $configRecord = DB::table($table)->where($keyName, 'layout_blocks')->first();
        
        // Default blocks jika belum ada konfigurasi
        $blocks = [
            'left_column' => ['User Menu', 'Site Stats'],
            'center_column' => ['Main Feed', 'Recent Photos'],
            'right_column' => ['Sponsored Ads', 'Trending Topics', 'Suggested Friends'],
        ];

        if ($configRecord && !empty($configRecord->$valName)) {
            $blocks = json_decode($configRecord->$valName, true) ?? $blocks;
        }

        $availableBlocks = [
            'User Menu',
            'Site Stats',
            'Main Feed',
            'Recent Photos',
            'Sponsored Ads',
            'Trending Topics',
            'Suggested Friends',
            'Custom HTML',
            'Online Members',
            'Recent Blogs',
            'Recent Videos'
        ];

        return view('admin.themes.blocks', compact('blocks', 'availableBlocks'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'blocks' => 'required|array',
            'blocks.left_column' => 'nullable|array',
            'blocks.center_column' => 'nullable|array',
            'blocks.right_column' => 'nullable|array',
        ]);

        // Bersihkan array dari null
        $blocksData = [
            'left_column' => array_filter($request->blocks['left_column'] ?? []),
            'center_column' => array_filter($request->blocks['center_column'] ?? []),
            'right_column' => array_filter($request->blocks['right_column'] ?? []),
        ];

        $table = $this->getTable();
        $keyName = $this->getKeyName();
        $valName = $this->getValueName();

        DB::table($table)->updateOrInsert(
            [$keyName => 'layout_blocks'],
            [$valName => json_encode($blocksData)]
        );

        return back()->with('success', 'Susunan block berhasil disimpan.');
    }
}
