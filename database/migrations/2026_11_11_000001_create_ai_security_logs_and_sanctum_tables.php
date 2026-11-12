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
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->text('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ai_security_logs')) {
            Schema::create('ai_security_logs', function (Blueprint $table) {
                $table->id();
                $table->timestamp('detected_at')->nullable()->index();
                $table->string('attack_type', 150)->index();
                $table->string('source_ip', 64)->nullable()->index();
                $table->string('target', 500);
                $table->string('severity', 32)->default('medium')->index();
                $table->longText('ai_analysis')->nullable();
                $table->string('status', 32)->default('open')->index();
                $table->boolean('ip_blocked')->default(false);
                $table->json('raw_payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blocked_ips')) {
            Schema::create('blocked_ips', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 64)->unique();
                $table->string('reason', 255)->nullable();
                $table->unsignedBigInteger('ai_security_log_id')->nullable();
                $table->unsignedBigInteger('blocked_by')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('ai_security_logs');
        Schema::dropIfExists('personal_access_tokens');
    }
};
