<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jcow_spam_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->text('message');
            $table->text('reasons');
            $table->integer('created')->unsigned();
            $table->index('user_id');
            $table->index('created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jcow_spam_log');
    }
};
