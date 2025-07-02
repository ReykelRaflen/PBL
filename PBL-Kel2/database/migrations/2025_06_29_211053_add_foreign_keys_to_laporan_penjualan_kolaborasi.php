<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tunggu sampai semua tabel lain selesai dibuat
        if (Schema::hasTable('laporan_penjualan_kolaborasi')) {
            Schema::table('laporan_penjualan_kolaborasi', function (Blueprint $table) {
                // Hanya tambahkan foreign key jika tabel yang direferensi ada
                if (Schema::hasTable('pesanan_buku')) {
                    try {
                        $table->foreign('pesanan_buku_id')
                              ->references('id')
                              ->on('pesanan_buku')
                              ->onDelete('set null');
                    } catch (\Exception $e) {
                        // Jika gagal, lanjutkan tanpa foreign key
                    }
                }
                
                if (Schema::hasTable('users')) {
                    try {
                        $table->foreign('admin_id')
                              ->references('id')
                              ->on('users')
                              ->onDelete('set null');
                    } catch (\Exception $e) {
                        // Jika gagal, lanjutkan tanpa foreign key
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('laporan_penjualan_kolaborasi')) {
            Schema::table('laporan_penjualan_kolaborasi', function (Blueprint $table) {
                try {
                    $table->dropForeign(['pesanan_buku_id']);
                } catch (\Exception $e) {}
                
                try {
                    $table->dropForeign(['admin_id']);
                } catch (\Exception $e) {}
            });
        }
    }
};
