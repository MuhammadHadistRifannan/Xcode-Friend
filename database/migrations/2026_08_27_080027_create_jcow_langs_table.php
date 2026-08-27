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
        Schema::create('jcow_langs', function (Blueprint $table) {
            $table->string('lang_from', 255)->default('');
            $table->text('lang_to');
            $table->string('lang', 20)->default('');
            $table->index(['lang_from'], 'jcow_langs_lang_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_langs');
    }
};
