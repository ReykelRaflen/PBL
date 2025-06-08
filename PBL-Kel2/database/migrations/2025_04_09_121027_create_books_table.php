<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn')->nullable()->unique();
            
            // Kolom kategori_id dan promo_id (tanpa foreign key constraint dulu)
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('promo_id')->nullable();
            
            $table->decimal('harga', 10, 2)->nullable();
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->string('file_buku')->nullable();
            
            // Tambahan untuk promo
            $table->decimal('harga_promo', 10, 2)->nullable(); // Harga setelah promo
            
            $table->timestamps();

            // Index untuk performa
            $table->index(['judul_buku', 'penulis']);
            $table->index('kategori_id');
            $table->index('promo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
