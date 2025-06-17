<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'invoice_number',
        'metode_pembayaran',
        'bank_pengirim',
        'nama_pengirim',
        'nomor_rekening_pengirim',
        'jumlah_transfer',
        'bukti_pembayaran',
        'keterangan',
        'status',
        'catatan_admin',
        'tanggal_pembayaran',
        'tanggal_verifikasi',
        'verified_by'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'jumlah_transfer' => 'decimal:2'
    ];

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // TAMBAHAN: Relasi ke User melalui Pesanan
    public function user()
    {
        return $this->hasOneThrough(User::class, Pesanan::class, 'id', 'id', 'pesanan_id', 'user_id');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
            'terverifikasi' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Methods
    public static function generateInvoiceNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastInvoice = self::where('invoice_number', 'like', "INV-{$date}-%")
                          ->orderBy('invoice_number', 'desc')
                          ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "INV-{$date}-{$newNumber}";
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        return $this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : null;
    }
}
