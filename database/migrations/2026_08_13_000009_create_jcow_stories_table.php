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
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('cid')->default(0);
            $table->tinyInteger('sticky')->default(0);
            $table->tinyInteger('closed')->default(0);
            $table->string('title', 120)->default('');
            $table->string('thumbnail', 255)->default('');
            $table->text('content');
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('lastreply')->default(0);
            $table->string('lastreplyuname', 50);
            $table->integer('lastreplyuid');
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->integer('views')->default(0);
            $table->integer('comments')->default(0);
            $table->foreignId('stream_id')->constrained('jcow_streams')->onDelete('cascade');
            $table->string('app', 50)->default('');
            $table->integer('digg')->default(0);
            $table->integer('dugg')->default(0);
            $table->integer('photos')->default(0);
            $table->string('tags', 255);
            $table->tinyInteger('featured')->default(0);
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
            $table->index('app');
            $table->index('uid');
            $table->index('page_id');
            $table->index('cid');
        });
        
        Schema::create('jcow_story_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('gid');
            $table->string('name', 150)->default('');
            $table->text('description');
            $table->integer('weight')->default(0);
            $table->string('app', 50)->default('');
            $table->string('var1', 255);
            $table->string('var2', 255);
            $table->string('var3', 255);
            $table->string('var4', 255);
            $table->string('var5', 255);
            $table->string('uri', 255);
            $table->index('app');
            $table->index('weight');
        });
        
        Schema::create('jcow_story_cat_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 100);
            $table->string('app', 50);
            $table->integer('weight');
        });
        
        Schema::create('jcow_story_photos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('sid')->constrained('jcow_stories')->onDelete('cascade');
            $table->string('uri', 100);
            $table->string('des', 255);
            $table->string('thumb', 100);
            $table->integer('size');
            $table->index('sid');
        });
        
        Schema::create('jcow_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 100);
            $table->string('app', 50);
            $table->integer('num')->default(0);
            $table->index('name');
        });
        
        Schema::create('jcow_tag_ids', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->foreignId('tid')->constrained('jcow_tags')->onDelete('cascade');
            $table->foreignId('sid')->constrained('jcow_stories')->onDelete('cascade');
            $table->primary(['tid', 'sid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_tag_ids');
        Schema::dropIfExists('jcow_tags');
        Schema::dropIfExists('jcow_story_photos');
        Schema::dropIfExists('jcow_story_cat_groups');
        Schema::dropIfExists('jcow_story_categories');
        Schema::dropIfExists('jcow_stories');
    }
};