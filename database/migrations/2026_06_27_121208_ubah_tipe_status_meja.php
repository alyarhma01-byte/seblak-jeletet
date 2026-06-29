<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan perintah untuk mengubah kolom.
     */
    public function up()
    {
        // Menggunakan Raw SQL untuk mengubah ENUM menjadi VARCHAR dengan aman tanpa menghapus data
        DB::statement("ALTER TABLE meja MODIFY status_meja VARCHAR(255) DEFAULT 'Tersedia'");
    }

    /**
     * Kembalikan ke keadaan semula jika di-rollback.
     */
    public function down()
    {
        // Mengembalikan ke ENUM jika suatu saat migration di-rollback
        DB::statement("ALTER TABLE meja MODIFY status_meja ENUM('Tersedia', 'Penuh') DEFAULT 'Tersedia'");
    }
};
