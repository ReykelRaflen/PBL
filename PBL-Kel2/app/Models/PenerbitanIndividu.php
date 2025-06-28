<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerbitanIndividu extends Model
{
    protected $table = 'penerbitan_individus';

    protected $fillable = [
        'pilihan_paket',
        'rekening_id',
        'judul_naskah',
        'nama_penulis',
        'deskripsi_singkat',
        'tanggal_penerbitan',
        'payment_receipt',
        'file_naskah', 
    ];

    public function rekening()
{
    return $this->belongsTo(Rekening::class);
}
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_penerbitan ? $this->tanggal_penerbitan->format('d-m-Y') : null;
    }
    

}
