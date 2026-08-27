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
        Schema::create('jcow_tmp', function (Blueprint $table) {
            $table->string('tkey', 70);
            $table->text('tcontent');
            $table->index(['tkey'], 'jcow_tmp_tkey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_tmp');
    }
};
