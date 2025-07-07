<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            // Ubah kolom jumlah_terjual menjadi isbn
            $table->renameColumn('jumlah_terjual', 'isbn');
            
            // Ubah tipe data isbn menjadi string
            $table->string('isbn')->nullable()->change();
            
            // Ubah tanggal_terbit menjadi nullable
            $table->date('tanggal_terbit')->nullable()->change();
            
            // Ubah default status menjadi pending
            $table->enum('status', ['proses', 'terbit', 'pending'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            // Kembalikan perubahan
            $table->renameColumn('isbn', 'jumlah_terjual');
            $table->integer('jumlah_terjual')->change();
            $table->date('tanggal_terbit')->nullable(false)->change();
            $table->enum('status', ['proses', 'terbit', 'pending'])->change();
        });
    }
};
