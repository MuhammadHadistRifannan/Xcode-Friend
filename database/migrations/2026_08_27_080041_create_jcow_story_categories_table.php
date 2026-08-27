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
        Schema::create('jcow_story_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('gid');
            $table->string('name', 150)->default('');
            $table->text('description');
            $table->integer('weight')->default('0');
            $table->string('app', 50)->default('');
            $table->string('var1', 255);
            $table->string('var2', 255);
            $table->string('var3', 255);
            $table->string('var4', 255);
            $table->string('var5', 255);
            $table->string('uri', 255);
            $table->index(['app'], 'jcow_story_categories_app');
            $table->index(['weight'], 'jcow_story_categories_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_story_categories');
    }
};
