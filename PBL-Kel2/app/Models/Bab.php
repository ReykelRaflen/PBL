<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bab extends Model
{
    protected $table = 'bab';
    protected $fillable = ['buku_id', 'judul', 'harga', 'deadline', 'status', 'bab', 'status_pembayaran', 'bukti_pembayaran', 'nama_penulis', 'file_naskah', 'tanggal_penerbitan'];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
    protected $casts = [
    'deadline' => 'datetime',
];
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function getFormattedDeadlineAttribute()
    {
        return $this->deadline ? $this->deadline->format('d-m-Y') : 'Tidak ada deadline';
    }
}

