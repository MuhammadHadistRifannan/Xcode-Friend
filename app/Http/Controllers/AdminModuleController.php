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
            ['name' => 'VIDEOS', 'desc' => 'Video uploading, embedding and playback system.', 'type' => 'COMM', 'version' => 'V2.1.0'],
            ['name' => 'GROUPS', 'desc' => 'Community groups and collective discussions.', 'type' => 'COMM', 'version' => 'V1.5.0'],
            ['name' => 'PAGES', 'desc' => 'Official fan pages and organizational profiles.', 'type' => 'COMM', 'version' => 'V2.0.0'],
            ['name' => 'MUSIC', 'desc' => 'Audio streaming and playlist management.', 'type' => 'COMM', 'version' => 'V1.0.1'],
            ['name' => 'XCODECHAT', 'desc' => 'Real-time instant messaging and chatbar.', 'type' => 'COMM', 'version' => 'V3.2.0'],
            ['name' => 'XCODEFORUM', 'desc' => 'Structured threaded discussion boards.', 'type' => 'COMM', 'version' => 'V2.5.0'],
            ['name' => 'DEGAMES', 'desc' => 'Interactive casual games platform.', 'type' => 'COMM', 'version' => 'V1.2.0'],
        ];

        $core_modules = [
            ['name' => 'ACCOUNT', 'desc' => 'User lifecycle, authentication, and security credentials.', 'type' => 'CORE', 'version' => 'V9.0.0'],
            ['name' => 'ADMIN CP', 'desc' => 'Global command center and architectural overrides.', 'type' => 'CORE', 'version' => 'V9.0.0'],
            ['name' => 'BLOCK / UNBLOCK', 'desc' => 'Inter-user access denial and restriction routing.', 'type' => 'CORE', 'version' => 'V4.2.1'],
            ['name' => 'BROWSE', 'desc' => 'Member directory search and filtering engine.', 'type' => 'CORE', 'version' => 'V2.0.0'],
            ['name' => 'SEARCH', 'desc' => 'Global search across all active modules.', 'type' => 'CORE', 'version' => 'V1.5.0'],
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
        $checkedModules = $request->input('modules', []);
        
        if (!Schema::hasTable($this->table)) {
            return back()->withErrors(['message' => 'Tabel jcow_modules tidak ditemukan.']);
        }

        // All community modules available to toggle
        $allModules = ['BLOGS', 'EVENTS', 'FEED', 'PHOTOS', 'VIDEOS', 'GROUPS', 'PAGES', 'MUSIC', 'XCODECHAT', 'XCODEFORUM', 'DEGAMES'];

        // Loop through all defined modules and explicitly insert or update their status
        foreach ($allModules as $moduleName) {
            $isActive = isset($checkedModules[$moduleName]) && $checkedModules[$moduleName] == '1' ? 1 : 0;
            
            DB::table($this->table)->updateOrInsert(
                ['name' => $moduleName],
                ['actived' => $isActive]
            );
        }

        // Clear module cache
        if (class_exists(\App\Helpers\ModuleHelper::class)) {
            \App\Helpers\ModuleHelper::clearCache();
        }

        return back()->with('success', 'Status modul berhasil diperbarui!');
    }
}
