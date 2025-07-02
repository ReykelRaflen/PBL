<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku_kolaboratif', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->decimal('harga_per_bab', 10, 2);
            $table->integer('total_bab');
            $table->string('gambar_sampul')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku_kolaboratif');
    }
};
