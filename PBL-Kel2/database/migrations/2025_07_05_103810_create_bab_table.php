2025_07_05_024117_create_bab_table.php
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
        Schema::create('bab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->decimal('harga', 10, 2);
            $table->date('deadline');
            $table->enum('status', ['available', 'reserved', 'taken']);
            $table->string('bab')->nullable(); // jika hanya satu bab (meskipun secara desain ini tidak ideal)
            $table->string('status_pembayaran')->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('nama_penulis')->nullable();
            $table->string('file_naskah')->nullable();
            $table->date('tanggal_penerbitan')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */


    public function down(): void
    {
        Schema::table('bab', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }

    
};