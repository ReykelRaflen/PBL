<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_buku';

    protected $fillable = [
        'nama',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relasi dengan books
    public function books()
    {
        return $this->hasMany(Book::class, 'kategori_id');
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
