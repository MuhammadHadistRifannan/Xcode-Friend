<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('jcow_accounts', 'last_seen')) return;

        Schema::table('jcow_accounts', function (Blueprint $table) {
            $table->integer('last_seen')->default(0)->after('lastlogin');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('jcow_accounts', 'last_seen')) return;

        Schema::table('jcow_accounts', function (Blueprint $table) {
            $table->dropColumn('last_seen');
        });
    }
};
