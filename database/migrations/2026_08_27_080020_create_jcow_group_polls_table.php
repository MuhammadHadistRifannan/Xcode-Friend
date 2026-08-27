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
        Schema::create('jcow_group_polls', function (Blueprint $table) {
            $table->id();
            $table->integer('tid')->default('0');
            $table->string('question', 100)->default('');
            $table->integer('created')->default('0');
            $table->text('options');
            $table->integer('timeout')->default('0');
            $table->tinyInteger('options_per_user')->default('0');
            $table->text('voters');
            $table->integer('total')->default('0');
            $table->index(['tid'], 'jcow_group_polls_tid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_group_polls');
    }
};
