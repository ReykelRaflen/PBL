<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananKolaborasi extends Model
{
    use HasFactory;

    protected $table = 'pesanan_kolaborasi';

    protected $fillable = [
        'user_id',
        'buku_kolaboratif_id',
        'bab_buku_id',
        'nomor_pesanan',
        'jumlah_bayar',
        'status_pembayaran',
        'status_penulisan',
        'catatan',
        'tanggal_pesanan',
        'batas_pembayaran',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'tanggal_batal',
        'admin_id',
        'catatan_admin',
        'tanggal_verifikasi',
        'hasil_verifikasi',
        // Fields untuk naskah
        'file_naskah',
        'judul_naskah',
        'deskripsi_naskah',
        'jumlah_kata',
        'catatan_penulis',
        'tanggal_upload_naskah',
        'feedback_editor',
        'tanggal_feedback',
        'catatan_persetujuan',
        'tanggal_disetujui'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'batas_pembayaran' => 'datetime',
        'tanggal_bayar' => 'datetime',
        'tanggal_batal' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_upload_naskah' => 'datetime',
        'tanggal_feedback' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
        'jumlah_kata' => 'integer'
    ];

    // Event untuk otomatis buat laporan ketika upload bukti bayar
    protected static function booted()
    {
        static::updated(function ($pesanan) {
            $oldStatus = $pesanan->getOriginal('status_pembayaran');
            $newStatus = $pesanan->status_pembayaran;
                       
            // Jika status berubah ke pending dan ada bukti pembayaran
            if ($newStatus === 'pending' && $oldStatus !== 'pending' && $pesanan->bukti_pembayaran) {
                $pesanan->createLaporanPenjualan();
            }
        });
    }

    // Method untuk membuat laporan penjualan
    public function createLaporanPenjualan()
    {
        // Cek apakah sudah ada laporan untuk pesanan ini
        if ($this->laporanPenjualan()->exists()) {
            return;
        }

        \App\Models\LaporanPenjualanKolaborasi::create([
            'pesanan_kolaborasi_id' => $this->id,
            'judul' => $this->bukuKolaboratif->judul ?? 'Judul Tidak Ditemukan',
            'penulis' => $this->user->name ?? 'Penulis Tidak Ditemukan',
            'bab' => $this->babBuku->judul_bab ?? $this->babBuku->nama_bab ?? 'Bab Tidak Ditemukan',
            'nomor_invoice' => $this->nomor_pesanan,
            'jumlah_pembayaran' => $this->jumlah_bayar,
            'bukti_pembayaran' => $this->bukti_pembayaran,
            'status_pembayaran' => 'menunggu_verifikasi',
            'tanggal' => now()->toDateString(),
        ]);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function pengguna() // Alias untuk kompatibilitas
    {
        return $this->user();
    }

    public function bukuKolaboratif()
    {
        return $this->belongsTo(\App\Models\BukuKolaboratif::class);
    }

    public function babBuku()
    {
        return $this->belongsTo(\App\Models\BabBuku::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

    public function laporanPenjualan()
    {
        return $this->hasOne(\App\Models\LaporanPenjualanKolaborasi::class);
    }

    // Accessors
    public function getJumlahBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'pending' => 'bg-blue-100 text-blue-800',
            'menunggu_verifikasi' => 'bg-orange-100 text-orange-800',
            'lunas' => 'bg-green-100 text-green-800',
            'tidak_sesuai' => 'bg-red-100 text-red-800',
            'dibatalkan' => 'bg-gray-100 text-gray-800',
            'expired' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusPembayaranTextAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu' => 'Menunggu Pembayaran',
            'pending' => 'Menunggu Verifikasi',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'dibatalkan' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            'gagal' => 'Gagal',
            'tidak_sesuai' => 'Tidak Sesuai',
            default => ucfirst(str_replace('_', ' ', $this->status_pembayaran))
        };
    }

    public function getStatusPenulisanTextAttribute()
    {
        return match($this->status_penulisan) {
            'belum_mulai' => 'Belum Mulai',
            'dapat_mulai' => 'Dapat Mulai',
            'sedang_proses' => 'Sedang Proses',
            'sudah_kirim' => 'Sudah Dikirim',
            'revisi' => 'Perlu Revisi',
            'selesai' => 'Selesai',
            'disetujui' => 'Disetujui',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status_penulisan))
        };
    }

    public function getFileNaskahUrlAttribute()
    {
        if ($this->file_naskah) {
            return asset('storage/' . $this->file_naskah);
        }
        return null;
    }

    public function getFileNaskahNameAttribute()
    {
        if ($this->file_naskah) {
            return basename($this->file_naskah);
        }
        return null;
    }

    public function getFileNaskahSizeAttribute()
    {
        if ($this->file_naskah && \Storage::disk('public')->exists($this->file_naskah)) {
            $bytes = \Storage::disk('public')->size($this->file_naskah);
            return $this->formatBytes($bytes);
        }
        return null;
    }

        private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    // Status helpers
    public function isPembayaranLunas()
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function canUploadNaskah()
    {
        return $this->isPembayaranLunas() && 
               in_array($this->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi']);
    }

    public function hasNaskah()
    {
        return !empty($this->file_naskah);
    }

    public function isNaskahDirevisi()
    {
        return $this->status_penulisan === 'revisi';
    }

    public function isNaskahDisetujui()
    {
        return $this->status_penulisan === 'disetujui';
    }

    // Scopes
    public function scopeWithPaymentStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    public function scopeWithWritingStatus($query, $status)
    {
        return $query->where('status_penulisan', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePendingVerification($query)
    {
        return $query->whereIn('status_pembayaran', ['pending', 'menunggu_verifikasi']);
    }

    public function scopeCanStartWriting($query)
    {
        return $query->where('status_pembayaran', 'lunas')
                    ->where('status_penulisan', 'dapat_mulai');
    }

    public function scopeNeedReview($query)
    {
        return $query->where('status_penulisan', 'sudah_kirim');
    }
}
