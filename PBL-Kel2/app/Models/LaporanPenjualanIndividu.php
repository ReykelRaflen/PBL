<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjualanIndividu extends Model
{
    protected $table = 'laporan_penjualan_individu'; // ✅ tambahkan ini

    protected $fillable = [
        'judul',
        'penulis',
        'paket',
        'bukti_pembayaran',
        'status_pembayaran',
        'tanggal',
        'invoice',
    ];
}
