<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix the enum values to match what the controller expects
        DB::statement("ALTER TABLE laporan_penjualan_individu MODIFY COLUMN status_pembayaran ENUM('menunggu_verifikasi', 'sukses', 'tidak_sesuai') NOT NULL DEFAULT 'menunggu_verifikasi'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE laporan_penjualan_individu MODIFY COLUMN status_pembayaran ENUM('sukses', 'tidak sesuai') NOT NULL");
    }
};
