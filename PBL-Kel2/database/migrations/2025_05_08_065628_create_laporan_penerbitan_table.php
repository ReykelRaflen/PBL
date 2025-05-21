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
    Schema::create('laporan_penerbitan', function (Blueprint $table) {
        $table->id();
        $table->string('kode_buku')->unique();
        $table->string('judul');
        $table->string('penulis');
        $table->date('tanggal_terbit');
        $table->enum('status', ['proses', 'terbit', 'pending']);
        $table->integer('jumlah_terjual');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penerbitan');
    }
};
