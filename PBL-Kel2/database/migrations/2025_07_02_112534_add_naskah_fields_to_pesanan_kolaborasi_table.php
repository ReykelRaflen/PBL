<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanan_kolaborasi', function (Blueprint $table) {
            // Fields untuk naskah
            $table->string('file_naskah')->nullable()->after('bukti_pembayaran');
            $table->string('judul_naskah')->nullable()->after('file_naskah');
            $table->text('deskripsi_naskah')->nullable()->after('judul_naskah');
            $table->integer('jumlah_kata')->nullable()->after('deskripsi_naskah');
            $table->text('catatan_penulis')->nullable()->after('jumlah_kata');
            $table->timestamp('tanggal_upload_naskah')->nullable()->after('catatan_penulis');
            
            // Fields untuk feedback editor
            $table->text('feedback_editor')->nullable()->after('tanggal_upload_naskah');
            $table->timestamp('tanggal_feedback')->nullable()->after('feedback_editor');
            $table->text('catatan_persetujuan')->nullable()->after('tanggal_feedback');
            $table->timestamp('tanggal_disetujui')->nullable()->after('catatan_persetujuan');
            
            // Update enum status_penulisan untuk menambah status baru
            $table->dropColumn('status_penulisan');
        });
        
        // Tambah ulang kolom status_penulisan dengan enum yang diperluas
        Schema::table('pesanan_kolaborasi', function (Blueprint $table) {
            $table->enum('status_penulisan', [
                'belum_mulai',
                'dapat_mulai',      // Setelah pembayaran lunas
                'sedang_proses',    // User sedang menulis
                'sudah_kirim',      // User sudah upload naskah
                'revisi',           // Editor minta revisi
                'selesai',          // Naskah selesai direview
                'disetujui',        // Naskah disetujui final
                'dibatalkan'
            ])->default('belum_mulai')->after('status_pembayaran');
            
            // Index untuk performa
            $table->index(['status_penulisan']);
            $table->index(['tanggal_upload_naskah']);
        });
    }

    public function down()
    {
        Schema::table('pesanan_kolaborasi', function (Blueprint $table) {
            $table->dropColumn([
                'file_naskah',
                'judul_naskah', 
                'deskripsi_naskah',
                'jumlah_kata',
                'catatan_penulis',
                'tanggal_upload_naskah',
                'feedback_editor',
                'tanggal_feedback',
                'catatan_persetujuan',
                'tanggal_disetujui'
            ]);
            
            // Kembalikan enum status_penulisan ke semula
            $table->dropColumn('status_penulisan');
        });
        
        Schema::table('pesanan_kolaborasi', function (Blueprint $table) {
            $table->enum('status_penulisan', [
                'belum_mulai', 
                'dalam_proses', 
                'selesai', 
                'revisi', 
                'dibatalkan'
            ])->default('belum_mulai')->after('status_pembayaran');
        });
    }
};
