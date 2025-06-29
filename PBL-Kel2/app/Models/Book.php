<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul_buku',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori_id',
        'harga',
        'harga_ebook', // Harga e-book manual
        'stok',
        'deskripsi',
        'cover',
        'file_buku',
        'promo_id',
        'harga_promo',
        'harga_ebook_promo', // Harga e-book setelah promo
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_ebook' => 'decimal:2',
        'harga_promo' => 'decimal:2',
        'harga_ebook_promo' => 'decimal:2',
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

    // ========== HARGA BUKU FISIK ==========

    /**
     * Mendapatkan harga final fisik (dengan promo jika ada)
     */
    public function getHargaFinalAttribute()
    {
        return $this->harga_promo ?: $this->harga;
    }

    /**
     * Format harga fisik
     */
    public function getHargaFormatAttribute()
    {
        return $this->harga ? 'Rp ' . number_format($this->harga, 0, ',', '.') : null;
    }

    /**
     * Format harga promo fisik
     */
    public function getHargaPromoFormatAttribute()
    {
        return $this->harga_promo ? 'Rp ' . number_format($this->harga_promo, 0, ',', '.') : null;
    }

    /**
     * Format harga final fisik
     */
    public function getHargaFinalFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_final, 0, ',', '.');
    }

    // ========== HARGA E-BOOK ==========

    /**
     * Mendapatkan harga final e-book (dengan promo jika ada)
     */
    public function getHargaEbookFinalAttribute()
    {
        return $this->harga_ebook_promo ?: $this->harga_ebook;
    }

    /**
     * Format harga e-book
     */
    public function getHargaEbookFormatAttribute()
    {
        return $this->harga_ebook ? 'Rp ' . number_format($this->harga_ebook, 0, ',', '.') : null;
    }

    /**
     * Format harga e-book promo
     */
    public function getHargaEbookPromoFormatAttribute()
    {
        return $this->harga_ebook_promo ? 'Rp ' . number_format($this->harga_ebook_promo, 0, ',', '.') : null;
    }

    /**
     * Format harga final e-book
     */
    public function getHargaEbookFinalFormatAttribute()
    {
        return $this->harga_ebook_final ? 'Rp ' . number_format($this->harga_ebook_final, 0, ',', '.') : null;
    }

    // ========== DISKON & PROMO ==========

    /**
     * Cek apakah buku fisik memiliki diskon
     */
    public function hasDiscount()
    {
        return $this->harga_promo && $this->harga_promo < $this->harga;
    }

    /**
     * Cek apakah e-book memiliki diskon
     */
    public function hasEbookDiscount()
    {
        return $this->harga_ebook_promo && $this->harga_ebook_promo < $this->harga_ebook;
    }

    /**
     * Persentase diskon buku fisik
     */
    public function getDiscountPercentage()
    {
        if ($this->hasDiscount()) {
            return round((($this->harga - $this->harga_promo) / $this->harga) * 100);
        }
        return 0;
    }

    /**
     * Persentase diskon e-book
     */
    public function getEbookDiscountPercentage()
    {
        if ($this->hasEbookDiscount()) {
            return round((($this->harga_ebook - $this->harga_ebook_promo) / $this->harga_ebook) * 100);
        }
        return 0;
    }

    /**
     * Penghematan buku fisik
     */
    public function getSavingsAmount()
    {
        if ($this->hasDiscount()) {
            return $this->harga - $this->harga_promo;
        }
        return 0;
    }

    /**
     * Penghematan e-book
     */
    public function getEbookSavingsAmount()
    {
        if ($this->hasEbookDiscount()) {
            return $this->harga_ebook - $this->harga_ebook_promo;
        }
        return 0;
    }

    /**
     * Format penghematan fisik
     */
    public function getSavingsFormatAttribute()
    {
        $savings = $this->getSavingsAmount();
        return $savings > 0 ? 'Rp ' . number_format($savings, 0, ',', '.') : null;
    }

    /**
     * Format penghematan e-book
     */
    public function getEbookSavingsFormatAttribute()
    {
        $savings = $this->getEbookSavingsAmount();
        return $savings > 0 ? 'Rp ' . number_format($savings, 0, ',', '.') : null;
    }

    // ========== KETERSEDIAAN ==========

    /**
     * Cek apakah buku fisik tersedia
     */
    public function isAvailable()
    {
        return $this->stok > 0;
    }

    /**
     * Cek apakah e-book tersedia
     */
    public function hasEbook()
    {
        return !empty($this->file_buku) && !empty($this->harga_ebook);
    }

    /**
     * Cek apakah ada cover
     */
    public function hasCover()
    {
        return !empty($this->cover);
    }

    /**
     * URL cover
     */
    public function getCoverUrlAttribute()
    {
        if ($this->cover) {
            return asset('storage/' . $this->cover);
        }
        return null;
    }

    /**
     * Cek apakah ada promo aktif
     */
    public function hasActivePromo()
    {
        return $this->promo && $this->promo->isActive();
    }

    // ========== HELPER METHODS ==========

    /**
     * Method untuk menghitung harga promo otomatis
     */
    public function calculatePromoPrice()
    {
        if ($this->promo && $this->promo->isActive()) {
            $promo = $this->promo;
            
            // Hitung promo untuk buku fisik
            if ($this->harga) {
                if ($promo->tipe === 'Persentase') {
                    $this->harga_promo = $this->harga * (1 - $promo->besaran / 100);
                } else {
                    $this->harga_promo = max(0, $this->harga - $promo->besaran);
                }
            }
            
            // Hitung promo untuk e-book
            if ($this->harga_ebook) {
                if ($promo->tipe === 'Persentase') {
                    $this->harga_ebook_promo = $this->harga_ebook * (1 - $promo->besaran / 100);
                } else {
                    // Untuk promo nominal, berikan diskon proporsional untuk e-book
                    $diskonProporsional = ($this->harga_ebook / $this->harga) * $promo->besaran;
                    $this->harga_ebook_promo = max(0, $this->harga_ebook - $diskonProporsional);
                }
            }
        } else {
            $this->harga_promo = null;
            $this->harga_ebook_promo = null;
        }
    }

    /**
     * Method untuk mendapatkan informasi lengkap harga
     */
    public function getPriceInfo()
    {
        return [
            'fisik' => [
                'harga_asli' => $this->harga,
                'harga_promo' => $this->harga_promo,
                'harga_final' => $this->harga_final,
                'formatted' => $this->harga_final_format,
                'has_discount' => $this->hasDiscount(),
                'discount_percentage' => $this->getDiscountPercentage(),
                'savings' => $this->getSavingsAmount(),
                'available' => $this->isAvailable(),
            ],
            'ebook' => [
                'harga_asli' => $this->harga_ebook,
                'harga_promo' => $this->harga_ebook_promo,
                'harga_final' => $this->harga_ebook_final,
                'formatted' => $this->harga_ebook_final_format,
                'has_discount' => $this->hasEbookDiscount(),
                'discount_percentage' => $this->getEbookDiscountPercentage(),
                'savings' => $this->getEbookSavingsAmount(),
                'available' => $this->hasEbook(),
            ]
        ];
    }

    // ========== SCOPES ==========

    public function scopeSearch($query, $keyword)
    {
        return $query->where('judul_buku', 'like', '%' . $keyword . '%')
                    ->orWhere('penulis', 'like', '%' . $keyword . '%');
    }

    public function scopeWithDiscount($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('harga_promo')->whereColumn('harga_promo', '<', 'harga')
              ->orWhere(function($subQ) {
                  $subQ->whereNotNull('harga_ebook_promo')
                       ->whereColumn('harga_ebook_promo', '<', 'harga_ebook');
              });
        });
    }

    public function scopeHasEbook($query)
    {
        return $query->whereNotNull('file_buku')
                    ->whereNotNull('harga_ebook');
    }

    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

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

    // ========== EVENTS ==========

    protected static function boot()
    {
        parent::boot();

        // Auto calculate promo price when saving
        static::saving(function ($book) {
            $book->calculatePromoPrice();
        });
    }
}
