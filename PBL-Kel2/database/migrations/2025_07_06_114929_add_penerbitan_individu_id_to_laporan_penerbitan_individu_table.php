<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            $table->unsignedBigInteger('penerbitan_individu_id')->nullable()->after('status');
            $table->foreign('penerbitan_individu_id')->references('id')->on('penerbitan_individu')->onDelete('set null');
            $table->index('penerbitan_individu_id');
        });
    }

    public function down()
    {
        Schema::table('laporan_penerbitan_individu', function (Blueprint $table) {
            $table->dropForeign(['penerbitan_individu_id']);
            $table->dropIndex(['penerbitan_individu_id']);
            $table->dropColumn('penerbitan_individu_id');
        });
    }
};
