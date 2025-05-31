<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class LaporanBukuKolaborasi extends Model
{
    protected $table = 'laporan_penerbitan_kolaborasi';

    protected $fillable = [
        'kode_buku', 'judul','bab_buku', 'penulis', 'tanggal_terbit','jumlah_terjual', 'status'
    ];
}


