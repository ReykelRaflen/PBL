<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            // Hapus kolom judul_buku karena sudah ada kolom judul
            if (Schema::hasColumn('laporan_penerbitan_kolaborasi', 'judul_buku')) {
                $table->dropColumn('judul_buku');
            }
        });
    }

    public function down()
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            // Kembalikan kolom jika rollback
            $table->string('judul_buku')->nullable();
        });
    }
};
