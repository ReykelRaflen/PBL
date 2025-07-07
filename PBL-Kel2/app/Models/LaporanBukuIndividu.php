<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LaporanBukuIndividu extends Model
{
    protected $table = 'laporan_penerbitan_individu';

    protected $fillable = [
        'kode_buku', 
        'judul', 
        'penulis', 
        'isbn',
        'tanggal_terbit',
        'status',
        'penerbitan_individu_id'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke PenerbitanIndividu
     */
    public function penerbitanIndividu(): BelongsTo
    {
        return $this->belongsTo(PenerbitanIndividu::class, 'penerbitan_individu_id');
    }

    /**
     * Generate kode buku otomatis
     */
    public static function generateKodeBuku(): string
    {
        $year = date('Y');
        $month = date('m');
        
        // Cari kode buku terakhir untuk bulan ini
        $lastCode = static::where('kode_buku', 'like', "BK{$year}{$month}%")
            ->orderBy('kode_buku', 'desc')
            ->first();

        if ($lastCode) {
            // Ambil 3 digit terakhir dan tambah 1
            $lastNumber = (int) substr($lastCode->kode_buku, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "BK{$year}{$month}" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-orange-500',
            'proses' => 'bg-yellow-500',
            'terbit' => 'bg-green-500',
            default => 'bg-gray-500'
        };
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'proses' => 'Proses',
            'terbit' => 'Terbit',
            default => ucfirst($this->status)
        };
    }

    /**
     * Accessor untuk tanggal terbit formatted
     */
    public function getTanggalTerbitFormattedAttribute(): string
    {
        if (!$this->tanggal_terbit) {
            return 'Belum diterbitkan';
        }

        return $this->tanggal_terbit->format('d/m/Y');
    }

    /**
     * Accessor untuk paket badge (dari relasi)
     */
    public function getPaketBadgeAttribute(): string
    {
        if (!$this->penerbitanIndividu) {
            return 'bg-gray-500';
        }

        return match ($this->penerbitanIndividu->paket) {
            'silver' => 'bg-gray-500',
            'gold' => 'bg-yellow-500',
            'diamond' => 'bg-blue-500',
            default => 'bg-gray-500'
        };
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('kode_buku', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Boot method untuk auto-generate kode buku
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_buku)) {
                $model->kode_buku = static::generateKodeBuku();
            }
        });
    }
}
