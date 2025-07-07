<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            // Ubah tanggal_terbit menjadi nullable
            $table->date('tanggal_terbit')->nullable()->change();
            
            // Tambahkan field yang mungkin diperlukan
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'penerbit')) {
                $table->string('penerbit')->nullable();
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'harga_jual')) {
                $table->decimal('harga_jual', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'jumlah_cetak')) {
                $table->integer('jumlah_cetak')->nullable();
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'catatan')) {
                $table->text('catatan')->nullable();
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->foreign('admin_id')->references('id')->on('users');
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'isbn')) {
                $table->string('isbn')->nullable();
            }
            if (!Schema::hasColumn('laporan_penerbitan_kolaborasi', 'buku_kolaboratif_id')) {
                $table->unsignedBigInteger('buku_kolaboratif_id')->nullable();
                $table->foreign('buku_kolaboratif_id')->references('id')->on('buku_kolaboratif');
            }
        });
    }

    public function down()
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            $table->date('tanggal_terbit')->nullable(false)->change();
        });
    }
};
