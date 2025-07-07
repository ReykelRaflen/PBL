<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            // Tambahkan kolom baru yang diperlukan
            $table->foreignId('buku_kolaboratif_id')->after('id')->constrained('buku_kolaboratif')->onDelete('cascade');
            $table->string('isbn', 20)->nullable()->after('judul');
            $table->string('penerbit')->nullable()->after('status');
            $table->decimal('harga_jual', 10, 2)->nullable()->after('penerbit');
            $table->integer('jumlah_cetak')->nullable()->after('harga_jual');
            $table->text('catatan')->nullable()->after('jumlah_cetak');
            $table->foreignId('admin_id')->nullable()->after('catatan')->constrained('users')->onDelete('set null');
            
            // Rename kolom yang ada
            $table->renameColumn('judul', 'judul_buku');
            
            // Update enum status untuk menambahkan opsi baru
            $table->enum('status', ['draft', 'proses', 'pending', 'terbit'])->default('draft')->change();
        });
        
        // Drop kolom yang tidak diperlukan setelah rename
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            $table->dropColumn(['bab_buku', 'penulis']);
        });
        
        // Tambahkan indexes untuk optimasi
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index(['buku_kolaboratif_id', 'status'], 'idx_buku_status');
            $table->index(['tanggal_terbit', 'status'], 'idx_terbit_status');
            $table->index('kode_buku', 'idx_kode_buku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_penerbitan_kolaborasi', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_status_created');
            $table->dropIndex('idx_buku_status');
            $table->dropIndex('idx_terbit_status');
            $table->dropIndex('idx_kode_buku');
            
            // Drop foreign keys
            $table->dropForeign(['buku_kolaboratif_id']);
            $table->dropForeign(['admin_id']);
            
            // Drop kolom yang ditambahkan
            $table->dropColumn([
                'buku_kolaboratif_id',
                'isbn',
                'penerbit',
                'harga_jual',
                'jumlah_cetak',
                'catatan',
                'admin_id'
            ]);
            
            // Rename back
            $table->renameColumn('judul_buku', 'judul');
            
            // Tambahkan kembali kolom yang dihapus
            $table->string('bab_buku')->after('judul');
            $table->string('penulis')->after('bab_buku');
            
            // Kembalikan enum status ke nilai asli
            $table->enum('status', ['proses', 'terbit', 'pending'])->change();
        });
    }
};
