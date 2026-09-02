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

        // Siapkan default value jika belum ada di database
        $settings = [
            'site_name' => $settingsData['site_name'] ?? 'X-CODE NETWORK',
            'slogan' => $settingsData['slogan'] ?? 'Advanced Infrastructure Management',
            'keywords' => $settingsData['keywords'] ?? 'x-code, network, cybersecurity, control panel, infrastructure, devops',
            'contact_email' => $settingsData['contact_email'] ?? 'sysadmin@x-code.network',
            'footer_message' => $settingsData['footer_message'] ?? '© 2024 X-CODE TECHNOLOGY NETWORK. ALL RIGHTS RESERVED.',
            
            'network_visiting' => $settingsData['network_visiting'] ?? 'Registered Members Only',
            'account_verification' => $settingsData['account_verification'] ?? 'Email Verification Link',
            'pending_limit' => $settingsData['pending_limit'] ?? '5',
            
            'offline_mode' => $settingsData['offline_mode'] ?? '0',
            'offline_reason' => $settingsData['offline_reason'] ?? 'The X-CODE network is currently undergoing scheduled maintenance. Please check back later.',
            
            'locations' => isset($settingsData['locations']) ? json_decode($settingsData['locations'], true) : ['Indonesia', 'USA', 'Japan', 'Australia', 'Austria'],
            
            'max_miniblog_length' => $settingsData['max_miniblog_length'] ?? '280',
            'recaptcha_signup' => $settingsData['recaptcha_signup'] ?? '1',
            'recaptcha_login' => $settingsData['recaptcha_login'] ?? '0',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        // Handle checkboxes that might not be sent if unchecked
        $inputs['offline_mode'] = $request->has('offline_mode') ? '1' : '0';
        $inputs['recaptcha_signup'] = $request->has('recaptcha_signup') ? '1' : '0';
        $inputs['recaptcha_login'] = $request->has('recaptcha_login') ? '1' : '0';
        
        // Handle locations array -> json
        if (isset($inputs['locations']) && is_array($inputs['locations'])) {
            $inputs['locations'] = json_encode($inputs['locations']);
        } else {
            $inputs['locations'] = json_encode([]);
        }

        // Loop dan update atau insert ke tabel setting
        foreach ($inputs as $key => $value) {
            DB::table($this->table)->updateOrInsert(
                [$this->keyColumn => $key],
                [$this->valueColumn => $value]
            );
        }

        return back()->with('success', 'Pengaturan situs berhasil diperbarui!');
    }
}
