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
        Schema::create('jcow_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('uri', 30);
            $table->string('name', 100);
            $table->string('slogan', 200);
            $table->foreignId('creatorid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('creator', 50);
            $table->text('description');
            $table->integer('members')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->string('logo', 100);
            $table->tinyInteger('private')->default(0);
            $table->tinyInteger('needapproval')->default(0);
            $table->integer('posts')->default(0);
            $table->integer('topics')->default(0);
            $table->integer('lastptime')->default(0);
            $table->string('lastpname', 50);
            $table->string('password', 32);
            $table->text('custom_css');
            $table->string('style_ids', 50);
            $table->char('category', 2);
            $table->unique('uri');
            $table->index('creatorid');
        });
        
        Schema::create('jcow_group_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 50);
            $table->integer('groups')->default(0);
        });
        
        Schema::create('jcow_group_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('gid')->constrained('jcow_groups')->onDelete('cascade');
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->string('nickname', 20);
            $table->text('about_me');
            $table->tinyInteger('hide_profile')->default(0);
            $table->tinyInteger('status')->default(0); // 0=active, 1=pending, 2=rejected, 3=banned
            $table->index('gid');
            $table->index('uid');
            $table->unique(['gid', 'uid']);
        });
        
        Schema::create('jcow_group_posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('gid')->constrained('jcow_groups')->onDelete('cascade');
            $table->bigInteger('tid')->default(0);
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('username', 50);
            $table->integer('rtid')->default(0);
            $table->integer('rid')->default(0);
            $table->string('rname', 100);
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->string('ip', 30)->default('');
            $table->integer('attach')->default(0);
            $table->tinyInteger('bbcode_off')->default(0);
            $table->tinyInteger('emote_off')->default(0);
            $table->tinyInteger('got_attach')->default(0);
            $table->string('topic', 100);
            $table->tinyInteger('is_first')->default(0);
            $table->integer('replies')->default(0);
            $table->index(['tid', 'gid']);
            $table->index('uid');
            $table->index('rtid');
            $table->index('rid');
        });
        
        Schema::create('jcow_group_topics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('gid')->constrained('jcow_groups')->onDelete('cascade');
            $table->integer('old_fid')->default(0);
            $table->integer('pid')->default(0);
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
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
            $table->index('gid');
        });
        
        Schema::create('jcow_group_postcats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('gid')->constrained('jcow_groups')->onDelete('cascade');
            $table->string('name', 100);
            $table->index('gid');
        });
        
        Schema::create('jcow_group_polls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('tid')->constrained('jcow_group_topics')->onDelete('cascade');
            $table->string('question', 100)->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->text('options');
            $table->integer('timeout')->default(0);
            $table->tinyInteger('options_per_user')->default(0);
            $table->text('voters');
            $table->integer('total')->default(0);
            $table->index('tid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_polls');
        Schema::dropIfExists('jcow_group_postcats');
        Schema::dropIfExists('jcow_group_topics');
        Schema::dropIfExists('jcow_group_posts');
        Schema::dropIfExists('jcow_group_members');
        Schema::dropIfExists('jcow_group_categories');
        Schema::dropIfExists('jcow_groups');
    }
};