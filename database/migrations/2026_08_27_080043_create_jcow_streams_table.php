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
        Schema::create('jcow_streams', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->integer('wall_id');
            $table->integer('uid');
            $table->text('attachment');
            $table->integer('created');
            $table->tinyInteger('type');
            $table->string('app', 20);
            $table->integer('aid');
            $table->tinyInteger('hide');
            $table->integer('likes');
            $table->index(['app'], 'jcow_streams_app');
            $table->index(['aid'], 'jcow_streams_aid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_streams');
    }
};
