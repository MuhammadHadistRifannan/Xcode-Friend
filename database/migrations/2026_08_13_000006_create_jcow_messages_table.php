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
        Schema::create('jcow_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('from_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('to_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('subject', 100)->default('');
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->tinyInteger('hasread')->default(0);
            $table->index(['from_id', 'to_id']);
        });
        
        Schema::create('jcow_messages_sent', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('from_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('to_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('subject', 100)->default('');
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
            $table->tinyInteger('hasread')->default(0);
            $table->index(['from_id', 'to_id']);
        });
        
        Schema::create('jcow_chatrooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('fid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->integer('request_id');
            $table->index(['uid', 'fid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_chatrooms');
        Schema::dropIfExists('jcow_messages_sent');
        Schema::dropIfExists('jcow_messages');
    }
};