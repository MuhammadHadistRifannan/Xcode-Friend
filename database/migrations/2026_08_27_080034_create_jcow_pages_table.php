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
        Schema::create('jcow_pages', function (Blueprint $table) {
            $table->id();
            $table->string('uri', 30);
            $table->integer('uid');
            $table->integer('views');
            $table->string('logo', 100);
            $table->string('name', 100);
            $table->text('style_ids');
            $table->text('custom_css');
            $table->string('background', 100);
            $table->string('type', 25);
            $table->text('description');
            $table->integer('users');
            $table->integer('updated');
            $table->index(['uid'], 'jcow_pages_uid');
            $table->index(['uri'], 'jcow_pages_uri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_pages');
    }
};
