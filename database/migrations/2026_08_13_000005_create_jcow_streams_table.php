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
        Schema::create('jcow_streams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->text('message');
            $table->integer('wall_id')->default(0);
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->text('attachment');
            $table->timestamp('created_at')->useCurrent();
            $table->tinyInteger('type')->default(0);
            $table->string('app', 20)->default('');
            $table->integer('aid')->default(0);
            $table->tinyInteger('hide')->default(0);
            $table->integer('likes')->default(0);
            $table->index(['app', 'aid']);
            $table->index('uid');
        });
        
        Schema::create('jcow_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('target_id', 20);
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('stream_id')->constrained('jcow_streams')->onDelete('cascade');
            $table->index('target_id');
            $table->index('stream_id');
        });
        
        Schema::create('jcow_profile_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->integer('target_id');
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('stream_id')->constrained('jcow_streams')->onDelete('cascade');
            $table->index('stream_id');
        });
        
        Schema::create('jcow_liked', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('stream_id')->constrained('jcow_streams')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['uid', 'stream_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_liked');
        Schema::dropIfExists('jcow_profile_comments');
        Schema::dropIfExists('jcow_comments');
        Schema::dropIfExists('jcow_streams');
    }
};