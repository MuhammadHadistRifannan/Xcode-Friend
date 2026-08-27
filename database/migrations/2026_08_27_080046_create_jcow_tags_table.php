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
        Schema::create('jcow_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('app', 25);
            $table->integer('num');
            $table->index(['name'], 'jcow_tags_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_tags');
    }
};
