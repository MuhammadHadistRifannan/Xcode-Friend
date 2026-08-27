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
        Schema::create('jcow_banned', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('ip1', 3);
            $table->string('ip2', 3);
            $table->string('ip3', 3);
            $table->string('ip4', 3);
            $table->integer('created');
            $table->integer('expired');
            $table->string('operator', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_banned');
    }
};
