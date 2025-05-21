<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class LaporanBukuIndividu extends Model
{
    protected $table = 'laporan_penerbitan';

    protected $fillable = [
        'kode_buku', 'judul', 'penulis', 'tanggal_terbit','jumlah_terjual', 'status'
    ];
}


