<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

        public function up()
        {
            Schema::create('laporan_penjualan_kolaborasi', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('judul');
                $table->string('penulis');
                $table->string('bab'); //
                $table->date('tanggal')->default(now());
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
        Schema::dropIfExists('laporan_penjualan_kolaborasi');
    }
};
