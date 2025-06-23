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
        Schema::create('naskah', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('nama_file_asli');
            $table->string('mime_type');
            $table->bigInteger('ukuran_file'); // dalam bytes
            $table->enum('status', ['pending', 'sedang_direview', 'disetujui', 'ditolak'])->default('pending');
            $table->datetime('batas_waktu');
            $table->text('catatan')->nullable();
            $table->timestamp('direview_pada')->nullable();
            
            // Foreign keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pengirim
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null'); // admin yang review
            
            $table->timestamps();
            $table->softDeletes(); // untuk soft delete
            
            // Indexes untuk performa
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index('batas_waktu');
            $table->index('created_at');
            $table->index('reviewer_id');
            $table->index('direview_pada');
            $table->index('deleted_at');
            
            // Composite indexes untuk query yang sering digunakan
            $table->index(['status', 'batas_waktu']);
            $table->index(['user_id', 'created_at']);
            $table->index(['reviewer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naskah');
    }
};
