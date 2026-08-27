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
        Schema::create('jcow_group_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('gid');
            $table->bigInteger('tid')->default('0');
            $table->integer('uid')->default('0');
            $table->string('username', 50);
            $table->integer('rtid');
            $table->integer('rid');
            $table->string('rname', 100);
            $table->text('message');
            $table->integer('created')->default('0');
            $table->string('ip', 30)->default('');
            $table->integer('attach')->default('0');
            $table->tinyInteger('bbcode_off')->default('0');
            $table->tinyInteger('emote_off')->default('0');
            $table->tinyInteger('got_attach');
            $table->string('topic', 100);
            $table->tinyInteger('is_first');
            $table->integer('replies');
            $table->index(['tid'], 'jcow_group_posts_tid');
            $table->index(['uid'], 'jcow_group_posts_uid');
            $table->index(['gid'], 'jcow_group_posts_gid');
            $table->index(['rtid'], 'jcow_group_posts_rtid');
            $table->index(['rid'], 'jcow_group_posts_rid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_posts');
    }
};
