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
        // 1. Clean up orphaned data first before applying constraints
        DB::statement('DELETE FROM jcow_streams WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_comments WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_friends WHERE uid NOT IN (SELECT id FROM jcow_accounts) OR fid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_followers WHERE uid NOT IN (SELECT id FROM jcow_accounts) OR fid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_group_members WHERE uid NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_messages WHERE to_id NOT IN (SELECT id FROM jcow_accounts)');
        DB::statement('DELETE FROM jcow_messages WHERE from_id NOT IN (SELECT id FROM jcow_accounts)');

        // 2. Add Foreign Keys
        Schema::table('jcow_streams', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_comments', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_friends', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
            $table->foreign('fid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_followers', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
            $table->foreign('fid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_group_members', function (Blueprint $table) {
            $table->foreign('uid')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });

        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->foreign('to_id')->references('id')->on('jcow_accounts')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('jcow_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcow_streams', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_comments', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_friends', function (Blueprint $table) {
            $table->dropForeign(['uid']);
            $table->dropForeign(['fid']);
        });

        Schema::table('jcow_followers', function (Blueprint $table) {
            $table->dropForeign(['uid']);
            $table->dropForeign(['fid']);
        });

        Schema::table('jcow_group_members', function (Blueprint $table) {
            $table->dropForeign(['uid']);
        });

        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->dropForeign(['uid']);
            $table->dropForeign(['from_uid']);
        });
    }
};
