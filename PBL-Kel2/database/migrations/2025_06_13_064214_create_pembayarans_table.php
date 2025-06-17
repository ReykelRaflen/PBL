<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('metode_pembayaran')->default('Transfer Bank');
            $table->string('bank_pengirim');
            $table->string('nama_pengirim');
            $table->string('nomor_rekening_pengirim')->nullable();
            $table->decimal('jumlah_transfer', 12, 2);
            $table->string('bukti_pembayaran'); // path file
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu_verifikasi', 'terverifikasi', 'ditolak'])->default('menunggu_verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_pembayaran');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index untuk performa
            $table->index(['pesanan_id', 'status']);
            $table->index('invoice_number');
            $table->index('tanggal_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
