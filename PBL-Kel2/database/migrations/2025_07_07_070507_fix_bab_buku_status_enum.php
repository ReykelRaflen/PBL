<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bab_buku', function (Blueprint $table) {
            // Drop existing enum and recreate with correct values
            $table->dropColumn('status');
        });
        
        Schema::table('bab_buku', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'selesai'])->default('tersedia');
        });
    }

    public function down()
    {
        Schema::table('bab_buku', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('bab_buku', function (Blueprint $table) {
            $table->enum('status', ['available', 'unavailable', 'completed'])->default('available');
        });
    }
};
