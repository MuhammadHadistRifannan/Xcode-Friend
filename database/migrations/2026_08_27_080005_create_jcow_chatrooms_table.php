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
        Schema::create('jcow_chatrooms', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->integer('fid');
            $table->text('content');
            $table->integer('updated');
            $table->integer('created');
            $table->integer('request_id');
            $table->index(['uid', 'fid'], 'jcow_chatrooms_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_chatrooms');
    }
};
