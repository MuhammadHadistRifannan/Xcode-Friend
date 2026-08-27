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
        Schema::create('jcow_profiles', function (Blueprint $table) {
            $table->integer('id');
            $table->string('style_ids', 255);
            $table->text('custom_css');
            $table->string('background', 100);
            $table->integer('videoid');
            $table->integer('favorites');
            $table->integer('views');
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_profiles');
    }
};
