<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerbitan_individu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->enum('paket', ['silver', 'gold', 'diamond']);
            $table->decimal('harga_paket', 10, 2);
            $table->timestamp('tanggal_pesanan')->nullable();
            
            // Payment fields
            $table->enum('status_pembayaran', ['menunggu', 'pending', 'lunas', 'ditolak', 'dibatalkan'])->default('menunggu');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bank_pengirim')->nullable();
            $table->string('bank_tujuan')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan_pembayaran')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            
            // Publishing fields
            $table->enum('status_penerbitan', ['belum_mulai', 'dapat_mulai', 'sudah_kirim', 'revisi', 'disetujui', 'ditolak', 'selesai'])->default('belum_mulai');
            $table->string('judul_buku')->nullable();
            $table->string('nama_penulis')->nullable();
            $table->string('file_naskah')->nullable();
            $table->text('deskripsi_singkat')->nullable(); // Changed from deskripsi_buku to match controller
            $table->timestamp('tanggal_upload_naskah')->nullable();
            
            // Admin fields
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            $table->text('feedback_editor')->nullable();
            $table->timestamp('tanggal_feedback')->nullable(); // Fixed typo: timesatamp -> timestamp
            $table->text('catatan_persetujuan')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerbitan_individu');
    }
};
