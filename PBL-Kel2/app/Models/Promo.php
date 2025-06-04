<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_promo',
        'keterangan',
        'tipe',
        'besaran',
        'kuota',
        'kuota_terpakai',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'besaran' => 'decimal:2',
        'kuota' => 'integer',
        'kuota_terpakai' => 'integer',
    ];

    public function getJangkaWaktuAttribute()
    {
        return $this->tanggal_mulai->format('d/m/Y') . ' - ' . $this->tanggal_selesai->format('d/m/Y');
    }

    public function getKuotaTersisaAttribute()
    {
        if ($this->kuota === null) {
            return 'Tidak terbatas';
        }
        return $this->kuota - $this->kuota_terpakai;
    }

    public function getInfoAttribute()
    {
        $tipe = $this->tipe === 'Persentase' ? '%' : 'Rp';
        $besaran = $this->tipe === 'Persentase' ? $this->besaran : number_format($this->besaran, 0, ',', '.');
        $kuota = $this->kuota === null ? 'Tidak terbatas' : $this->kuota_tersisa . '/' . $this->kuota;
        
        return "Tipe: {$this->tipe}, Besaran: {$besaran}{$tipe}, Kuota: {$kuota}";
    }

    public function isActive()
    {
        $today = Carbon::today();
        $hasQuota = $this->kuota === null || $this->kuota_terpakai < $this->kuota;
        
        return $this->status === 'Aktif' && 
               $today->between($this->tanggal_mulai, $this->tanggal_selesai) &&
               $hasQuota;
    }
}
