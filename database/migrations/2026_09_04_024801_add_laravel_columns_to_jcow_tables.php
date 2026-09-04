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
        Schema::table('jcow_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('jcow_accounts', 'name')) {
                $table->string('name')->nullable()->after('fullname');
            }
            if (!Schema::hasColumn('jcow_accounts', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('jcow_accounts', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
            if (!Schema::hasColumn('jcow_accounts', 'created_at')) {
                $table->timestamps();
            }
        });

        Schema::table('jcow_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('jcow_messages', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('jcow_messages', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcow_accounts', function (Blueprint $table) {
            $table->dropColumn(['name', 'email_verified_at', 'remember_token', 'created_at', 'updated_at']);
        });

        Schema::table('jcow_messages', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropTimestamps();
        });
    }
};
