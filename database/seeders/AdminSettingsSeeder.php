<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'site_name' => 'X-CODE NETWORK',
            'slogan' => 'Advanced Infrastructure Management',
            'keywords' => 'x-code, network, cybersecurity, control panel, infrastructure, devops',
            'contact_email' => 'sysadmin@x-code.network',
            'footer_message' => '© 2024 X-CODE TECHNOLOGY NETWORK. ALL RIGHTS RESERVED.',
            
            'network_visiting' => 'Registered Members Only',
            'account_verification' => 'Email Verification Link',
            'pending_limit' => '5',
            
            'offline_mode' => '0',
            'offline_reason' => 'The X-CODE network is currently undergoing scheduled maintenance. Please check back later.',
            
            'locations' => json_encode(['Indonesia', 'USA', 'Japan', 'Australia', 'Austria']),
            
            'words_filter' => 'anjing,babi,bangsat,kontol,memek,ngentot',
            
            'max_miniblog_length' => '280',
            'recaptcha_signup' => '1',
            'recaptcha_login' => '0',
        ];

        foreach ($defaultSettings as $key => $value) {
            DB::table('jcow_gvars')->updateOrInsert(
                ['gkey' => $key],
                ['gvalue' => $value]
            );
        }
    }
}
