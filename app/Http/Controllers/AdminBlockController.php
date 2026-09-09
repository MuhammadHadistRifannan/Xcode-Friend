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
        // Get raw HTML blocks from jcow_gvars
        $blocks = [
            'header_code' => \App\Helpers\SettingHelper::get('theme_block_header_code', ''),
            'footer_code' => \App\Helpers\SettingHelper::get('theme_block_footer_code', ''),
            'left_column' => \App\Helpers\SettingHelper::get('theme_block_left_column', ''),
            'right_column' => \App\Helpers\SettingHelper::get('theme_block_right_column', ''),
            'center_column' => \App\Helpers\SettingHelper::get('theme_block_center_column', ''),
        ];

        return view('admin.themes.blocks', compact('blocks'));
    }

    public function update(Request $request)
    {
        $table = Schema::hasTable('jcow_gvars') ? 'jcow_gvars' : 'jcow_settings';
        $keyName = Schema::hasTable('jcow_gvars') ? 'gkey' : 'setting_name';
        $valName = Schema::hasTable('jcow_gvars') ? 'gvalue' : 'setting_value';

        $blocksToSave = [
            'theme_block_header_code' => $request->input('header_code') ?? '',
            'theme_block_footer_code' => $request->input('footer_code') ?? '',
            'theme_block_left_column' => $request->input('left_column') ?? '',
            'theme_block_right_column' => $request->input('right_column') ?? '',
            'theme_block_center_column' => $request->input('center_column') ?? '',
        ];

        foreach ($blocksToSave as $key => $html) {
            DB::table($table)->updateOrInsert(
                [$keyName => $key],
                [$valName => $html ?? '']
            );
            \Illuminate\Support\Facades\Cache::forget('jcow_setting_' . $key);
        }

        return back()->with('success', 'Kode HTML Blocks berhasil disimpan.');
    }
}
