<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_penjualan_individu', function (Blueprint $table) {
            // Add the foreign key column if it doesn't exist
            if (!Schema::hasColumn('laporan_penjualan_individu', 'penerbitan_individu_id')) {
                $table->foreignId('penerbitan_individu_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('penerbitan_individu')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporan_penjualan_individu', function (Blueprint $table) {
            if (Schema::hasColumn('laporan_penjualan_individu', 'penerbitan_individu_id')) {
                $table->dropForeign(['penerbitan_individu_id']);
                $table->dropColumn('penerbitan_individu_id');
            }
        });
    }
};
