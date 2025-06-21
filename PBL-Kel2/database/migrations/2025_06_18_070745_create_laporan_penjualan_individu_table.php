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
       Schema::create('laporan_penjualan_individu', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamps();
            $table->string('judul');
            $table->string('penulis');
            $table->enum('paket', ['silver', 'gold', 'diamond']);
            $table->date('tanggal')->default(now()); // Auto-set to current date
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['sukses', 'tidak sesuai']);
            $table->integer('invoice')->unique();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualan_individu');
    }
};
