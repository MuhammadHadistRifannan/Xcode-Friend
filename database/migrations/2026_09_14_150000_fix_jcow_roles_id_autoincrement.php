<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix jcow_roles table: kolom 'id' tidak memiliki AUTO_INCREMENT dan PRIMARY KEY
     * sehingga insert role baru selalu gagal dengan SQLSTATE[HY000] 1364.
     * Migration ini meng-alter kolom id menjadi AUTO_INCREMENT PRIMARY KEY
     * tanpa menghapus data yang sudah ada.
     */
    public function up(): void
    {
        // Hapus index biasa dulu jika ada (agar bisa dijadikan primary key)
        try {
            Schema::table('jcow_roles', function (Blueprint $table) {
                $table->dropIndex('jcow_roles_id');
            });
        } catch (\Exception $e) {
            // Index mungkin sudah tidak ada, lanjutkan
        }

        // Ubah kolom id menjadi AUTO_INCREMENT PRIMARY KEY
        DB::statement('ALTER TABLE `jcow_roles` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }

    public function down(): void
    {
        // Kembalikan ke integer biasa tanpa auto increment
        DB::statement('ALTER TABLE `jcow_roles` MODIFY `id` INT NOT NULL');
    }
};
