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
        Schema::create('jcow_group_members_pending', function (Blueprint $table) {
            $table->integer('uid');
            $table->integer('gid');
            $table->integer('created');
            $table->tinyInteger('ignored');
            $table->index(['uid', 'gid'], 'jcow_group_members_pending_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_members_pending');
    }
};
