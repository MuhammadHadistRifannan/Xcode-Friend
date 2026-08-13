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
        Schema::create('jcow_pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('uri', 30);
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->integer('views')->default(0);
            $table->string('logo', 100);
            $table->string('name', 100);
            $table->text('style_ids');
            $table->text('custom_css');
            $table->string('background', 100);
            $table->string('type', 25);
            $table->text('description');
            $table->integer('users')->default(0);
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->unique('uri');
            $table->index('uid');
        });
        
        Schema::create('jcow_page_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->foreignId('pid')->constrained('jcow_pages')->onDelete('cascade');
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->primary(['pid', 'uid']);
        });
        
        Schema::create('jcow_favorites', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->integer('fuid');
            $table->string('fapp', 100);
            $table->integer('fsid');
            $table->timestamp('created_at')->useCurrent();
            $table->string('title', 100);
            $table->index(['uid', 'fuid', 'fsid', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_favorites');
        Schema::dropIfExists('jcow_page_users');
        Schema::dropIfExists('jcow_pages');
    }
};