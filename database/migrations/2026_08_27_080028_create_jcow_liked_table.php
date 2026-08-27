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
        Schema::create('jcow_liked', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->integer('stream_id');
            $table->index(['uid'], 'jcow_liked_uid');
            $table->index(['stream_id'], 'jcow_liked_stream_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_liked');
    }
};
