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
        Schema::create('jcow_friends', function (Blueprint $table) {
            $table->integer('uid')->default('0');
            $table->integer('fid')->default('0');
            $table->integer('created')->default('0');
            $table->index(['uid', 'fid'], 'jcow_friends_uid');
            $table->index(['fid'], 'jcow_friends_fid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_friends');
    }
};
