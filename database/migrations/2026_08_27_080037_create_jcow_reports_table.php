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
        Schema::create('jcow_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->string('url', 255);
            $table->text('message');
            $table->tinyInteger('hasread');
            $table->integer('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_reports');
    }
};
