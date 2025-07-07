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
        'harga' => 'decimal:2',
        'estimasi_kata' => 'integer'
    ];

    // Konstanta untuk status
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_DIPESAN = 'dipesan';
    const STATUS_SELESAI = 'selesai';
    const STATUS_TIDAK_TERSEDIA = 'tidak_tersedia';

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
        return $query->where('status', self::STATUS_TERSEDIA);
    }

    // Scope untuk bab dipesan
    public function scopeDipesan($query)
    {
        return $query->where('status', self::STATUS_DIPESAN);
    }

    // Scope untuk bab selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    // Scope untuk bab tidak tersedia
    public function scopeTidakTersedia($query)
    {
        return $query->where('status', self::STATUS_TIDAK_TERSEDIA);
    }

    // Method untuk check apakah bab bisa dipesan
    public function bisaDipesan()
    {
        return $this->status === self::STATUS_TERSEDIA;
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
            case self::STATUS_TERSEDIA:
                return '<span class="badge bg-success">Tersedia</span>';
            case self::STATUS_DIPESAN:
                return '<span class="badge bg-warning">Dipesan</span>';
            case self::STATUS_SELESAI:
                return '<span class="badge bg-info">Selesai</span>';
            case self::STATUS_TIDAK_TERSEDIA:
                return '<span class="badge bg-danger">Tidak Tersedia</span>';
            default:
                return '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>';
        }
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_TERSEDIA => 'Tersedia',
            self::STATUS_DIPESAN => 'Dipesan',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_TIDAK_TERSEDIA => 'Tidak Tersedia',
            default => ucfirst(str_replace('_', ' ', $this->status))
        };
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

    // Methods untuk mengubah status
    public function markAsTersedia()
    {
        return $this->update(['status' => self::STATUS_TERSEDIA]);
    }

    public function markAsDipesan()
    {
        return $this->update(['status' => self::STATUS_DIPESAN]);
    }

    public function markAsSelesai()
    {
        return $this->update(['status' => self::STATUS_SELESAI]);
    }

    public function markAsTidakTersedia()
    {
        return $this->update(['status' => self::STATUS_TIDAK_TERSEDIA]);
    }

    // Check status methods
    public function isTersedia()
    {
        return $this->status === self::STATUS_TERSEDIA;
    }

    public function isDipesan()
    {
        return $this->status === self::STATUS_DIPESAN;
    }

    public function isSelesai()
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isTidakTersedia()
    {
        return $this->status === self::STATUS_TIDAK_TERSEDIA;
    }
}
