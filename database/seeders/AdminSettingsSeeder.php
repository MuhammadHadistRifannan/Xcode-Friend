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
        // Pengaturan skalar / sistem disimpan di jcow_gvars
        $defaultGvars = [
            'site_name'               => 'X-CODE NETWORK',
            'site_slogan'             => 'Advanced Infrastructure Management',
            'site_keywords'           => 'x-code, network, cybersecurity, control panel, infrastructure, devops',
            'site_email'              => 'sysadmin@x-code.network',
            'private_network'         => '0', // 0: Public, 1: Registered Members Only
            'acc_verify'              => '1', // 0: Auto, 1: Email, 2: Admin
            'pending_post_limit'      => '5',
            'offline'                 => '0',
            'offline_reason'          => 'The X-CODE network is currently undergoing scheduled maintenance. Please check back later.',
            'miniblog_maximum'        => '280',
            'disable_recaptcha_reg'   => '0', // 0: Enable, 1: Disable
            'disable_recaptcha_login' => '1',
        ];

        foreach ($defaultGvars as $key => $value) {
            DB::table('jcow_gvars')->updateOrInsert(
                ['gkey' => $key],
                ['gvalue' => $value]
            );
        }

        // Pengaturan teks panjang / konten disimpan di jcow_texts
        $defaultTexts = [
            'footermsg'        => '© 2024 X-CODE TECHNOLOGY NETWORK. ALL RIGHTS RESERVED.',
            'rules_conditions' => "- Dilarang keras melakukan pelecehan SARA \n- Dilarang keras mengirim SPAM\n- Dilarang keras mengisi foto dengan gambar yang mengandung pornografi atau pornoaksi\n- Setiap tindakan yang dilakukan member di jejaring sosial X-code adalah tanggung jawab pribadi",
            'locations'        => json_encode(['Indonesia', 'USA', 'Japan', 'Australia', 'Austria']),
            'words_filter'     => 'anjing,babi,bangsat,kontol,memek,ngentot',
        ];

        foreach ($defaultTexts as $key => $value) {
            DB::table('jcow_texts')->updateOrInsert(
                ['tkey' => $key],
                ['tvalue' => $value]
            );
        }
    }
}
