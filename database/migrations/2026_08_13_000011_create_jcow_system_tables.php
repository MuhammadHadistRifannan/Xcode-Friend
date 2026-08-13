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
        Schema::create('jcow_gvars', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('gkey', 50)->primary();
            $table->text('gvalue');
            $table->index('gkey');
        });
        
        Schema::create('jcow_modules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('name', 50)->primary();
            $table->tinyInteger('actived')->default(0);
            $table->tinyInteger('hooking')->default(0);
            $table->index('name');
        });
        
        Schema::create('jcow_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 50);
            $table->string('tab_name', 50);
            $table->integer('weight')->default(0);
            $table->string('path', 255)->default('');
            $table->string('app', 50)->default('');
            $table->tinyInteger('actived')->default(0);
            $table->string('type', 25);
            $table->tinyInteger('protected');
            $table->text('allowed_roles');
            $table->string('icon', 255);
            $table->string('parent', 255);
        });
        
        Schema::create('jcow_langs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('lang_from', 255)->default('');
            $table->text('lang_to');
            $table->string('lang', 20)->default('');
            $table->index('lang_from');
        });
        
        Schema::create('jcow_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('url', 255);
            $table->text('message');
            $table->tinyInteger('hasread')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
        
        Schema::create('jcow_banned', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('username', 100);
            $table->string('ip1', 3);
            $table->string('ip2', 3);
            $table->string('ip3', 3);
            $table->string('ip4', 3);
            $table->integer('created');
            $table->integer('expired');
            $table->string('operator', 100);
        });
        
        Schema::create('jcow_invites', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('email', 255);
            $table->tinyInteger('status');
            $table->timestamp('created_at')->useCurrent();
        });
        
        Schema::create('jcow_cache', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('ckey', 50)->primary();
            $table->text('content');
            $table->integer('expired');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_cache');
        Schema::dropIfExists('jcow_invites');
        Schema::dropIfExists('jcow_banned');
        Schema::dropIfExists('jcow_reports');
        Schema::dropIfExists('jcow_langs');
        Schema::dropIfExists('jcow_menu');
        Schema::dropIfExists('jcow_modules');
        Schema::dropIfExists('jcow_gvars');
    }
};