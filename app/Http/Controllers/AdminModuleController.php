<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminModuleController extends Controller
{
    private $table = 'jcow_modules';

    public function index()
    {
        // Define base module information
        $community_modules_base = [
            ['name' => 'BLOGS', 'desc' => 'User-generated long-form editorial publishing system.', 'type' => 'COMM', 'version' => 'V2.4.1'],
            ['name' => 'EVENTS', 'desc' => 'Calendar coordination and user attendance tracking.', 'type' => 'COMM', 'version' => 'V1.1.0'],
            ['name' => 'FEED', 'desc' => 'Algorithmic timeline and chronological activity stream.', 'type' => 'COMM', 'version' => 'V3.0.2'],
            ['name' => 'PHOTOS', 'desc' => 'High-resolution image processing and gallery deployment.', 'type' => 'COMM', 'version' => 'V4.1.0'],
        ];

        $core_modules = [
            ['name' => 'ACCOUNT', 'desc' => 'User lifecycle, authentication, and security credentials.', 'type' => 'CORE', 'version' => 'V9.0.0'],
            ['name' => 'ADMIN CP', 'desc' => 'Global command center and architectural overrides.', 'type' => 'CORE', 'version' => 'V9.0.0'],
            ['name' => 'BLOCK / UNBLOCK', 'desc' => 'Inter-user access denial and restriction routing.', 'type' => 'CORE', 'version' => 'V4.2.1'],
        ];

        // Fetch active statuses from DB
        $dbModules = [];
        if (Schema::hasTable($this->table)) {
            $dbModules = DB::table($this->table)->pluck('actived', 'name')->toArray();
        }

        // Merge DB status with base definitions for Community Modules
        $community_modules = array_map(function ($module) use ($dbModules) {
            // Default active (1) if not found in DB
            $module['actived'] = isset($dbModules[$module['name']]) ? $dbModules[$module['name']] : 1;
            return $module;
        }, $community_modules_base);

        return view('admin.modules.index', compact('community_modules', 'core_modules'));
    }

    public function toggle(Request $request)
    {
        $modules = $request->input('modules', []);
        
        if (!Schema::hasTable($this->table)) {
            return back()->withErrors(['message' => 'Tabel jcow_modules tidak ditemukan.']);
        }

        // First, set all to 0
        DB::table($this->table)->update(['actived' => 0]);

        // Then update checked modules to 1, or insert if not exists
        foreach ($modules as $moduleName => $status) {
            if ($status == '1') {
                DB::table($this->table)->updateOrInsert(
                    ['name' => $moduleName],
                    ['actived' => 1]
                );
            }
        }

        return back()->with('success', 'Status modul berhasil diperbarui!');
    }
}
