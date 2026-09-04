<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('jcow_messages', 'reply_to')) return;

        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to')->nullable()->after('hasread');
        });

        Schema::table('jcow_messages_sent', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to')->nullable()->after('hasread');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('jcow_messages', 'reply_to')) return;

        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->dropColumn('reply_to');
        });

        Schema::table('jcow_messages_sent', function (Blueprint $table) {
            $table->dropColumn('reply_to');
        });
    }
};
