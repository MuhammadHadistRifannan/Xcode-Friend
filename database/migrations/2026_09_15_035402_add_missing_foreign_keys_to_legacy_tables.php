<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Clean up orphaned data
        DB::statement('DELETE FROM jcow_liked WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_reports WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_profile_comments WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_friend_reqs WHERE uid NOT IN (SELECT id FROM jcow_accounts) OR fid NOT IN (SELECT id FROM jcow_accounts)');

        // 2. Add Foreign Keys
        Schema::table('jcow_liked', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_reports', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_profile_comments', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_friend_reqs', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
            $table->foreign('fid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcow_liked', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_reports', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_profile_comments', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_friend_reqs', function (Blueprint $table) {
            $table->dropForeign(['uid']);
            $table->dropForeign(['fid']);
        });
    }
};
