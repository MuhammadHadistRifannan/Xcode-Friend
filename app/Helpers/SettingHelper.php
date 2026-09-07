<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    /**
     * Get a setting from jcow_gvars table with caching
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever('jcow_setting_' . $key, function () use ($key, $default) {
            try {
                $value = DB::table('jcow_gvars')->where('gkey', $key)->value('gvalue');
                return $value !== null ? $value : $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    /**
     * Clear the cache for a specific setting or all settings
     */
    public static function clearCache($key = null)
    {
        if ($key) {
            Cache::forget('jcow_setting_' . $key);
        } else {
            // Need to clear all - usually better to use Cache::flush() or tag-based caching
            Cache::flush();
        }
    }
}
