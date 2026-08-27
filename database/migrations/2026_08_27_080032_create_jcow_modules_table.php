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
        Schema::create('jcow_modules', function (Blueprint $table) {
            $table->string('name', 50)->default('');
            $table->tinyInteger('actived')->default('0');
            $table->tinyInteger('hooking')->default('0');
            $table->index(['name'], 'jcow_modules_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_modules');
    }
};
