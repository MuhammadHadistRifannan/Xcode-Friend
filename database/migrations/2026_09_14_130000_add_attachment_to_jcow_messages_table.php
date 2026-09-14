<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('jcow_messages', 'attachment')) {
            Schema::table('jcow_messages', function (Blueprint $table) {
                $table->string('attachment', 255)->nullable()->after('message');
            });
        }

        if (!Schema::hasColumn('jcow_messages_sent', 'attachment')) {
            Schema::table('jcow_messages_sent', function (Blueprint $table) {
                $table->string('attachment', 255)->nullable()->after('message');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jcow_messages', 'attachment')) {
            Schema::table('jcow_messages', function (Blueprint $table) {
                $table->dropColumn('attachment');
            });
        }

        if (Schema::hasColumn('jcow_messages_sent', 'attachment')) {
            Schema::table('jcow_messages_sent', function (Blueprint $table) {
                $table->dropColumn('attachment');
            });
        }
    }
};
