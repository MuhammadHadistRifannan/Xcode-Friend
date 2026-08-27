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
        Schema::create('jcow_user_crafts', function (Blueprint $table) {
            $table->integer('uid');
            $table->string('hash', 5);
            $table->integer('created');
            $table->index(['uid', 'created'], 'jcow_user_crafts_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_user_crafts');
    }
};
