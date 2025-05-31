<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    protected $table = 'kategori_buku';
    protected $fillable = ['id_buku','kode_kategori', 'nama_kategori', 'deskripsi', 'total_buku', 'status'];
}
