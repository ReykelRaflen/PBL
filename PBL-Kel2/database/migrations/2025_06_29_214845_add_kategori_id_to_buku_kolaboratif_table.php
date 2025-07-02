<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buku_kolaboratif', function (Blueprint $table) {
            // Hapus kolom kategori lama jika ada
            if (Schema::hasColumn('buku_kolaboratif', 'kategori')) {
                $table->dropColumn('kategori');
            }
            
            // Tambah foreign key ke kategori_buku
            $table->foreignId('kategori_buku_id')->nullable()->after('deskripsi')->constrained('kategori_buku')->onDelete('set null');
            
            // Tambah kolom target_pembaca jika belum ada
            if (!Schema::hasColumn('buku_kolaboratif', 'target_pembaca')) {
                $table->string('target_pembaca')->default('umum')->after('kategori_buku_id');
            }
        });
    }

    public function down()
    {
        Schema::table('buku_kolaboratif', function (Blueprint $table) {
            $table->dropForeign(['kategori_buku_id']);
            $table->dropColumn(['kategori_buku_id', 'target_pembaca']);
            
            // Kembalikan kolom kategori lama
            $table->string('kategori')->nullable();
        });
    }
};
