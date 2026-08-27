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
        Schema::create('jcow_messages_sent', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 100)->default('');
            $table->text('message');
            $table->integer('from_id')->default('0');
            $table->integer('to_id')->default('0');
            $table->integer('created')->default('0');
            $table->tinyInteger('hasread')->default('0');
            $table->index(['from_id', 'to_id'], 'jcow_messages_sent_from_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_messages_sent');
    }
};
