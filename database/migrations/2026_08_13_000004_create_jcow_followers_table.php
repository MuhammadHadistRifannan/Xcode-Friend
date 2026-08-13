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
        Schema::create('jcow_followers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('fid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['uid', 'fid']);
        });
        
        Schema::create('jcow_blacks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('uid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->foreignId('bid')->constrained('jcow_accounts')->onDelete('cascade');
            $table->string('bname', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['uid', 'bid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcow_blacks');
        Schema::dropIfExists('jcow_followers');
    }
};