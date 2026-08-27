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
        Schema::create('jcow_forums', function (Blueprint $table) {
            $table->id();
            $table->integer('weight')->default('0');
            $table->integer('parent_id')->default('0');
            $table->string('name', 255)->default('');
            $table->string('type_pic', 255)->default('');
            $table->tinyText('description');
            $table->text('rules');
            $table->string('forum_type', 50)->default('0');
            $table->integer('threads')->default('0');
            $table->integer('posts')->default('0');
            $table->string('lastpostname', 32);
            $table->integer('lastposttopicid')->default('0');
            $table->string('lastposttopic', 70);
            $table->integer('lastpostcreated')->default('0');
            $table->string('moderator', 255)->default('');
            $table->text('settings');
            $table->integer('fmembers')->default('0');
            $table->string('image', 250);
            $table->string('read_roles', 255);
            $table->string('upload_roles', 255);
            $table->string('thread_roles', 255);
            $table->string('reply_roles', 255);
            $table->string('moderators', 255);
            $table->index(['parent_id'], 'jcow_forums_belong_id');
            $table->index(['weight'], 'jcow_forums_order_num');
            $table->index(['forum_type'], 'jcow_forums_type_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_forums');
    }
};
