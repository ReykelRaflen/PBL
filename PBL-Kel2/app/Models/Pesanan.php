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
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
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
            'menunggu_pembayaran' => 'bg-warning text-dark',
            'menunggu_verifikasi' => 'bg-info text-white',
            'terverifikasi' => 'bg-primary text-white',
            'diproses' => 'bg-info text-white',
            'dikirim' => 'bg-purple text-white',
            'selesai' => 'bg-success text-white',
            'dibatalkan' => 'bg-danger text-white',
            default => 'bg-secondary text-white'
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

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status === 'menunggu_pembayaran';
    }

    /**
     * Cancel the order
     */
    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update(['status' => 'dibatalkan']);
        return true;
    }

    /**
     * Get formatted price
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedDiskonAttribute()
    {
        return 'Rp ' . number_format($this->diskon, 0, ',', '.');
    }

    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Get order progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 20,
            'menunggu_verifikasi' => 40,
            'terverifikasi' => 60,
            'diproses' => 80,
            'dikirim' => 90,
            'selesai' => 100,
            'dibatalkan' => 0,
            default => 10
        };
    }

    /**
     * Get progress color
     */
    public function getProgressColorAttribute()
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'warning',
            'menunggu_verifikasi' => 'info',
            'terverifikasi' => 'primary',
            'diproses' => 'info',
            'dikirim' => 'purple',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tipe buku
     */
    public function scopeByTipeBuku($query, $tipe)
    {
        return $query->where('tipe_buku', $tipe);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('tanggal_pesanan', [$startDate, $endDate]);
        }
        
        return $query->whereDate('tanggal_pesanan', '>=', $startDate);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('order_number', 'like', '%' . $search . '%')
              ->orWhereHas('buku', function($bookQuery) use ($search) {
                  $bookQuery->where('judul_buku', 'like', '%' . $search . '%')
                           ->orWhere('penulis', 'like', '%' . $search . '%');
              });
        });
    }

    /**
     * Get payment status text
     */
    public function getPaymentStatusTextAttribute()
    {
        if (!$this->pembayaran) {
            return 'Belum Bayar';
        }

        return match($this->pembayaran->status) {
            'pending' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get payment status badge
     */
    public function getPaymentStatusBadgeAttribute()
    {
        if (!$this->pembayaran) {
            return 'bg-secondary text-white';
        }

        return match($this->pembayaran->status) {
            'pending' => 'bg-warning text-dark',
            'terverifikasi' => 'bg-success text-white',
            'ditolak' => 'bg-danger text-white',
            default => 'bg-secondary text-white'
        };
    }

    /**
     * Check if order is for physical book
     */
    public function isPhysicalBook()
    {
        return $this->tipe_buku === 'fisik';
    }

    /**
     * Check if order is for ebook
     */
    public function isEbook()
    {
        return $this->tipe_buku === 'ebook';
    }

    /**
     * Get shipping info if available
     */
    public function getShippingInfoAttribute()
    {
        if (!$this->isPhysicalBook()) {
            return null;
        }

        return [
            'alamat' => $this->alamat_pengiriman,
            'no_telepon' => $this->no_telepon,
            'status' => $this->status
        ];
    }

    /**
     * Get order timeline
     */
    public function getTimelineAttribute()
    {
        $timeline = [
            [
                'status' => 'menunggu_pembayaran',
                'title' => 'Pesanan Dibuat',
                'description' => 'Pesanan berhasil dibuat, menunggu pembayaran',
                'date' => $this->created_at,
                'completed' => true
            ]
        ];

        if ($this->pembayaran) {
            $timeline[] = [
                'status' => 'menunggu_verifikasi',
                'title' => 'Pembayaran Diupload',
                'description' => 'Bukti pembayaran telah diupload',
                'date' => $this->pembayaran->created_at,
                'completed' => true
            ];

            if ($this->pembayaran->status === 'terverifikasi') {
                $timeline[] = [
                    'status' => 'terverifikasi',
                    'title' => 'Pembayaran Terverifikasi',
                    'description' => 'Pembayaran telah diverifikasi oleh admin',
                    'date' => $this->pembayaran->updated_at,
                    'completed' => true
                ];
            }
        }

        if (in_array($this->status, ['diproses', 'dikirim', 'selesai'])) {
            $timeline[] = [
                'status' => 'diproses',
                'title' => 'Pesanan Diproses',
                'description' => $this->isPhysicalBook() ? 'Buku sedang disiapkan' : 'E-book sedang diproses',
                'date' => $this->updated_at,
                'completed' => true
            ];
        }

               if (in_array($this->status, ['dikirim', 'selesai']) && $this->isPhysicalBook()) {
            $timeline[] = [
                'status' => 'dikirim',
                'title' => 'Pesanan Dikirim',
                'description' => 'Buku sedang dalam perjalanan',
                'date' => $this->updated_at,
                'completed' => true
            ];
        }

        if ($this->status === 'selesai') {
            $timeline[] = [
                'status' => 'selesai',
                'title' => 'Pesanan Selesai',
                'description' => $this->isPhysicalBook() ? 'Buku telah diterima' : 'E-book siap didownload',
                'date' => $this->updated_at,
                'completed' => true
            ];
        }

        if ($this->status === 'dibatalkan') {
            $timeline[] = [
                'status' => 'dibatalkan',
                'title' => 'Pesanan Dibatalkan',
                'description' => 'Pesanan telah dibatalkan',
                'date' => $this->updated_at,
                'completed' => true
            ];
        }

        return collect($timeline);
    }

    /**
     * Get order summary for display
     */
    public function getSummaryAttribute()
    {
        return [
            'order_number' => $this->order_number,
            'book_title' => $this->buku->judul_buku ?? 'Buku Tidak Ditemukan',
            'book_author' => $this->buku->penulis ?? 'Penulis Tidak Diketahui',
            'book_type' => $this->tipe_buku,
            'quantity' => $this->quantity,
            'total_price' => $this->total,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'order_date' => $this->tanggal_pesanan,
            'can_cancel' => $this->canBeCancelled(),
            'can_upload_payment' => $this->canUploadPayment(),
            'can_download' => $this->canDownloadEbook(),
            'has_payment' => $this->pembayaran !== null,
            'payment_status' => $this->pembayaran->status ?? null,
        ];
    }

    /**
     * Static method to get status options
     */
    public static function getStatusOptions()
    {
        return [
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];
    }

    /**
     * Static method to get book type options
     */
    public static function getTipeBukuOptions()
    {
        return [
            'fisik' => 'Buku Fisik',
            'ebook' => 'E-book',
        ];
    }

    /**
     * Get orders by user with statistics
     */
    public static function getOrderStatistics($userId)
    {
        $orders = self::where('user_id', $userId)->get();
        
        return [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total'),
            'pending_payment' => $orders->where('status', 'menunggu_pembayaran')->count(),
            'pending_verification' => $orders->where('status', 'menunggu_verifikasi')->count(),
            'processing' => $orders->whereIn('status', ['terverifikasi', 'diproses'])->count(),
            'shipping' => $orders->where('status', 'dikirim')->count(),
            'completed' => $orders->where('status', 'selesai')->count(),
            'cancelled' => $orders->where('status', 'dibatalkan')->count(),
            'physical_books' => $orders->where('tipe_buku', 'fisik')->count(),
            'ebooks' => $orders->where('tipe_buku', 'ebook')->count(),
        ];
    }

    /**
     * Boot method untuk auto-generate order number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesanan) {
            if (empty($pesanan->order_number)) {
                $pesanan->order_number = self::generateOrderNumber();
            }
            
            if (empty($pesanan->tanggal_pesanan)) {
                $pesanan->tanggal_pesanan = now();
            }
        });
    }

    /**
     * Get download URL for ebook
     */
    public function getEbookDownloadUrl()
    {
        if (!$this->canDownloadEbook()) {
            return null;
        }

        return route('user.pesanan.download', $this->id);
    }

    /**
     * Get payment URL
     */
    public function getPaymentUrl()
    {
        if (!$this->canUploadPayment()) {
            return null;
        }

        return route('user.pesanan.payment', $this->id);
    }

    /**
     * Get detail URL
     */
    public function getDetailUrl()
    {
        return route('user.pesanan.show', $this->id);
    }

    /**
     * Check if order has expired (for pending payment)
     */
    public function isExpired($hours = 24)
    {
        if ($this->status !== 'menunggu_pembayaran') {
            return false;
        }

        return $this->created_at->addHours($hours)->isPast();
    }

    /**
     * Auto expire old pending orders
     */
    public static function expireOldOrders($hours = 24)
    {
        return self::where('status', 'menunggu_pembayaran')
            ->where('created_at', '<', now()->subHours($hours))
            ->update(['status' => 'dibatalkan']);
    }

    /**
     * Get recent orders
     */
    public static function getRecentOrders($userId, $limit = 5)
    {
        return self::where('user_id', $userId)
            ->with(['buku', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search orders
     */
    public static function searchOrders($userId, $query)
    {
        return self::where('user_id', $userId)
            ->where(function($q) use ($query) {
                $q->where('order_number', 'like', '%' . $query . '%')
                  ->orWhereHas('buku', function($bookQuery) use ($query) {
                      $bookQuery->where('judul_buku', 'like', '%' . $query . '%')
                               ->orWhere('penulis', 'like', '%' . $query . '%');
                  });
            })
            ->with(['buku', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get orders by status
     */
    public static function getOrdersByStatus($userId, $status)
    {
        return self::where('user_id', $userId)
            ->where('status', $status)
            ->with(['buku', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get orders by book type
     */
    public static function getOrdersByBookType($userId, $type)
    {
        return self::where('user_id', $userId)
            ->where('tipe_buku', $type)
            ->with(['buku', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get orders by date range
     */
    public static function getOrdersByDateRange($userId, $startDate, $endDate = null)
    {
        $query = self::where('user_id', $userId);
        
        if ($endDate) {
            $query->whereBetween('tanggal_pesanan', [$startDate, $endDate]);
        } else {
            $query->whereDate('tanggal_pesanan', '>=', $startDate);
        }
        
        return $query->with(['buku', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calculate total savings from discounts
     */
    public function getTotalSavingsAttribute()
    {
        return $this->diskon;
    }

    /**
     * Get order age in days
     */
    public function getOrderAgeAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Check if order needs attention (stuck in one status too long)
     */
    public function needsAttention()
    {
        $daysSinceUpdate = $this->updated_at->diffInDays(now());
        
        return match($this->status) {
            'menunggu_pembayaran' => $daysSinceUpdate > 1,
            'menunggu_verifikasi' => $daysSinceUpdate > 2,
            'terverifikasi', 'diproses' => $daysSinceUpdate > 3,
            'dikirim' => $daysSinceUpdate > 7,
            default => false
        };
    }

    /**
     * Get estimated delivery date for physical books
     */
    public function getEstimatedDeliveryAttribute()
    {
        if (!$this->isPhysicalBook()) {
            return null;
        }

        if (!in_array($this->status, ['terverifikasi', 'diproses', 'dikirim'])) {
            return null;
        }

        // Estimate 3-7 days for processing + shipping
        $processingDays = match($this->status) {
            'terverifikasi' => 7, // 3 days processing + 4 days shipping
            'diproses' => 4, // 4 days shipping
            'dikirim' => 2, // 2 days remaining
            default => 7
        };

        return now()->addDays($processingDays);
    }

    /**
     * Get order actions available to user
     */
    public function getAvailableActionsAttribute()
    {
        $actions = [];

        // Always available
        $actions[] = [
            'type' => 'view',
            'label' => 'Lihat Detail',
            'url' => $this->getDetailUrl(),
            'class' => 'btn-outline-primary'
        ];

        // Payment action
        if ($this->canUploadPayment()) {
            $actions[] = [
                'type' => 'payment',
                'label' => 'Bayar Sekarang',
                'url' => $this->getPaymentUrl(),
                'class' => 'btn-warning'
            ];
        }

        // Download action
        if ($this->canDownloadEbook()) {
            $actions[] = [
                'type' => 'download',
                'label' => 'Download E-book',
                'url' => $this->getEbookDownloadUrl(),
                'class' => 'btn-success'
            ];
        }

        // Cancel action
        if ($this->canBeCancelled()) {
            $actions[] = [
                'type' => 'cancel',
                'label' => 'Batalkan',
                'url' => '#',
                'class' => 'btn-outline-danger',
                'onclick' => "cancelOrder({$this->id})"
            ];
        }

        return collect($actions);
    }
}
    