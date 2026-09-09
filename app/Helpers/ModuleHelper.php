<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ModuleHelper
{
    /**
     * Check if a module is active.
     * Core modules (like ADMIN) or non-existent in DB will default to true for safety,
     * unless explicitly set to 0.
     * 
     * @param string $moduleName
     * @param bool $default
     * @return bool
     */
    public static function isActive($moduleName, $default = true)
    {
        $moduleName = strtoupper($moduleName);
        
        return Cache::rememberForever('jcow_module_' . $moduleName, function () use ($moduleName, $default) {
            try {
                if (!Schema::hasTable('jcow_modules')) {
                    return $default;
                }
                
                $module = DB::table('jcow_modules')->where('name', $moduleName)->first();
                
                if ($module) {
                    return (bool) $module->actived;
                }
                
                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    /**
     * Clear module cache
     */
    public static function clearCache()
    {
        // Simple wipe out if using file driver.
        // For production with redis, tag-based clearing is better, but this is fine.
        Cache::flush();
    }
}
