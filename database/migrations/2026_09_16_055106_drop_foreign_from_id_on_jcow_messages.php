<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->dropForeign(['from_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->foreign('from_id')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });
    }
};
