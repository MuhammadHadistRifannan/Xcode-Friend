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
        Schema::table('jcow_streams', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_comments', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_friends', function (Blueprint $table) { $table->dropForeign(['uid']); $table->dropForeign(['fid']); });
        Schema::table('jcow_followers', function (Blueprint $table) { $table->dropForeign(['uid']); $table->dropForeign(['fid']); });
        Schema::table('jcow_group_members', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_messages', function (Blueprint $table) { $table->dropForeign(['to_id']); }); // from_id already dropped
        
        Schema::table('jcow_liked', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_reports', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_profile_comments', function (Blueprint $table) { $table->dropForeign(['uid']); });
        Schema::table('jcow_friend_reqs', function (Blueprint $table) { $table->dropForeign(['uid']); $table->dropForeign(['fid']); });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not adding them back automatically to prevent future 500 errors
    }
};
