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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable(); // deskripsi buku
            $table->string('cover')->nullable(); // path gambar
            $table->integer('original_price'); // harga asli buku fisik
            $table->integer('discount_price'); // harga diskon buku fisik
            $table->integer('ebook_price'); // harga e-book
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
