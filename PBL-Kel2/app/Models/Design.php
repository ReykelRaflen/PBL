<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'cover',
        'pembuat_id',
        'reviewer_id',
        'status',
        'due_date',
        'direview_pada',
        'catatan'
    ];

    protected $casts = [
        'due_date' => 'date',
        'direview_pada' => 'datetime'
    ];

    // Relationships
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(DesignStatusHistory::class);
    }

    // Accessors
    public function getCoverUrlAttribute()
    {
        if ($this->cover) {
            return Storage::url($this->cover);
        }
        return null;
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Static methods
    public static function getStatusOptions()
    {
        return [
            'draft' => 'Draft',
            'review' => 'Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai'
        ];
    }

    public static function getStatistik()
    {
        return [
            'total' => self::count(),
            'draft' => self::where('status', 'draft')->count(),
            'review' => self::where('status', 'review')->count(),
            'approved' => self::where('status', 'approved')->count(),
            'rejected' => self::where('status', 'rejected')->count(),
            'completed' => self::where('status', 'completed')->count(),
            'urgent' => self::whereDate('due_date', '<=', now()->addDays(3))
                ->whereIn('status', ['draft', 'review'])
                ->count(),
            'overdue' => self::where('due_date', '<', now())
                ->whereIn('status', ['draft', 'review'])
                ->count(),
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
                        'approved' => self::whereDate('direview_pada', $date)
                            ->where('status', 'approved')->count(),
                        'completed' => self::whereDate('updated_at', $date)
                            ->where('status', 'completed')->count(),
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
                        'approved' => self::whereDate('direview_pada', $date)
                            ->where('status', 'approved')->count(),
                        'completed' => self::whereDate('updated_at', $date)
                            ->where('status', 'completed')->count(),
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
                        'approved' => self::whereMonth('direview_pada', $date->month)
                            ->whereYear('direview_pada', $date->year)
                            ->where('status', 'approved')->count(),
                        'completed' => self::whereMonth('updated_at', $date->month)
                            ->whereYear('updated_at', $date->year)
                            ->where('status', 'completed')->count(),
                    ];
                }
                break;
        }
        
        return $data;
    }

       // Scopes
    public function scopeUrgent($query)
    {
        return $query->whereDate('due_date', '<=', now()->addDays(3))
            ->whereIn('status', ['draft', 'review']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereIn('status', ['draft', 'review']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPembuat($query, $pembuatId)
    {
        return $query->where('pembuat_id', $pembuatId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('judul', 'like', '%' . $search . '%')
              ->orWhere('deskripsi', 'like', '%' . $search . '%')
              ->orWhereHas('pembuat', function($subQ) use ($search) {
                  $subQ->where('name', 'like', '%' . $search . '%');
              });
        });
    }

    // Helper methods
    public function isUrgent()
    {
        return $this->due_date && $this->due_date->lte(now()->addDays(3)) 
            && in_array($this->status, ['draft', 'review']);
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->lt(now()) 
            && in_array($this->status, ['draft', 'review']);
    }

    public function canBeReviewed()
    {
        return in_array($this->status, ['draft', 'review']);
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canBeDeleted()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    // Boot method untuk event handling
    protected static function boot()
    {
        parent::boot();

        // Log status changes
        static::updating(function ($design) {
            if ($design->isDirty('status')) {
                $original = $design->getOriginal('status');
                $new = $design->status;
                
                // Create status history record if model exists
                if (class_exists('\App\Models\DesignStatusHistory')) {
                    \App\Models\DesignStatusHistory::create([
                        'design_id' => $design->id,
                        'status_from' => $original,
                        'status_to' => $new,
                        'user_id' => auth()->id(),
                        'catatan' => $design->catatan
                    ]);
                }
            }
        });

        // Clean up files when deleting
        static::deleting(function ($design) {
            if ($design->cover && \Storage::disk('public')->exists($design->cover)) {
                \Storage::disk('public')->delete($design->cover);
            }
        });
    }
}
