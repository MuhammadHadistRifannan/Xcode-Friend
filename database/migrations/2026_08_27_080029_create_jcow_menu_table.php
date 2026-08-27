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
        Schema::create('jcow_menu', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('tab_name', 50);
            $table->integer('weight')->default('0');
            $table->string('path', 255)->default('');
            $table->string('app', 50)->default('');
            $table->tinyInteger('actived')->default('0');
            $table->string('type', 25);
            $table->tinyInteger('protected');
            $table->text('allowed_roles');
            $table->string('icon', 255);
            $table->string('parent', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_menu');
    }
};
