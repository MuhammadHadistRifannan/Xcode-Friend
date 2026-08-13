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
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('weight')->default(0);
            $table->integer('parent_id')->default(0);
            $table->string('name', 255)->default('');
            $table->string('type_pic', 255)->default('');
            $table->text('description');
            $table->text('rules');
            $table->string('forum_type', 50)->default('0');
            $table->integer('threads')->default(0);
            $table->integer('posts')->default(0);
            $table->string('lastpostname', 32);
            $table->integer('lastposttopicid')->default(0);
            $table->string('lastposttopic', 70);
            $table->integer('lastpostcreated')->default(0);
            $table->string('moderator', 255)->default('');
            $table->text('settings');
            $table->integer('fmembers')->default(0);
            $table->string('image', 250);
            $table->string('read_roles', 255);
            $table->string('upload_roles', 255);
            $table->string('thread_roles', 255);
            $table->string('reply_roles', 255);
            $table->string('moderators', 255);
            $table->index('parent_id');
            $table->index('weight');
            $table->index('forum_type');
        });
        
        Schema::create('jcow_forum_threads', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('fid')->constrained('jcow_forums')->onDelete('cascade');
            $table->integer('old_fid')->default(0);
            $table->integer('pid')->default(0);
            $table->foreignId('userid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('username', 50);
            $table->string('topic', 255);
            $table->integer('views')->default(0);
            $table->integer('posts')->default(0);
            $table->smallInteger('closed')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->string('lastpostusername', 255)->default('0');
            $table->integer('lastpostcreated')->default(0);
            $table->tinyInteger('icon')->default(0);
            $table->tinyInteger('thread_type')->default(0);
            $table->tinyInteger('thread_lock')->default(0);
            $table->tinyInteger('got_poll')->default(0);
            $table->tinyInteger('got_attach')->default(0);
            $table->tinyInteger('stressed')->default(0);
            $table->integer('digg')->default(0);
            $table->integer('dugg')->default(0);
            $table->text('votes');
            $table->index('fid');
            $table->index('thread_type');
        });
        
        Schema::create('jcow_forum_posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('tid')->constrained('jcow_forum_threads')->onDelete('cascade');
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->string('ip', 30)->default('');
            $table->tinyInteger('is_first')->default(0);
            $table->integer('attach')->default(0);
            $table->tinyInteger('bbcode_off')->default(0);
            $table->tinyInteger('emote_off')->default(0);
            $table->tinyInteger('got_attach')->default(0);
            $table->index('tid');
            $table->index('uid');
        });
        
        Schema::create('jcow_forum_attachments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('pid')->constrained('jcow_forum_posts')->onDelete('cascade');
            $table->foreignId('tid')->constrained('jcow_forum_threads')->onDelete('cascade');
            $table->string('uri', 100);
            $table->string('des', 255);
            $table->integer('size');
            $table->string('orginal_name', 255);
            $table->integer('downloads')->default(0);
            $table->index('pid');
            $table->index('tid');
        });
        
        Schema::create('jcow_forum_polls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('tid')->constrained('jcow_forum_threads')->onDelete('cascade');
            $table->string('question', 100)->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->text('options');
            $table->integer('timeout')->default(0);
            $table->tinyInteger('options_per_user')->default(0);
            $table->text('voters');
            $table->integer('total')->default(0);
            $table->index('tid');
        });
        
        Schema::create('jcow_forum_subscribes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('tid')->constrained('jcow_forum_threads')->onDelete('cascade');
            $table->primary(['uid', 'tid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_forum_subscribes');
        Schema::dropIfExists('jcow_forum_polls');
        Schema::dropIfExists('jcow_forum_attachments');
        Schema::dropIfExists('jcow_forum_posts');
        Schema::dropIfExists('jcow_forum_threads');
        Schema::dropIfExists('jcow_forums');
    }
};