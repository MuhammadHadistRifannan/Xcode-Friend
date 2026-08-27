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
        Schema::create('jcow_votes', function (Blueprint $table) {
            $table->integer('sid');
            $table->integer('created');
            $table->integer('rate');
            $table->integer('uid');
            $table->index(['sid', 'uid'], 'jcow_votes_sid');
            $table->index(['created'], 'jcow_votes_created');
            $table->index(['uid'], 'jcow_votes_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_votes');
    }
};
