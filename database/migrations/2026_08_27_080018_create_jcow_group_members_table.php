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
        Schema::create('jcow_group_members', function (Blueprint $table) {
            $table->integer('gid');
            $table->integer('uid');
            $table->integer('created');
            $table->string('nickname', 20);
            $table->text('about_me');
            $table->tinyInteger('hide_profile');
            $table->index(['gid'], 'jcow_group_members_gid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_members');
    }
};
