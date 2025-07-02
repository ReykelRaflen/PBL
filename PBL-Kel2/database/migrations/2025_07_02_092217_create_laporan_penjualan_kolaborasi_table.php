<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_penjualan_kolaborasi', function (Blueprint $table) {
            $table->id();
            
            // === RELASI KE PESANAN ===
            $table->foreignId('pesanan_kolaborasi_id')->constrained('pesanan_kolaborasi')->onDelete('cascade');
            
            // === DATA BUKU (DENORMALIZED UNTUK PERFORMA) ===
            $table->string('judul', 1000);
            $table->string('penulis', 255);
            $table->string('bab', 255);
            $table->string('nomor_invoice')->unique(); // Sama dengan nomor_pesanan
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->string('bukti_pembayaran')->nullable();
            
            // === STATUS VERIFIKASI ADMIN ===
            $table->enum('status_pembayaran', [
                'menunggu_verifikasi', // Menunggu admin cek
                'sukses',              // Admin approve
                'tidak_sesuai'         // Admin tolak
            ])->default('menunggu_verifikasi');
            
            // === DATA VERIFIKASI ADMIN ===
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            
            // === TIMESTAMPS ===
            $table->date('tanggal')->default(now()); // Tanggal transaksi
            $table->timestamps();
            
            // === INDEXES ===
            $table->index(['status_pembayaran']);
            $table->index(['tanggal']);
            $table->index(['pesanan_kolaborasi_id']);
            $table->index(['admin_id']);
            $table->index(['tanggal_verifikasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualan_kolaborasi');
    }
};
