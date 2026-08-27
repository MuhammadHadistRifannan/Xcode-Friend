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
        Schema::create('jcow_group_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('gid')->default('0');
            $table->integer('old_fid');
            $table->integer('pid');
            $table->integer('uid')->default('0');
            $table->string('username', 50);
            $table->string('topic', 255);
            $table->integer('views')->default('0');
            $table->integer('posts')->default('0');
            $table->smallInteger('closed')->default('0');
            $table->integer('created')->default('0');
            $table->string('lastpostusername', 255)->default('0');
            $table->integer('lastpostcreated')->default('0');
            $table->tinyInteger('icon')->default('0');
            $table->tinyInteger('thread_type')->default('0');
            $table->tinyInteger('thread_lock')->default('0');
            $table->tinyInteger('got_poll')->default('0');
            $table->tinyInteger('got_attach');
            $table->tinyInteger('stressed')->default('0');
            $table->integer('digg')->default('0');
            $table->integer('dugg')->default('0');
            $table->text('votes');
            $table->index(['gid'], 'jcow_group_topics_gid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_topics');
    }
};
