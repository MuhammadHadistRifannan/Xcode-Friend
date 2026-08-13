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
        Schema::create('jcow_accounts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->id();
            $table->bigInteger('fbid');
            $table->string('email', 120)->unique();
            $table->integer('lastact')->default(0);
            $table->integer('created')->default(0);
            $table->string('username', 25)->unique();
            $table->string('fullname', 30);
            $table->string('password', 32);
            $table->tinyInteger('level')->default(0);
            $table->integer('points');
            $table->string('avatar', 50);
            $table->text('signature');
            $table->text('blurbs');
            $table->tinyInteger('profile_permission')->default(0);
            $table->string('location', 100);
            $table->integer('lastlogin');
            $table->string('ipaddress', 30);
            $table->string('chpass', 10);
            $table->tinyInteger('disabled');
            $table->text('intr');
            $table->tinyInteger('gender');
            $table->text('about_me');
            $table->integer('birthyear');
            $table->tinyInteger('birthmonth');
            $table->tinyInteger('birthday');
            $table->tinyInteger('hide_age');
            $table->string('reg_code', 8);
            $table->integer('forum_posts');
            $table->tinyInteger('featured');
            $table->string('roles', 255);
            $table->string('country', 50);
            $table->string('locale', 50);
            $table->string('state', 50);
            $table->char('jcowsess', 12);
            $table->string('token', 32);
            $table->integer('wall_id');
            $table->integer('followers');
            $table->text('settings');
            $table->string('var1', 255);
            $table->string('var2', 255);
            $table->string('var3', 255);
            $table->string('var4', 255);
            $table->string('var5', 255);
            $table->string('var6', 255);
            $table->string('var7', 255);
            $table->string('pass', 32);
            $table->tinyInteger('hide_me');
            
            // Laravel timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            
            // Soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_accounts');
    }
};