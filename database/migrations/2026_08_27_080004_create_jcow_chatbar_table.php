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
        Schema::create('jcow_chatbar', function (Blueprint $table) {
            $table->id();
            $table->string('from', 255)->default('');
            $table->string('to', 255)->default('');
            $table->text('message');
            $table->dateTime('sent')->nullable();
            $table->unsignedInteger('recd')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_chatbar');
    }
};