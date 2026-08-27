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
        Schema::create('jcow_groups', function (Blueprint $table) {
            $table->id();
            $table->string('uri', 30);
            $table->string('name', 100);
            $table->string('slogan', 200);
            $table->integer('creatorid');
            $table->string('creator', 50);
            $table->text('description');
            $table->integer('members');
            $table->integer('created');
            $table->string('logo', 100);
            $table->tinyInteger('private');
            $table->tinyInteger('needapproval');
            $table->integer('posts');
            $table->integer('topics');
            $table->integer('lastptime');
            $table->string('lastpname', 50);
            $table->string('password', 32);
            $table->text('custom_css');
            $table->string('style_ids', 50);
            $table->char('category', 2);
            $table->index(['creatorid'], 'jcow_groups_creatorid');
            $table->index(['uri'], 'jcow_groups_uri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_groups');
    }
};
