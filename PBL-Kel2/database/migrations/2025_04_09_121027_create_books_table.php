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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->text('deskripsi')->nullable(); // deskripsi buku
            $table->string('sampul')->nullable(); // path gambar
            $table->integer('harga_asli'); // harga asli buku fisik
            $table->integer('harga_diskon'); // harga diskon buku fisik
            $table->integer('harga_ebook'); // harga e-book
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
