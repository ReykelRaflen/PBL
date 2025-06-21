<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualanKolaborasi extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan_kolaborasi';

    protected $fillable = [
        'judul',
        'penulis',
        'bab',
        'tanggal',
        'bukti_pembayaran',
        'status_pembayaran',
        'invoice',
    ];
}
