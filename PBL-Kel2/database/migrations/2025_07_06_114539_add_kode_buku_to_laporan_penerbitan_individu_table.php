<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            $table->string('kode_buku')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            $table->dropColumn('kode_buku');
        });
    }
};
