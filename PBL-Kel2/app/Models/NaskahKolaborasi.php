<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NaskahKolaborasi extends Model
{
    use HasFactory;

    protected $table = 'naskah_kolaborasi';

    protected $fillable = [
        'pesanan_kolaborasi_id',
        'user_id',
        'buku_kolaboratif_id',
        'bab_buku_id',
        'nomor_pesanan',
        'judul_naskah',
        'deskripsi_naskah',
        'file_naskah',
        'jumlah_kata',
        'status_penulisan',
        'tanggal_upload_naskah',
        'tanggal_deadline',
        'feedback_editor',
        'tanggal_feedback',
        'catatan_persetujuan',
        'tanggal_disetujui',
        'catatan_penulis',
        'admin_id'
    ];

    protected $casts = [
        'tanggal_upload_naskah' => 'datetime',
        'tanggal_deadline' => 'datetime',
        'tanggal_feedback' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'jumlah_kata' => 'integer'
    ];

    // Relationships
    public function pesananKolaborasi()
    {
        return $this->belongsTo(PesananKolaborasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bukuKolaboratif()
    {
        return $this->belongsTo(BukuKolaboratif::class);
    }

    public function babBuku()
    {
        return $this->belongsTo(BabBuku::class, 'bab_buku_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accessors
    public function getStatusPenulisanTextAttribute()
    {
        return match($this->status_penulisan) {
            'belum_mulai' => 'Belum Mulai',
            'dapat_mulai' => 'Dapat Mulai',
            'sedang_ditulis' => 'Sedang Ditulis',
            'sudah_kirim' => 'Sudah Dikirim',
            'revisi' => 'Perlu Revisi',
            'disetujui' => 'Disetujui',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status_penulisan))
        };
    }

    public function getStatusPenulisanBadgeAttribute()
    {
        return match($this->status_penulisan) {
            'belum_mulai' => 'bg-gray-100 text-gray-800',
            'dapat_mulai' => 'bg-blue-100 text-blue-800',
            'sedang_ditulis' => 'bg-yellow-100 text-yellow-800',
            'sudah_kirim' => 'bg-purple-100 text-purple-800',
            'revisi' => 'bg-orange-100 text-orange-800',
            'disetujui' => 'bg-green-100 text-green-800',
            'selesai' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getJumlahKataFormattedAttribute()
    {
        return number_format($this->jumlah_kata ?? 0, 0, ',', '.');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_penulisan', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul_naskah', 'like', "%{$search}%")
              ->orWhere('nomor_pesanan', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Methods
    public function updateStatus($status, $adminId = null, $feedback = null, $catatan = null)
    {
        $updateData = [
            'status_penulisan' => $status,
            'admin_id' => $adminId
        ];

        if ($feedback) {
            $updateData['feedback_editor'] = $feedback;
            $updateData['tanggal_feedback'] = now();
        }

        if ($catatan && $status === 'disetujui') {
            $updateData['catatan_persetujuan'] = $catatan;
            $updateData['tanggal_disetujui'] = now();
        }

        return $this->update($updateData);
    }
}
