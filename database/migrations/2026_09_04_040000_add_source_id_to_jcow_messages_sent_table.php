<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jcow_messages_sent', function (Blueprint $table) {
            if (!Schema::hasColumn('jcow_messages_sent', 'source_id')) {
                $table->bigInteger('source_id')->default(0)->after('id');
                $table->index('source_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jcow_messages_sent', function (Blueprint $table) {
            if (Schema::hasColumn('jcow_messages_sent', 'source_id')) {
                $table->dropIndex(['source_id']);
                $table->dropColumn('source_id');
            }
        });
    }
};
