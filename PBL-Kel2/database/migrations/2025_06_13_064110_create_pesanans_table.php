<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('tipe_buku', ['fisik', 'ebook']);
            $table->integer('quantity')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->string('kode_promo')->nullable();
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->text('alamat_pengiriman')->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'terverifikasi', 
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            $table->timestamp('tanggal_pesanan');
            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'status']);
            $table->index('order_number');
            $table->index('tanggal_pesanan');
            
            // Tambahkan foreign key constraint untuk kode_promo
            $table->foreign('kode_promo')->references('kode_promo')->on('promos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
