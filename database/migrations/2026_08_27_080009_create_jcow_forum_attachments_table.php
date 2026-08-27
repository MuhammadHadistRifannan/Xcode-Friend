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
        Schema::create('jcow_forum_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->integer('tid');
            $table->string('uri', 100);
            $table->string('des', 255);
            $table->integer('size');
            $table->string('orginal_name', 255);
            $table->integer('downloads');
            $table->index(['pid'], 'jcow_forum_attachments_pid');
            $table->index(['tid'], 'jcow_forum_attachments_tid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_forum_attachments');
    }
};
