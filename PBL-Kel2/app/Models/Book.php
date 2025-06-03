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
        'deskripsi',
        'sampul',
        'harga_asli',
        'harga_diskon',
        'harga_ebook',
    ];

    protected $casts = [
        'harga_asli' => 'integer',
        'harga_diskon' => 'integer',
        'harga_ebook' => 'integer',
    ];

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
        // Bisa ditambahkan logic untuk stok atau status ketersediaan
        return true;
    }

    // Method untuk mendapatkan rating rata-rata (jika ada sistem rating)
    public function getAverageRating()
    {
        // Placeholder untuk sistem rating di masa depan
        return 0;
    }

    // Relasi dengan model lain (contoh untuk masa depan)
    
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }
}
