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
        Schema::create('jcow_tag_ids', function (Blueprint $table) {
            $table->integer('tid');
            $table->integer('sid');
            $table->index(['tid', 'sid'], 'jcow_tag_ids_tid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_tag_ids');
    }
};
