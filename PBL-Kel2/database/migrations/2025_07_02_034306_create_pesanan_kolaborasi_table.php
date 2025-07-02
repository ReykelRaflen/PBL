<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pesanan_kolaborasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_kolaboratif_id')->constrained('buku_kolaboratif')->onDelete('cascade');
            $table->foreignId('bab_buku_id')->constrained('bab_buku')->onDelete('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->decimal('jumlah_bayar', 15, 2);
            
            // Status pembayaran - flow dari user
            $table->enum('status_pembayaran', [
                'menunggu',           // User belum bayar
                'pending',            // User sudah upload bukti, menunggu verifikasi admin
                'menunggu_verifikasi', // Alias untuk pending
                'lunas',              // Admin sudah approve pembayaran
                'dibatalkan',         // User/Admin batalkan
                'expired',            // Waktu pembayaran habis
                'gagal',              // Payment gateway gagal
                'tidak_sesuai'        // Admin tolak pembayaran
            ])->default('menunggu');
            
            $table->enum('status_penulisan', [
                'belum_mulai',        // Belum mulai nulis (menunggu pembayaran lunas)
                'dalam_proses',       // Sedang menulis
                'selesai',            // Sudah selesai
                'revisi',             // Perlu revisi
                'dibatalkan'          // Dibatalkan
            ])->default('belum_mulai');
            
            $table->text('catatan')->nullable();
            $table->datetime('tanggal_pesanan');
            $table->datetime('batas_pembayaran')->nullable();
            $table->datetime('tanggal_bayar')->nullable(); // Kapan user upload bukti bayar
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable(); // File bukti bayar dari user
            $table->datetime('tanggal_batal')->nullable();
            
            // === FIELD UNTUK ADMIN VERIFICATION ===
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable(); // Catatan admin saat verifikasi
            $table->timestamp('tanggal_verifikasi')->nullable(); // Kapan admin verifikasi
            $table->enum('hasil_verifikasi', [
                'menunggu',    // Belum diverifikasi admin
                'disetujui',   // Admin setuju, pembayaran valid
                'ditolak'      // Admin tolak, pembayaran tidak valid
            ])->default('menunggu');
            
            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'status_pembayaran']);
            $table->index(['buku_kolaboratif_id', 'bab_buku_id']);
            $table->index(['status_pembayaran', 'hasil_verifikasi']);
            $table->index(['admin_id']);
            $table->index(['tanggal_verifikasi']);
            $table->index(['hasil_verifikasi']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesanan_kolaborasi');
    }
};
