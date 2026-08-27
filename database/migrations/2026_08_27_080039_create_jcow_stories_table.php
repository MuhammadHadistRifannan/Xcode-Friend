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
        Schema::create('jcow_stories', function (Blueprint $table) {
            $table->id();
            $table->integer('cid')->default('0');
            $table->tinyInteger('sticky');
            $table->tinyInteger('closed');
            $table->string('title', 120)->default('');
            $table->string('thumbnail', 255)->default('');
            $table->text('content');
            $table->integer('uid')->default('0');
            $table->integer('created')->default('0');
            $table->integer('lastreply')->default('0');
            $table->string('lastreplyuname', 50);
            $table->integer('lastreplyuid');
            $table->integer('updated')->default('0');
            $table->integer('views');
            $table->integer('comments');
            $table->integer('stream_id');
            $table->string('app', 50)->default('');
            $table->integer('digg');
            $table->integer('dugg');
            $table->integer('photos');
            $table->string('tags', 255);
            $table->tinyInteger('featured');
            $table->string('var1', 255)->default('');
            $table->string('var2', 255)->default('');
            $table->string('var3', 255)->default('');
            $table->string('var4', 255)->default('');
            $table->string('var5', 255)->default('');
            $table->text('text1');
            $table->text('text2');
            $table->binary('blob1');
            $table->text('rating');
            $table->integer('page_id');
            $table->string('page_type', 25);
            $table->index(['app'], 'jcow_stories_app');
            $table->index(['uid'], 'jcow_stories_uid');
            $table->index(['page_id'], 'jcow_stories_page_id');
            $table->index(['cid'], 'jcow_stories_cid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_stories');
    }
};
