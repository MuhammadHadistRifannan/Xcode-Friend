<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSettingsController extends Controller
{
    // Tabel yang akan digunakan. Default ke jcow_gvars, jika jcow_settings ada gunakan itu
    private $table = 'jcow_gvars';
    private $keyColumn = 'gkey';
    private $valueColumn = 'gvalue';

    public function __construct()
    {
        if (Schema::hasTable('jcow_settings')) {
            $this->table = 'jcow_settings';
            $this->keyColumn = 'key';
            $this->valueColumn = 'value';
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
            'rules_conditions' => $textData['rules_conditions'] ?? "- Dilarang keras melakukan pelecehan SARA \n- Dilarang keras mengirim SPAM\n- Dilarang keras mengisi foto dengan gambar yang mengandung pornografi atau pornoaksi\n- Setiap tindakan yang dilakukan member di jejaring sosial X-code adalah tanggung jawab pribadi",
            
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
            $inputs['locations'] = json_encode(array_values($inputs['locations']));
        } else {
            $inputs['locations'] = json_encode([]);
        }

        // Separate texts to save in jcow_texts (use array_key_exists so null values are caught)
        $textKeys = ['footermsg', 'locations', 'words_filter', 'rules_conditions'];
        $texts = [];
        foreach ($textKeys as $key) {
            if (array_key_exists($key, $inputs)) {
                $texts[$key] = $inputs[$key] ?? '';
                unset($inputs[$key]);
            }
        }

        DB::transaction(function () use ($inputs, $texts) {
            // Loop dan update atau insert ke tabel setting (jcow_gvars / jcow_settings)
            foreach ($inputs as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $val = $value !== null ? (string) $value : '';

                DB::table($this->table)->updateOrInsert(
                    [$this->keyColumn => $key],
                    [$this->valueColumn => $val]
                );
            }

            // Loop dan update texts
            foreach ($texts as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $val = $value !== null ? (string) $value : '';

                DB::table('jcow_texts')->updateOrInsert(
                    ['tkey' => $key],
                    ['tvalue' => $val]
                );
            }
        });

        // Clear the settings cache
        \App\Helpers\SettingHelper::clearCache();

        return back()->with('success', 'Pengaturan situs berhasil diperbarui!');
    }
}
