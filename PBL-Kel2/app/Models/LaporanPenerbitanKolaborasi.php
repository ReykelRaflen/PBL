<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LaporanPenerbitanKolaborasi extends Model
{
    use HasFactory;

    protected $table = 'laporan_penerbitan_kolaborasi';

    // Sesuaikan dengan kolom yang benar-benar ada di database
    protected $fillable = [
        'buku_kolaboratif_id',
        'kode_buku',
        'judul',
        'isbn',
        'tanggal_terbit',
        'status',
        'penerbit',
        'harga_jual',
        'jumlah_cetak',
        'catatan',
        'admin_id',
        'jumlah_terjual'
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
        'jumlah_terjual' => 'integer',
        'harga_jual' => 'decimal:2',
        'jumlah_cetak' => 'integer',
    ];

    // Relationships
    public function bukuKolaboratif()
    {
        return $this->belongsTo(BukuKolaboratif::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function naskahKolaborasi()
    {
        return $this->hasMany(PesananKolaborasi::class, 'buku_kolaboratif_id', 'buku_kolaboratif_id')
                    ->where('status_penulisan', 'disetujui')
                    ->whereNotNull('file_naskah');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'proses' => 'Dalam Proses',
            'pending' => 'Pending',
            'terbit' => 'Sudah Terbit',
            default => ucfirst($this->status)
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'proses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'pending' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'terbit' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }

    public function getJumlahTerjualFormattedAttribute()
    {
        return number_format($this->jumlah_terjual, 0, ',', '.');
    }

    public function getHargaJualFormattedAttribute()
    {
        return $this->harga_jual ? 'Rp ' . number_format($this->harga_jual, 0, ',', '.') : null;
    }

    public function getTotalBabBukuAttribute()
    {
        return $this->bukuKolaboratif ? $this->bukuKolaboratif->babBuku()->count() : 0;
    }

    public function getTotalBabDisetujuiAttribute()
    {
        return $this->naskahKolaborasi()->count();
    }

    public function getPersentaseSelesaiAttribute()
    {
        if ($this->total_bab_buku == 0) {
            return 0;
        }
        
        return round(($this->total_bab_disetujui / $this->total_bab_buku) * 100, 1);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_buku', 'like', "%{$search}%")
              ->orWhere('judul', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%")
              ->orWhere('penerbit', 'like', "%{$search}%")
              ->orWhere('catatan', 'like', "%{$search}%");
        });
    }

    // Methods
    public function updateProgress()
    {
        if (!$this->bukuKolaboratif) {
            return ['total_bab' => 0, 'bab_selesai' => 0, 'persentase' => 0];
        }

        $totalBab = $this->bukuKolaboratif->babBuku()->count();
        $babSelesai = $this->naskahKolaborasi()->count();
        
        // Auto update status berdasarkan progress
        if ($babSelesai >= $totalBab && $this->status === 'proses') {
            $this->update(['status' => 'pending']);
        }
        
        return [
            'total_bab' => $totalBab,
            'bab_selesai' => $babSelesai,
            'persentase' => $totalBab > 0 ? round(($babSelesai / $totalBab) * 100, 1) : 0
        ];
    }
}
