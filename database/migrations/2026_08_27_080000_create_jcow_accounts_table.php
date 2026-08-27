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
            // Menggunakan integer biasa (int 11) agar cocok dengan relasi 'uid' di tabel lain
            $table->integer('id', true);

            $table->bigInteger('fbid')->default(0);
            $table->string('email', 120)->default('');
            $table->integer('lastact')->default(0);
            $table->integer('created')->default(0);
            $table->string('username', 25);
            $table->string('fullname', 30);

            // Password tanpa batasan panjang karakter agar cukup untuk algoritma Bcrypt Laravel (60 karakter)
            $table->string('password');

            $table->tinyInteger('level')->default(0);
            $table->integer('points')->default(0);
            $table->string('avatar', 50)->nullable();
            $table->tinyText('signature')->nullable();
            $table->text('blurbs')->nullable();
            $table->tinyInteger('profile_permission')->default(0);
            $table->string('location', 100)->nullable();
            $table->integer('lastlogin')->default(0);
            $table->string('ipaddress', 30)->nullable();
            $table->string('chpass', 10)->nullable();
            $table->tinyInteger('disabled')->default(0);
            $table->text('intr')->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->text('about_me')->nullable();
            $table->integer('birthyear')->default(0);
            $table->tinyInteger('birthmonth')->default(0);
            $table->tinyInteger('birthday')->default(0);
            $table->tinyInteger('hide_age')->default(0);
            $table->string('reg_code', 8)->nullable();
            $table->integer('forum_posts')->default(0);
            $table->tinyInteger('featured')->default(0);
            $table->string('roles', 255)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('locale', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->char('jcowsess', 12)->nullable();
            $table->string('token', 32)->nullable();
            $table->integer('wall_id')->default(0);
            $table->integer('followers')->default(0);
            $table->text('settings')->nullable();
            $table->string('var1', 255)->nullable();
            $table->string('var2', 255)->nullable();
            $table->string('var3', 255)->nullable();
            $table->string('var4', 255)->nullable();
            $table->string('var5', 255)->nullable();
            $table->string('var6', 255)->nullable();
            $table->string('var7', 255)->nullable();
            $table->string('pass', 32)->nullable();
            $table->tinyInteger('hide_me')->default(0);

            // Indexes
            $table->index(['username'], 'jcow_accounts_username');
            $table->index(['lastlogin'], 'jcow_accounts_lastlogin');
            $table->index(['email'], 'jcow_accounts_email');
            $table->index(['fbid'], 'jcow_accounts_fbid');
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
