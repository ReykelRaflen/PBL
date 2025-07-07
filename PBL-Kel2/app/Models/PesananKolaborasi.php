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

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
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

        try {
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
        } catch (\Exception $e) {
            \Log::error('Error creating laporan penjualan', [
                'pesanan_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
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

    public function getStatusPembayaranTextAttribute()
    {
        return match ($this->status_pembayaran) {
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
        return match ($this->status_penulisan) {
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

    // Tambahkan method ini jika belum ada
    public function canUploadNaskah()
    {
        return $this->status_pembayaran === 'lunas' &&
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

}
