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
        Schema::create('jcow_invites', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->string('email', 255);
            $table->tinyInteger('status');
            $table->integer('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_invites');
    }
};
