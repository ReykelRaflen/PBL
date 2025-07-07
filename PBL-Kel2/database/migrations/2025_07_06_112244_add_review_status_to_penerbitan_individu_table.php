<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add 'review' to the existing ENUM values
        DB::statement("ALTER TABLE penerbitan_individu MODIFY COLUMN status_penerbitan ENUM('belum_mulai','dapat_mulai','sudah_kirim','review','revisi','disetujui','ditolak','selesai') DEFAULT 'belum_mulai'");
    }

    public function down()
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE penerbitan_individu MODIFY COLUMN status_penerbitan ENUM('belum_mulai','dapat_mulai','sudah_kirim','revisi','disetujui','ditolak','selesai') DEFAULT 'belum_mulai'");
    }
};
