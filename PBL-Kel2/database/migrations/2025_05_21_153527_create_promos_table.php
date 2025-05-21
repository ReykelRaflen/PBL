<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_promo')->unique();
            $table->text('keterangan');
            // Info fields
            $table->enum('tipe', ['Persentase', 'Nominal'])->default('Persentase');
            $table->decimal('besaran', 10, 2); // Amount or percentage
            $table->integer('kuota')->nullable(); // Total available quota
            $table->integer('kuota_terpakai')->default(0); // Used quota
            // Timeframe
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promos');
    }
};
