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
        Schema::create('jcow_gvars', function (Blueprint $table) {
            $table->string('gkey', 50);
            $table->text('gvalue');
            $table->index(['gkey'], 'jcow_gvars_gkey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_gvars');
    }
};
