<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminThemeController extends Controller
{
    public function index()
    {
        $activeTheme = 'red'; // Default to red theme
        
        if (Schema::hasTable('jcow_gvars')) {
            $gvar = DB::table('jcow_gvars')->where('gkey', 'theme_color')->first();
            if ($gvar && !empty($gvar->gvalue)) {
                $activeTheme = $gvar->gvalue;
            }
        }

        $availableThemes = [
            'red' => 'Red Theme (Default)',
            'blue' => 'Blue Theme',
            'dark' => 'Dark Mode',
        ];

        return view('admin.themes.index', compact('activeTheme', 'availableThemes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_color' => 'required|in:red,blue,dark'
        ]);

        if (Schema::hasTable('jcow_gvars')) {
            DB::table('jcow_gvars')->updateOrInsert(
                ['gkey' => 'theme_color'],
                ['gvalue' => $request->theme_color]
            );
        }

        // Clear cache if we want to cache the theme later (we can use SettingHelper)
        \Illuminate\Support\Facades\Cache::forget('jcow_setting_theme_color');

        return back()->with('success', 'Tema warna berhasil diperbarui.');
    }
}
