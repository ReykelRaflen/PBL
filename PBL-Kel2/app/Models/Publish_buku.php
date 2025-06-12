<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publish_Buku extends Model
{
    protected $table = 'publish_books';
    protected $fillable = [
        'judul_buku', 'harga', 'penulis', 'penerbit', 'isbn', 'jumlah_halaman',
        'tanggal_terbit', 'deskripsi', 'cover_buku', 'diskon'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

