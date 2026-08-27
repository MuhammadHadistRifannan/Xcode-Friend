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
        Schema::create('jcow_story_photos', function (Blueprint $table) {
            $table->id();
            $table->integer('sid');
            $table->string('uri', 100);
            $table->string('des', 255);
            $table->string('thumb', 100);
            $table->integer('size');
            $table->index(['sid'], 'jcow_story_photos_sid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_story_photos');
    }
};
