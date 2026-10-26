<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\AccountDeletionService;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membersihkan sisa data orphan dari akun-akun yang telah dihapus sebelumnya.
     */
    public function up(): void
    {
        try {
            $service = app(AccountDeletionService::class);
            $service->purgeOrphans();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Purge orphan migration notice: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation for purged orphan data
    }
};
