<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rekenings extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->string('bank'); // Kolom untuk menyimpan nama bank
            $table->string('nomor_rekening'); // Kolom untuk menyimpan nama rekening
            $table->string('nama_pemilik'); // Kolom untuk menyimpan nama pemilik
            $table->timestamps(); // Kolom untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekenings'); // Menghapus tabel rekenings jika ada
    }
}
