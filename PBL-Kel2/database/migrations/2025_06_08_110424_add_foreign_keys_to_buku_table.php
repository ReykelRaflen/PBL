<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Tambahkan foreign key setelah semua tabel sudah dibuat
            $table->foreign('kategori_id')->references('id')->on('kategori_buku')->onDelete('set null');
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropForeign(['promo_id']);
        });
    }
};
