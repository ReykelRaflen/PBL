<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Naskah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'naskah';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file_path',
        'nama_file_asli',
        'mime_type',
        'ukuran_file',
        'status',
        'batas_waktu',
        'catatan',
        'user_id',
        'reviewer_id',
        'direview_pada',
    ];

    protected $casts = [
        'batas_waktu' => 'datetime',
        'direview_pada' => 'datetime',
        'ukuran_file' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'batas_waktu',
        'direview_pada',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SEDANG_DIREVIEW = 'sedang_direview';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SEDANG_DIREVIEW => 'Sedang Direview',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
    ];

    // Relationships
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(NaskahStatusHistory::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    public function getStatusTextAttribute()
    {
        return $this->getStatusLabelAttribute();
    }

    public function getStatusText()
    {
        return $this->getStatusTextAttribute();
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_SEDANG_DIREVIEW => 'bg-info text-white',
            self::STATUS_DISETUJUI => 'bg-success text-white',
            self::STATUS_DITOLAK => 'bg-danger text-white',
        ];

        return $classes[$this->status] ?? 'bg-secondary text-white';
    }

    public function getStatusBadgeClass()
    {
        return $this->getStatusBadgeClassAttribute();
    }

    public function getStatusIcon()
    {
        $icons = [
            self::STATUS_PENDING => 'fas fa-clock',
            self::STATUS_SEDANG_DIREVIEW => 'fas fa-search',
            self::STATUS_DISETUJUI => 'fas fa-check-circle',
            self::STATUS_DITOLAK => 'fas fa-times-circle',
        ];

        return $icons[$this->status] ?? 'fas fa-question-circle';
    }

    public function getUkuranFileFormattedAttribute()
    {
        return $this->formatFileSize($this->ukuran_file);
    }

    public function getFileSize()
    {
        return $this->getUkuranFileFormattedAttribute();
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->nama_file_asli, PATHINFO_EXTENSION);
    }

    public function getFileExtension()
    {
        return $this->getFileExtensionAttribute();
    }

    public function getIsOverdueAttribute()
    {
        return $this->batas_waktu < now() && in_array($this->status, [self::STATUS_PENDING, self::STATUS_SEDANG_DIREVIEW]);
    }

    public function getIsUrgentAttribute()
    {
        return $this->batas_waktu <= now()->addDays(3) && in_array($this->status, [self::STATUS_PENDING, self::STATUS_SEDANG_DIREVIEW]);
    }

    public function isSegera()
    {
        return $this->getIsUrgentAttribute();
    }

    public function isTerlambat()
    {
        return $this->getIsOverdueAttribute();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->batas_waktu < now()) {
            return 0;
        }
        return now()->diffInDays($this->batas_waktu);
    }

    public function canPreview()
    {
        $extension = $this->getFileExtension();
        $previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'];
        return in_array(strtolower($extension), $previewableExtensions);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSedangDireview($query)
    {
        return $query->where('status', self::STATUS_SEDANG_DIREVIEW);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }

    public function scopeSegera($query)
    {
        return $query->whereDate('batas_waktu', '<=', now()->addDays(3))
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_SEDANG_DIREVIEW]);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('batas_waktu', '<', now())
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_SEDANG_DIREVIEW]);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%')
                ->orWhereHas('pengirim', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
        });
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && in_array($status, array_keys(self::STATUSES))) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByDateRange($query, $dateFrom = null, $dateTo = null)
    {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        return $query;
    }

    // Static methods
    public static function generateUniqueFilename($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $cleanFilename = preg_replace('/[^A-Za-z0-9\-_]/', '_', $filename);

        return $cleanFilename . '_' . time() . '_' . uniqid() . '.' . $extension;
    }

    public static function getStoragePath()
    {
        return 'naskah/' . date('Y/m');
    }

    public static function getStatistik()
    {
        return [
            'total' => self::count(),
            'pending' => self::where('status', self::STATUS_PENDING)->count(),
            'sedang_direview' => self::where('status', self::STATUS_SEDANG_DIREVIEW)->count(),
            'disetujui' => self::where('status', self::STATUS_DISETUJUI)->count(),
            'ditolak' => self::where('status', self::STATUS_DITOLAK)->count(),
            'segera_berakhir' => self::segera()->count(),
            'terlambat' => self::terlambat()->count(),
        ];
    }

    public static function getChartData($period = '7days')
    {
        $data = [];

        switch ($period) {
            case '7days':
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $data[] = [
                        'date' => $date->format('Y-m-d'),
                        'label' => $date->format('d M'),
                        'total' => self::whereDate('created_at', $date)->count(),
                        'disetujui' => self::whereDate('direview_pada', $date)
                            ->where('status', self::STATUS_DISETUJUI)->count(),
                        'ditolak' => self::whereDate('direview_pada', $date)
                            ->where('status', self::STATUS_DITOLAK)->count(),
                    ];
                }
                break;

            case '30days':
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $data[] = [
                        'date' => $date->format('Y-m-d'),
                        'label' => $date->format('d/m'),
                        'total' => self::whereDate('created_at', $date)->count(),
                        'disetujui' => self::whereDate('direview_pada', $date)
                            ->where('status', self::STATUS_DISETUJUI)->count(),
                        'ditolak' => self::whereDate('direview_pada', $date)
                            ->where('status', self::STATUS_DITOLAK)->count(),
                    ];
                }
                break;

            case '12months':
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $data[] = [
                        'date' => $date->format('Y-m'),
                        'label' => $date->format('M Y'),
                        'total' => self::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)->count(),
                        'disetujui' => self::whereMonth('direview_pada', $date->month)
                            ->whereYear('direview_pada', $date->year)
                            ->where('status', self::STATUS_DISETUJUI)->count(),
                        'ditolak' => self::whereMonth('direview_pada', $date->month)
                            ->whereYear('direview_pada', $date->year)
                            ->where('status', self::STATUS_DITOLAK)->count(),
                    ];
                }
                break;
        }

        return $data;
    }

    // Instance methods
    public function formatFileSize($bytes)
    {
        if ($bytes == 0)
            return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    public function canBeReviewed()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SEDANG_DIREVIEW]);
    }

    public function isReviewed()
    {
        return in_array($this->status, [self::STATUS_DISETUJUI, self::STATUS_DITOLAK]);
    }

    public function getFileUrl()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    public function fileExists()
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    // Methods for controller compatibility
    public function statusCheck()
    {
        return [
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'updated_at' => $this->updated_at->toISOString()
        ];
    }

    public function preview()
    {
        if (!$this->fileExists()) {
            return [
                'success' => false,
                'message' => 'File tidak ditemukan'
            ];
        }

        $extension = $this->getFileExtension();
        $url = $this->getFileUrl();

        if ($extension === 'pdf') {
            return [
                'success' => true,
                'type' => 'pdf',
                'url' => $url
            ];
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return [
                'success' => true,
                'type' => 'image',
                'url' => $url
            ];
        } else {
            return [
                'success' => true,
                'type' => 'unsupported',
                'url' => $url
            ];
        }
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        // Create status history when status changes
        static::updating(function ($naskah) {
            if ($naskah->isDirty('status')) {
                // Only create history if NaskahStatusHistory model exists
                if (class_exists('App\Models\NaskahStatusHistory')) {
                    $naskah->statusHistories()->create([
                        'status_lama' => $naskah->getOriginal('status'),
                        'status_baru' => $naskah->status,
                        'catatan' => $naskah->catatan,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        });

        // Delete file when naskah is deleted
        static::deleting(function ($naskah) {
            if ($naskah->fileExists()) {
                Storage::disk('public')->delete($naskah->file_path);
            }
        });
    }
}
