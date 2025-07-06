<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Buku extends Model
{
    protected $table = 'buku';
    protected $fillable = ['judul', 'deskripsi', 'image_url'];

    public function bab()
    {
        return $this->hasMany(Bab::class);
    }

    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bab_id')->constrained()->onDelete('cascade');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'valid', 'invalid']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};



