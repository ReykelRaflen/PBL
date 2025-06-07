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
    Schema::create('kategori_buku', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->boolean('status')->default(true); // aktif atau tidak
    $table->timestamps();
});

    }           

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
