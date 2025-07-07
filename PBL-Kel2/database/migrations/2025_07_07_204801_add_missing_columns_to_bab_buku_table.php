<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bab_buku', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            $table->decimal('harga', 10, 2)->nullable()->after('deskripsi');
            $table->enum('tingkat_kesulitan', ['mudah', 'sedang', 'sulit'])->default('mudah')->after('harga');
            $table->integer('estimasi_kata')->nullable()->after('tingkat_kesulitan');
            $table->datetime('deadline')->nullable()->after('estimasi_kata');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('deadline');
            
            // Update kolom deskripsi jika perlu
            $table->text('deskripsi')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('bab_buku', function (Blueprint $table) {
            $table->dropColumn(['harga', 'tingkat_kesulitan', 'estimasi_kata', 'deadline', 'user_id']);
        });
    }
};
