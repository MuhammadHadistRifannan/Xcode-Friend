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
        Schema::create('jcow_subscr', function (Blueprint $table) {
            $table->string('id', 32);
            $table->string('item_number', 32);
            $table->string('status', 25);
            $table->integer('uid');
            $table->integer('timeline');
            $table->index(['id'], 'jcow_subscr_id');
            $table->index(['uid'], 'jcow_subscr_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_subscr');
    }
};
