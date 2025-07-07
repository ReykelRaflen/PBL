<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_penjualan_individu', function (Blueprint $table) {
            // Add penerbitan_individu_id if it doesn't exist
            if (!Schema::hasColumn('laporan_penjualan_individu', 'penerbitan_individu_id')) {
                $table->foreignId('penerbitan_individu_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('penerbitan_individu')
                      ->onDelete('cascade');
            }

            // Add harga column if it doesn't exist
            if (!Schema::hasColumn('laporan_penjualan_individu', 'harga')) {
                $table->decimal('harga', 10, 2)->nullable()->after('paket');
            }

            // Modify invoice column to handle string format (if it's currently integer)
            $invoiceColumn = Schema::getColumnType('laporan_penjualan_individu', 'invoice');
            if ($invoiceColumn !== 'string') {
                $table->string('invoice')->change();
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

            if (Schema::hasColumn('laporan_penjualan_individu', 'harga')) {
                $table->dropColumn('harga');
            }

            // Revert invoice back to integer if needed
            $table->integer('invoice')->change();
        });
    }
};
