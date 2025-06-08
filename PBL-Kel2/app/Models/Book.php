<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'buku';

   protected $fillable = [
        'judul_buku',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori_id',
        'harga',
        'stok',
        'deskripsi',
        'cover',
        'file_buku',
        'promo_id',
        'harga_promo',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_promo' => 'decimal:2',
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    // Relasi dengan kategori
    public function kategori()
    {
        return $this->belongsTo(\App\Models\KategoriBuku::class, 'kategori_id');
    }
    
    // Relasi dengan promo
    public function promo()
    {
        return $this->belongsTo(\App\Models\Promo::class, 'promo_id');
    }

    // Accessor untuk kompatibilitas dengan view yang menggunakan bahasa Inggris
    public function getTitleAttribute()
    {
        return $this->judul_buku;
    }

    public function getAuthorAttribute()
    {
        return $this->penulis;
    }

    public function getDescriptionAttribute()
    {
        return $this->deskripsi;
    }

    public function getCoverAttribute()
    {
        return $this->sampul;
    }

    public function getOriginalPriceAttribute()
    {
        return $this->harga_asli;
    }

    public function getDiscountPriceAttribute()
    {
        return $this->harga_diskon;
    }

    public function getEbookPriceAttribute()
    {
        return $this->harga_ebook;
    }

    // Format harga dalam bahasa Inggris (untuk kompatibilitas)
    public function getFormattedOriginalPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_asli, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_diskon, 0, ',', '.');
    }

    public function getFormattedEbookPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_ebook, 0, ',', '.');
    }

    // Format harga dalam bahasa Indonesia
    public function getHargaAsliFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_asli, 0, ',', '.');
    }

    public function getHargaDiskonFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_diskon, 0, ',', '.');
    }

    public function getHargaEbookFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_ebook, 0, ',', '.');
    }

    // Persentase diskon dalam bahasa Inggris (untuk kompatibilitas)
    public function getDiscountPercentageAttribute()
    {
        if ($this->harga_asli > 0 && $this->harga_diskon < $this->harga_asli) {
            return round((($this->harga_asli - $this->harga_diskon) / $this->harga_asli) * 100);
        }
        return 0;
    }

    // Persentase diskon dalam bahasa Indonesia
    public function getPersentaseDiskonAttribute()
    {
        if ($this->harga_asli > 0 && $this->harga_diskon < $this->harga_asli) {
            return round((($this->harga_asli - $this->harga_diskon) / $this->harga_asli) * 100);
        }
        return 0;
    }

    // URL sampul dalam bahasa Inggris (untuk kompatibilitas)
    public function getCoverUrlAttribute()
    {
        if ($this->sampul) {
            return asset('storage/covers/' . $this->sampul);
        }
        return null;
    }

    // URL sampul dalam bahasa Indonesia
    public function getSampulUrlAttribute()
    {
        if ($this->sampul) {
            return asset('storage/covers/' . $this->sampul);
        }
        return null;
    }

    // Method untuk menghitung harga dengan promo
    public function getHargaWithPromoAttribute()
    {
        if (!$this->promo || !$this->promo->isActive()) {
            return $this->harga_diskon ?: $this->harga_asli;
        }

        $basePrice = $this->harga_diskon ?: $this->harga_asli;
        
        if ($this->promo->tipe === 'Persentase') {
            $diskon = ($basePrice * $this->promo->besaran) / 100;
            return max(0, $basePrice - $diskon);
        } else {
            // Nominal
            return max(0, $basePrice - $this->promo->besaran);
        }
    }

    // Method untuk format harga dengan promo
    public function getFormattedHargaWithPromoAttribute()
    {
        return 'Rp ' . number_format($this->harga_with_promo, 0, ',', '.');
    }

    // Method untuk cek apakah ada promo aktif
    public function hasActivePromo()
    {
        return $this->promo && $this->promo->isActive();
    }

    // Method untuk mendapatkan penghematan dari promo
    public function getPromoSavingsAttribute()
    {
        if (!$this->hasActivePromo()) {
            return 0;
        }

        $basePrice = $this->harga_diskon ?: $this->harga_asli;
        return $basePrice - $this->harga_with_promo;
    }

    // Method untuk format penghematan promo
    public function getFormattedPromoSavingsAttribute()
    {
        $savings = $this->promo_savings;
        if ($savings > 0) {
            return 'Rp ' . number_format($savings, 0, ',', '.');
        }
        return null;
    }

    // Scope untuk pencarian berdasarkan judul atau penulis
    public function scopeSearch($query, $keyword)
    {
        return $query->where('judul_buku', 'like', '%' . $keyword . '%')
                    ->orWhere('penulis', 'like', '%' . $keyword . '%');
    }

    // Scope untuk mendapatkan buku yang memiliki diskon
    public function scopeWithDiscount($query)
    {
        return $query->where('harga_diskon', '<', 'harga_asli');
    }

    // Scope untuk buku berdasarkan penulis
    public function scopeByAuthor($query, $author)
    {
        return $query->where('penulis', $author);
    }

    // Scope untuk buku dengan harga dalam rentang tertentu
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('harga_diskon', [$min, $max]);
    }

    // Scope untuk buku dengan promo aktif
    public function scopeWithActivePromo($query)
    {
        return $query->whereHas('promo', function($q) {
            $q->where('status', 'Aktif')
              ->where('tanggal_mulai', '<=', now())
              ->where('tanggal_selesai', '>=', now())
              ->where(function($subQ) {
                  $subQ->whereNull('kuota')
                       ->orWhereRaw('kuota_terpakai < kuota');
              });
        });
    }

    // Method untuk mengecek apakah buku memiliki diskon
    public function hasDiscount()
    {
        return $this->harga_diskon < $this->harga_asli;
    }

    // Method untuk mendapatkan jumlah penghematan
    public function getSavingsAmount()
    {
        if ($this->hasDiscount()) {
            return $this->harga_asli - $this->harga_diskon;
        }
        return 0;
    }

    // Method untuk mendapatkan format penghematan
    public function getFormattedSavings()
    {
        $savings = $this->getSavingsAmount();
        if ($savings > 0) {
            return 'Rp ' . number_format($savings, 0, ',', '.');
        }
        return null;
    }

    // Method untuk mengecek apakah buku tersedia
    public function isAvailable()
    {
        return $this->stok_fisik > 0;
    }

    // Method untuk mendapatkan rating rata-rata (jika ada sistem rating)
    public function getAverageRating()
    {
        // Placeholder untuk sistem rating di masa depan
        return 0;
    }
}
