<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LaporanPenjualanKolaborasi extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan_kolaborasi';

    protected $fillable = [
        'pesanan_kolaborasi_id',
        'judul',
        'penulis',
        'bab',
        'tanggal',
        'bukti_pembayaran',
        'status_pembayaran',
        'nomor_invoice',
        'jumlah_pembayaran',
        'admin_id',
        'catatan_admin',
        'tanggal_verifikasi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_pembayaran' => 'decimal:2',
        'tanggal_verifikasi' => 'datetime'
    ];

    // Relationships
    public function pesananKolaborasi()
    {
        return $this->belongsTo(PesananKolaborasi::class);
    }

    // Alias untuk kompatibilitas dengan controller lama
    public function pesananBuku()
    {
        return $this->pesananKolaborasi();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Method untuk verifikasi pembayaran
    public function verifikasiPembayaran($status, $adminId, $catatan = null)
    {
        $this->update([
            'status_pembayaran' => $status,
            'admin_id' => $adminId,
            'catatan_admin' => $catatan,
            'tanggal_verifikasi' => now()
        ]);

        // Update status di pesanan kolaborasi
        if ($this->pesananKolaborasi) {
            if ($status === 'sukses') {
                $this->pesananKolaborasi->update([
                    'status_pembayaran' => 'lunas',
                    'hasil_verifikasi' => 'disetujui',
                    'admin_id' => $adminId,
                    'catatan_admin' => $catatan,
                    'tanggal_verifikasi' => now()
                ]);
            } else {
                $this->pesananKolaborasi->update([
                    'status_pembayaran' => 'tidak_sesuai',
                    'hasil_verifikasi' => 'ditolak',
                    'admin_id' => $adminId,
                    'catatan_admin' => $catatan,
                    'tanggal_verifikasi' => now()
                ]);

                // Kembalikan status bab menjadi tersedia
                if ($this->pesananKolaborasi->babBuku) {
                    $this->pesananKolaborasi->babBuku->update(['status' => 'tersedia']);
                }
            }
        }
    }

    // Accessors
    public function getJumlahPembayaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_pembayaran, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
            'sukses' => 'bg-green-100 text-green-800',
            'tidak_sesuai' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'sukses' => 'Sukses',
            'tidak_sesuai' => 'Tidak Sesuai',
            default => ucfirst(str_replace('_', ' ', $this->status_pembayaran))
        };
    }

    // Alias untuk kompatibilitas dengan view
    public function getInvoiceAttribute()
    {
        return $this->nomor_invoice;
    }
}
