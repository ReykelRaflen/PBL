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
        Schema::create('naskah_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('naskah_id')->constrained('naskah')->onDelete('cascade');
            $table->enum('status_dari', ['pending', 'sedang_direview', 'disetujui', 'ditolak'])->nullable();
            $table->enum('status_ke', ['pending', 'sedang_direview', 'disetujui', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang mengubah status
            $table->timestamps();
            
            // Indexes
            $table->index(['naskah_id', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naskah_status_histories');
    }
};
