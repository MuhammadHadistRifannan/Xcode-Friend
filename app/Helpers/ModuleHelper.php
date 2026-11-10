<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ModuleHelper
{
    /**
     * Module alias mappings (singular to plural, or legacy names)
     */
    protected static array $aliases = [
        'VIDEO'       => 'VIDEOS',
        'PHOTO'       => 'PHOTOS',
        'PAGE'        => 'PAGES',
        'GROUP'       => 'GROUPS',
        'BLOG'        => 'BLOGS',
        'EVENT'       => 'EVENTS',
        'EVENTS2005'  => 'EVENTS',
        'CHAT'        => 'XCODECHAT',
        'CHATBAR'     => 'XCODECHAT',
        'FORUM'       => 'XCODEFORUM',
        'GAME'        => 'DEGAMES',
        'GAMES'       => 'DEGAMES',
        'TELUSUR'     => 'BROWSE',
    ];

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
        $normalized = strtoupper(trim($moduleName));
        if (isset(self::$aliases[$normalized])) {
            $normalized = self::$aliases[$normalized];
        }
        
        return Cache::rememberForever('jcow_module_' . $normalized, function () use ($normalized, $default) {
            try {
                if (!Schema::hasTable('jcow_modules')) {
                    return $default;
                }
                
                // Collect possible DB names to check
                $searchNames = [$normalized];
                if ($normalized === 'EVENTS') {
                    $searchNames[] = 'EVENTS2005';
                }
                if (str_ends_with($normalized, 'S')) {
                    $searchNames[] = substr($normalized, 0, -1);
                } else {
                    $searchNames[] = $normalized . 'S';
                }

                $module = DB::table('jcow_modules')
                    ->whereIn(DB::raw('UPPER(name)'), $searchNames)
                    ->orderBy('actived', 'asc')
                    ->first();
                
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
        Cache::flush();

        foreach (array_unique(array_values(self::$aliases)) as $canonical) {
            Cache::forget('jcow_module_' . $canonical);
        }
    }
}
