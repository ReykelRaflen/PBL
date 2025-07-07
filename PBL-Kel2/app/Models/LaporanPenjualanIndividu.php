<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualanIndividu extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan_individu';

    protected $fillable = [
        'penerbitan_individu_id',
        'judul',
        'penulis',
        'paket',
        'tanggal',
        'bukti_pembayaran',
        'status_pembayaran',
        'invoice',
        'harga',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
    ];

    // Accessors
    public function getStatusPembayaranTextAttribute()
    {
        return match($this->status_pembayaran) {
            'sukses' => 'Sukses',
            'tidak_sesuai' => 'Tidak Sesuai',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status_pembayaran))
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_pembayaran) {
            'sukses' => 'bg-green-500',
            'terverifikasi' => 'bg-green-500',
            'tidak_sesuai' => 'bg-red-500',
            'ditolak' => 'bg-red-500',
            'menunggu_verifikasi' => 'bg-yellow-500',
            default => 'bg-gray-500'
        };
    }

    public function getPaketBadgeAttribute()
    {
        return match($this->paket) {
            'silver' => 'bg-gray-500',
            'gold' => 'bg-yellow-500',
            'diamond' => 'bg-blue-500',
            default => 'bg-gray-500'
        };
    }

    // Relationships
    public function penerbitanIndividu()
    {
        return $this->belongsTo(PenerbitanIndividu::class);
    }

    // Accessor untuk format invoice
    public function getInvoiceFormattedAttribute()
    {
        if (str_starts_with($this->invoice, 'INV-')) {
            return $this->invoice;
        }
        return 'INV-' . $this->invoice;
    }

    // Additional accessors
    public function getHargaFormattedAttribute()
    {
        if ($this->harga) {
            return 'Rp ' . number_format($this->harga, 0, ',', '.');
        }
        
        // Fallback ke harga dari penerbitan
        if ($this->penerbitanIndividu && $this->penerbitanIndividu->harga_paket) {
            return 'Rp ' . number_format($this->penerbitanIndividu->harga_paket, 0, ',', '.');
        }
        
        return 'Rp 0';
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            return asset('storage/' . $this->bukti_pembayaran);
        }
        return null;
    }

    // Status check methods
    public function isMenungguVerifikasi()
    {
        return $this->status_pembayaran === 'menunggu_verifikasi';
    }

    public function isSukses()
    {
        return $this->status_pembayaran === 'sukses';
    }

    public function isTidakSesuai()
    {
        return $this->status_pembayaran === 'tidak_sesuai';
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    public function scopeByPaket($query, $paket)
    {
        return $query->where('paket', $paket);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status_pembayaran', 'menunggu_verifikasi');
    }

    public function scopeVerified($query)
    {
        return $query->where('status_pembayaran', 'sukses');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_pembayaran', 'tidak_sesuai');
    }
}
