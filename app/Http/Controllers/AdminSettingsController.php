<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSettingsController extends Controller
{
    // Tabel yang akan digunakan. Coba jcow_settings, jika tidak ada fallback ke jcow_gvars
    private $table = 'jcow_settings';
    private $keyColumn = 'key';
    private $valueColumn = 'value';

    public function __construct()
    {
        // Cek jika tabel jcow_settings tidak ada, tapi jcow_gvars ada, kita asumsikan user maksudnya jcow_gvars
        if (!Schema::hasTable('jcow_settings') && Schema::hasTable('jcow_gvars')) {
            $this->table = 'jcow_gvars';
            $this->keyColumn = 'gkey';
            $this->valueColumn = 'gvalue';
        }
    }

    public function index()
    {
        // Ambil semua pengaturan sebagai associative array
        $settingsData = DB::table($this->table)->pluck($this->valueColumn, $this->keyColumn)->toArray();
        $textData = DB::table('jcow_texts')->pluck('tvalue', 'tkey')->toArray();

        // Siapkan default value jika belum ada di database
        $settings = [
            'site_name' => $settingsData['site_name'] ?? 'X-CODE NETWORK',
            'site_slogan' => $settingsData['site_slogan'] ?? 'Advanced Infrastructure Management',
            'site_keywords' => $settingsData['site_keywords'] ?? 'x-code, network, cybersecurity, control panel, infrastructure, devops',
            'site_email' => $settingsData['site_email'] ?? 'sysadmin@x-code.network',
            'footermsg' => $textData['footermsg'] ?? '© 2024 X-CODE TECHNOLOGY NETWORK. ALL RIGHTS RESERVED.',
            
            'private_network' => $settingsData['private_network'] ?? '0', // 1: Registered Members Only, 0: Public
            'acc_verify' => $settingsData['acc_verify'] ?? '1', // 0: Auto, 1: Email, 2: Admin
            'pending_post_limit' => $settingsData['pending_post_limit'] ?? '5',
            
            'offline' => $settingsData['offline'] ?? '0',
            'offline_reason' => $settingsData['offline_reason'] ?? 'The X-CODE network is currently undergoing scheduled maintenance. Please check back later.',
            
            'locations' => isset($textData['locations']) ? json_decode($textData['locations'], true) : ['Indonesia', 'USA', 'Japan', 'Australia', 'Austria'],
            
            'words_filter' => $textData['words_filter'] ?? 'anjing,babi,bangsat,kontol,memek,ngentot',
            
            'miniblog_maximum' => $settingsData['miniblog_maximum'] ?? '280',
            'disable_recaptcha_reg' => $settingsData['disable_recaptcha_reg'] ?? '0', // 0: Enable, 1: Disable
            'disable_recaptcha_login' => $settingsData['disable_recaptcha_login'] ?? '1',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        // Handle checkboxes that might not be sent if unchecked
        $inputs['offline'] = $request->has('offline') ? '1' : '0';
        $inputs['disable_recaptcha_reg'] = $request->has('enable_recaptcha_reg') ? '0' : '1';
        $inputs['disable_recaptcha_login'] = $request->has('enable_recaptcha_login') ? '0' : '1';
        
        // Remove enable variables from inputs so they aren't saved
        unset($inputs['enable_recaptcha_reg'], $inputs['enable_recaptcha_login']);

        // Handle locations array -> json
        if (isset($inputs['locations']) && is_array($inputs['locations'])) {
            $inputs['locations'] = json_encode($inputs['locations']);
        } else {
            $inputs['locations'] = json_encode([]);
        }

        // Separate texts to save in jcow_texts
        $textKeys = ['footermsg', 'locations', 'words_filter'];
        $texts = [];
        foreach ($textKeys as $key) {
            if (isset($inputs[$key])) {
                $texts[$key] = $inputs[$key];
                unset($inputs[$key]);
            }
        }

        // Loop dan update atau insert ke tabel setting
        foreach ($inputs as $key => $value) {
            DB::table($this->table)->updateOrInsert(
                [$this->keyColumn => $key],
                [$this->valueColumn => $value]
            );
        }

        // Loop dan update texts
        foreach ($texts as $key => $value) {
            DB::table('jcow_texts')->updateOrInsert(
                ['tkey' => $key],
                ['tvalue' => $value]
            );
        }

        // Clear the settings cache
        \App\Helpers\SettingHelper::clearCache();

        return back()->with('success', 'Pengaturan situs berhasil diperbarui!');
    }
}
