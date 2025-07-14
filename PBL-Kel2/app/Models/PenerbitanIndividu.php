<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerbitanIndividu extends Model
{
    use HasFactory;

    protected $table = 'penerbitan_individu';

    protected $fillable = [
        'user_id',
        'nomor_pesanan',
        'paket',
        'harga_paket',
        'tanggal_pesanan', // Missing in your model but used in controller
        'metode_pembayaran',
        'bank_pengirim',
        'bank_tujuan',
        'bukti_pembayaran',
        'catatan_pembayaran',
        'status_pembayaran',
        'status_penerbitan',
        'tanggal_bayar',
        'tanggal_verifikasi',
        'admin_id',
        'catatan_admin',
        'judul_buku',
        'nama_penulis',
        'file_naskah',
        'deskripsi_singkat', // Changed from deskripsi_buku to match controller
        'tanggal_upload_naskah',
        'feedback_editor',
        'tanggal_feedback',
        'catatan_persetujuan',
        'tanggal_disetujui'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime', // Added this cast
        'tanggal_bayar' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_upload_naskah' => 'datetime',
        'tanggal_feedback' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'harga_paket' => 'decimal:2'
    ];

    // Static method untuk mendapatkan opsi paket
    public static function getPaketOptions()
    {
        return [
            'silver' => [
                'nama' => 'Silver',
                'harga' => 1500000,
                'gambar' => 'img/paket-silver.jpg', // Added to match controller
                'fitur' => [
                    'Maksimal 3 Penulis',
                    'Editing',
                    'Layout Naskah',
                    'Desain Cover',
                    'Sertifikat Penulis',
                    'Royalti 20%',
                    'Terindeks Google Schoolar',
                    'Buku Cetak 10 Eksemplar'
                ]
            ],
            'gold' => [
                'nama' => 'Gold',
                'harga' => 3500000,
                'gambar' => 'img/paket-gold.jpg', // Added to match controller
                'fitur' => [
                    'Maksimal 3 Penulis',
                    'Editing',
                    'Layout Naskah',
                    'Desain Cover',
                    'Sertifikat Penulis',
                    'Royalti 20%',
                    'Terindeks Google Schoolar',
                    'Buku Cetak 20 Eksemplar'
                ]
            ],
            'diamond' => [
                'nama' => 'Diamond',
                'harga' => 5000000, // Updated to match controller (was 1500000)
                'gambar' => 'img/paket-diamond.jpg', // Added to match controller
                'fitur' => [
                    'Maksimal 3 Penulis',
                    'Editing',
                    'Layout Naskah',
                    'Desain Cover',
                    'Sertifikat Penulis',
                    'Royalti 20%',
                    'Terindeks Google Schoolar',
                    'Buku Cetak 30 Eksemplar',
                    'Sertifikat HAKI'
                ]
            ]
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporanPenerbitan()
    {
        return $this->hasOne(LaporanBukuIndividu::class, 'penerbitan_individu_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function laporanPenjualan()
    {
        return $this->hasOne(LaporanPenjualanIndividu::class);
    }

    // Accessors
    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            return asset('storage/' . $this->bukti_pembayaran);
        }
        return null;
    }

    public function getFileNaskahUrlAttribute()
    {
        if ($this->file_naskah) {
            return asset('storage/' . $this->file_naskah);
        }
        return null;
    }

    public function getHargaPaketFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_paket, 0, ',', '.');
    }

    public function getStatusPembayaranBadgeAttribute()
    {
        return match ($this->status_pembayaran) {
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'pending' => 'bg-blue-100 text-blue-800',
            'lunas' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'dibatalkan' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusPembayaranTextAttribute()
    {
        return match ($this->status_pembayaran) {
            'menunggu' => 'Menunggu Pembayaran',
            'pending' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status_pembayaran))
        };
    }

    public function getStatusPenerbitanBadgeAttribute()
    {
        return match ($this->status_penerbitan) {
            'belum_mulai' => 'bg-gray-100 text-gray-800',
            'dapat_mulai' => 'bg-blue-100 text-blue-800',
            'sudah_kirim' => 'bg-purple-100 text-purple-800',
            'revisi' => 'bg-orange-100 text-orange-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusPenerbitanTextAttribute()
    {
        return match ($this->status_penerbitan) {
            'belum_mulai' => 'Belum Mulai',
            'dapat_mulai' => 'Dapat Mulai',
            'sudah_kirim' => 'Sudah Dikirim',
            'revisi' => 'Perlu Revisi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $this->status_penerbitan))
        };
    }

    // Status helpers
    public function isPembayaranLunas()
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function canUploadNaskah()
    {
        return $this->isPembayaranLunas() &&
            in_array($this->status_penerbitan, ['dapat_mulai', 'revisi']);
    }

    public function hasNaskah()
    {
        return !empty($this->file_naskah);
    }

    public function isNaskahDirevisi()
    {
        return $this->status_penerbitan === 'revisi';
    }

    public function isNaskahDisetujui()
    {
        return $this->status_penerbitan === 'disetujui';
    }

    // Scopes
    public function scopeWithPaymentStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    public function scopeWithPublishingStatus($query, $status)
    {
        return $query->where('status_penerbitan', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    public function scopeCanStartPublishing($query)
    {
        return $query->where('status_pembayaran', 'lunas')
            ->where('status_penerbitan', 'dapat_mulai');
    }

    public function scopeNeedReview($query)
    {
        return $query->where('status_penerbitan', 'sudah_kirim');
    }
}
