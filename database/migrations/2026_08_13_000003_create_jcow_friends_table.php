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
        Schema::create('jcow_friends', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('jcow_accounts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_id', 'friend_id']);
        });
        
        Schema::create('jcow_friend_reqs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('fid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->string('msg', 200);
            $table->timestamp('accepted_at')->nullable();
            $table->unique(['uid', 'fid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_friend_reqs');
        Schema::dropIfExists('jcow_friends');
    }
};