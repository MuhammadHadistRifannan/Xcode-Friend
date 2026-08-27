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
        Schema::create('jcow_forum_subscribes', function (Blueprint $table) {
            $table->integer('uid');
            $table->integer('tid');
            $table->index(['uid', 'tid'], 'jcow_forum_subscribes_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_forum_subscribes');
    }
};
