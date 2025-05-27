<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DesignSampul extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'designsampul';   

    protected $fillable = [
        'nama_proyek',
        'jenis_design',
        'editor',
        'tanggal_kirim',
        'sampul',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    public function getSampulUrlAttribute(): ?string
    {
        return $this->sampul ? Storage::url($this->sampul) : null;
    }
}
