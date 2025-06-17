<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'buku_id',
        'order_number',
        'tipe_buku',
        'quantity',
        'harga_satuan',
        'subtotal',
        'kode_promo',
        'diskon',
        'total',
        'alamat_pengiriman',
        'no_telepon',
        'catatan',
        'status',
        'tanggal_pesanan'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'kode_promo', 'kode_promo');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
            'menunggu_verifikasi' => 'bg-blue-100 text-blue-800',
            'terverifikasi' => 'bg-green-100 text-green-800',
            'diproses' => 'bg-purple-100 text-purple-800',
            'dikirim' => 'bg-indigo-100 text-indigo-800',
            'selesai' => 'bg-green-100 text-green-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Methods
    public static function generateOrderNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', "ORD-{$date}-%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "ORD-{$date}-{$newNumber}";
    }

    public function canUploadPayment()
    {
        return in_array($this->status, ['menunggu_pembayaran']) ||
            ($this->pembayaran && $this->pembayaran->status === 'ditolak');
    }

    public function canDownloadEbook()
    {
        // Pastikan ini adalah pesanan e-book
        if ($this->tipe_buku !== 'ebook') {
            return false;
        }

        // Pastikan pembayaran ada dan sudah terverifikasi
        if (!$this->pembayaran || $this->pembayaran->status !== 'terverifikasi') {
            return false;
        }

        // Pastikan status pesanan memungkinkan download
        if (!in_array($this->status, ['selesai', 'terverifikasi'])) {
            return false;
        }

        return true;
    }

    /**
     * Check if ebook file actually exists
     */
    public function hasEbookFile()
    {
        if (!$this->canDownloadEbook()) {
            return false;
        }

        $buku = $this->buku;
        if (!$buku || !$buku->file_buku) {
            return false;
        }

        // Check if file exists in storage
        $possiblePaths = [
            'ebooks/' . $buku->file_buku,
            'books/' . $buku->file_buku,
            'files/' . $buku->file_buku,
            'uploads/ebooks/' . $buku->file_buku,
            'uploads/books/' . $buku->file_buku,
            $buku->file_buku
        ];

        foreach ($possiblePaths as $path) {
            if (\Storage::disk('public')->exists($path) || \Storage::disk('local')->exists($path)) {
                return true;
            }
        }

        // Check absolute paths
        $absolutePaths = [
            storage_path('app/public/' . $buku->file_buku),
            storage_path('app/' . $buku->file_buku),
            public_path('storage/' . $buku->file_buku),
            public_path('uploads/' . $buku->file_buku)
        ];

        foreach ($absolutePaths as $path) {
            if (file_exists($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get ebook download info
     */
    public function getEbookInfo()
    {
        if (!$this->hasEbookFile()) {
            return null;
        }

        $buku = $this->buku;
        $info = [
            'title' => $buku->judul_buku,
            'author' => $buku->penulis,
            'format' => strtoupper(pathinfo($buku->file_buku, PATHINFO_EXTENSION)) ?: 'PDF',
            'size' => 'Unknown'
        ];

        // Try to get file size
        $possiblePaths = [
            'ebooks/' . $buku->file_buku,
            'books/' . $buku->file_buku,
            'files/' . $buku->file_buku,
            $buku->file_buku
        ];

        foreach ($possiblePaths as $path) {
            try {
                if (\Storage::disk('public')->exists($path)) {
                    $size = \Storage::disk('public')->size($path);
                    $info['size'] = $this->formatBytes($size);
                    break;
                } elseif (\Storage::disk('local')->exists($path)) {
                    $size = \Storage::disk('local')->size($path);
                    $info['size'] = $this->formatBytes($size);
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $info;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
