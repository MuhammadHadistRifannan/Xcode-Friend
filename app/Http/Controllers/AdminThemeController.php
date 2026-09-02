<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminThemeController extends Controller
{
    public function index()
    {
        // Dalam Jcow, pengaturan tema aktif biasa disimpan di jcow_settings atau jcow_gvars
        $activeTheme = 'System Standard Theme';
        
        // Cari tema aktif dari database jika tabel jcow_settings tersedia
        if (Schema::hasTable('jcow_settings')) {
            $setting = DB::table('jcow_settings')->where('setting_name', 'theme')->first();
            if ($setting && !empty($setting->setting_value)) {
                $activeTheme = $setting->setting_value;
            }
        } elseif (Schema::hasTable('jcow_gvars')) {
            $gvar = DB::table('jcow_gvars')->where('gkey', 'theme')->first();
            if ($gvar && !empty($gvar->gvalue)) {
                $activeTheme = $gvar->gvalue;
            }
        }

        return view('admin.themes.index', compact('activeTheme'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|max:255'
        ]);

        // Simpan tema aktif ke database
        if (Schema::hasTable('jcow_settings')) {
            DB::table('jcow_settings')->updateOrInsert(
                ['setting_name' => 'theme'],
                ['setting_value' => $request->theme]
            );
        } elseif (Schema::hasTable('jcow_gvars')) {
            DB::table('jcow_gvars')->updateOrInsert(
                ['gkey' => 'theme'],
                ['gvalue' => $request->theme]
            );
        }

        return back()->with('success', 'Tema berhasil diperbarui.');
    }
}
