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
        Schema::create('jcow_var_cache', function (Blueprint $table) {
            $table->string('name', 60);
            $table->string('content', 255);
            $table->integer('created');
            $table->index(['name', 'created'], 'jcow_var_cache_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_var_cache');
    }
};
