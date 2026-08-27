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
        Schema::create('jcow_blacks', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->default('0');
            $table->integer('bid')->default('0');
            $table->string('bname', 50);
            $table->index(['uid', 'bid'], 'jcow_blacks_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_blacks');
    }
};
