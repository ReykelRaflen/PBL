<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tambahkan 'dipesan' ke ENUM status
        DB::statement("ALTER TABLE bab_buku MODIFY COLUMN status ENUM('tersedia', 'dipesan', 'tidak_tersedia', 'selesai') DEFAULT 'tersedia'");
    }

    public function down()
    {
        // Kembalikan ke ENUM asli
        DB::statement("ALTER TABLE bab_buku MODIFY COLUMN status ENUM('tersedia', 'tidak_tersedia', 'selesai') DEFAULT 'tersedia'");
    }
};
