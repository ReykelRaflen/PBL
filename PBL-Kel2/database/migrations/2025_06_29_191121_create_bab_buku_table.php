<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bab_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_kolaboratif_id')->constrained('buku_kolaboratif')->onDelete('cascade');
            $table->integer('nomor_bab');
            $table->string('judul_bab');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'dipesan', 'selesai'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bab_buku');
    }
};
