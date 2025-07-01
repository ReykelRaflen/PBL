<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerbitanIndividuTable extends Migration
{
    public function up()
    {
        Schema::create('penerbitan_individus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pilihan_paket', 50)->nullable();
            $table->unsignedBigInteger('rekening_id')->nullable();
            $table->string('judul_naskah', 100)->nullable();
            $table->string('nama_penulis', 100)->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->date('tanggal_penerbitan')->nullable();
           
        Schema::table('penerbitan_individus', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['Valid', 'Tidak Valid']);
            $table->text('catatan_admin')->nullable();
});

            $table->timestamps();
            $table->softDeletes();


            // Foreign key ke tabel rekenings
            $table->foreign('rekening_id')->references('id')->on('rekenings')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penerbitan_individus');
    }
}