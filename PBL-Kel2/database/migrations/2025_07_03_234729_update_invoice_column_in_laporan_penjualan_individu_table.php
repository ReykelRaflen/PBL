<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_penjualan_individu', function (Blueprint $table) {
            // Ubah kolom invoice dari integer ke string
            $table->string('invoice', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_penjualan_individu', function (Blueprint $table) {
            $table->integer('invoice')->change();
        });
    }
};
