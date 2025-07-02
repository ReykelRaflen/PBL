<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabBuku extends Model
{
    use HasFactory;

    protected $table = 'bab_buku';

    protected $fillable = [
        'buku_kolaboratif_id',
        'nomor_bab',
        'judul_bab',
        'deskripsi_bab',
        'tingkat_kesulitan',
        'estimasi_kata',
        'harga',
        'deadline',
        'status',
        'user_id', // untuk penulis yang mengambil bab ini
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan BukuKolaboratif
    public function bukuKolaboratif()
    {
        return $this->belongsTo(BukuKolaboratif::class, 'buku_kolaboratif_id');
    }

    // Relationship dengan User (penulis)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan PesananKolaborasi
    public function pesananKolaborasi()
    {
        return $this->hasMany(PesananKolaborasi::class, 'bab_buku_id');
    }

    // Method untuk mendapatkan pesanan aktif
    public function pesananAktif()
    {
        return $this->hasOne(PesananKolaborasi::class, 'bab_buku_id')
                    ->whereIn('status_pembayaran', ['pending', 'lunas'])
                    ->latest();
    }

    // Scope untuk bab tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Scope untuk bab dipesan
    public function scopeDipesan($query)
    {
        return $query->where('status', 'dipesan');
    }

    // Scope untuk bab selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Method untuk check apakah bab bisa dipesan
    public function bisaDipesan()
    {
        return $this->status === 'tersedia';
    }

    // Method untuk mendapatkan harga bab
    public function getHargaBab()
    {
        return $this->harga ?? $this->bukuKolaboratif->harga_per_bab ?? 0;
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'tersedia':
                return '<span class="badge bg-success">Tersedia</span>';
            case 'dipesan':
                return '<span class="badge bg-warning">Dipesan</span>';
            case 'selesai':
                return '<span class="badge bg-info">Selesai</span>';
            default:
                return '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>';
        }
    }

    // Accessor untuk tingkat kesulitan badge
    public function getTingkatKesulitanBadgeAttribute()
    {
        switch ($this->tingkat_kesulitan) {
            case 'mudah':
                return '<span class="badge bg-success"><i class="fas fa-star"></i> Mudah</span>';
            case 'sedang':
                return '<span class="badge bg-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i> Sedang</span>';
            case 'sulit':
                return '<span class="badge bg-danger"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> Sulit</span>';
            default:
                return '<span class="badge bg-secondary">Belum Ditentukan</span>';
        }
    }
}
