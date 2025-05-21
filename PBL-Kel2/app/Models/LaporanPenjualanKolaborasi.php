<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanPenjualanKolaborasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_penjualan_buku_Kolaborasi';

    protected $fillable = [
        'judul_buku',
        'penulis',
        'jumlah_terjual',
        'total_harga',
        'tanggal_penjualan',
        'status_pembayaran',
        'bukti_pembayaran',
    ];
}


