<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreign('kode_promo')->references('kode_promo')->on('promos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['kode_promo']);
        });
    }
};
