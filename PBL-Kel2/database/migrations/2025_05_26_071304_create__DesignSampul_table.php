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
        Schema::create('DesignSampul', function (Blueprint $table) {
    $table->id();
    $table->string('nama_proyek');
    $table->string('jenis_design');
    $table->string('editor'); 
    $table->date('tanggal_kirim');
    $table->string('sampul')->require();
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DesignSampul');
    }
};
