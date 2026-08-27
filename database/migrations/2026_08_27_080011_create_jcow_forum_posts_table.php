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
        Schema::create('jcow_forum_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tid')->default('0');
            $table->integer('uid')->default('0');
            $table->string('title', 255);
            $table->text('message');
            $table->integer('created')->default('0');
            $table->string('ip', 30)->default('');
            $table->tinyInteger('is_first')->default('0');
            $table->integer('attach')->default('0');
            $table->tinyInteger('bbcode_off')->default('0');
            $table->tinyInteger('emote_off')->default('0');
            $table->tinyInteger('got_attach');
            $table->index(['tid'], 'jcow_forum_posts_tid');
            $table->index(['uid'], 'jcow_forum_posts_author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_forum_posts');
    }
};
