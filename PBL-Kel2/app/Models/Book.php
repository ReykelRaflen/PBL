<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'cover',
        'original_price',
        'discount_price',
        'ebook_price'
    ];

    protected $casts = [
        'original_price' => 'integer',
        'discount_price' => 'integer',
        'ebook_price' => 'integer',
    ];

    // Accessor untuk format harga
    public function getFormattedOriginalPriceAttribute()
    {
        return 'Rp ' . number_format($this->original_price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute()
    {
        return 'Rp ' . number_format($this->discount_price, 0, ',', '.');
    }

    public function getFormattedEbookPriceAttribute()
    {
        return 'Rp ' . number_format($this->ebook_price, 0, ',', '.');
    }

    // Accessor untuk menghitung persentase diskon
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price > 0) {
            return round((($this->original_price - $this->discount_price) / $this->original_price) * 100);
        }
        return 0;
    }

    // Accessor untuk menghitung total hemat
    public function getSavingsAmountAttribute()
    {
        return $this->original_price - $this->discount_price;
    }

    public function getFormattedSavingsAmountAttribute()
    {
        return 'Rp ' . number_format($this->savings_amount, 0, ',', '.');
    }

    // Accessor untuk deskripsi yang dipotong
    public function getShortDescriptionAttribute()
    {
        if (strlen($this->description) > 100) {
            return substr($this->description, 0, 100) . '...';
        }
        return $this->description;
    }
}
