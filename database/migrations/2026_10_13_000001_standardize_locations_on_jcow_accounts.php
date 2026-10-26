<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('jcow_accounts')
            ->where('location', 'Daerah Istimewa Yogyakarta')
            ->update(['location' => 'DI Yogyakarta']);

        DB::table('jcow_accounts')
            ->where('location', 'Daerah Khusus Ibukota Jakarta')
            ->update(['location' => 'DKI Jakarta']);

        DB::table('jcow_accounts')
            ->where('location', 'Nanggroe Aceh Darussalam')
            ->update(['location' => 'Aceh']);

        DB::table('jcow_accounts')
            ->where('location', 'Jawa Selatan')
            ->update(['location' => 'Jawa Barat']);
    }

    public function down(): void
    {
        DB::table('jcow_accounts')
            ->where('location', 'DI Yogyakarta')
            ->update(['location' => 'Daerah Istimewa Yogyakarta']);

        DB::table('jcow_accounts')
            ->where('location', 'DKI Jakarta')
            ->update(['location' => 'Daerah Khusus Ibukota Jakarta']);

        DB::table('jcow_accounts')
            ->where('location', 'Aceh')
            ->update(['location' => 'Nanggroe Aceh Darussalam']);

        DB::table('jcow_accounts')
            ->where('id', 838)
            ->where('location', 'Jawa Barat')
            ->update(['location' => 'Jawa Selatan']);
    }
};
