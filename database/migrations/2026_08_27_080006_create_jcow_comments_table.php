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
        Schema::create('jcow_comments', function (Blueprint $table) {
            $table->id();
            $table->string('target_id', 20);
            $table->integer('uid');
            $table->text('message');
            $table->integer('created');
            $table->integer('stream_id');
            $table->index(['target_id'], 'jcow_comments_target_id');
            $table->index(['stream_id'], 'jcow_comments_stream_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_comments');
    }
};
