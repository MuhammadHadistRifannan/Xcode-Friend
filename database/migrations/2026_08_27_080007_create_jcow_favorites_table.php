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
        Schema::create('jcow_favorites', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->integer('fuid');
            $table->string('fapp', 100);
            $table->integer('fsid');
            $table->integer('created');
            $table->string('title', 100);
            $table->index(['uid', 'fuid', 'fsid', 'created'], 'jcow_favorites_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_favorites');
    }
};
