<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('laporan_penjualan_buku_kolaborasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->integer('jumlah_terjual');
            $table->double('total_harga');
            $table->enum('status_pembayaran', ['Valid', 'Tidak Valid']);
            $table->string('bukti_pembayaran')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualan_kolaborasi');
    }
};
