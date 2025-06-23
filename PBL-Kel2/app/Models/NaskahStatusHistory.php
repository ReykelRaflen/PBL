<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NaskahStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'naskah_id',
        'status_dari',
        'status_ke',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Naskah
     */
    public function naskah(): BelongsTo
    {
        return $this->belongsTo(Naskah::class);
    }

    /**
     * Relasi ke User (yang mengubah status)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status dari dalam bahasa Indonesia
     */
    public function getStatusDariLabelAttribute(): ?string
    {
        if (!$this->status_dari) {
            return null;
        }

        $labels = [
            'pending' => 'Menunggu',
            'sedang_direview' => 'Sedang Direview',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return $labels[$this->status_dari] ?? $this->status_dari;
    }

    /**
     * Get status ke dalam bahasa Indonesia
     */
    public function getStatusKeLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu',
            'sedang_direview' => 'Sedang Direview',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return $labels[$this->status_ke] ?? $this->status_ke;
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'sedang_direview' => 'info',
            'disetujui' => 'success',
            'ditolak' => 'danger',
        ];

        return $colors[$this->status_ke] ?? 'secondary';
    }

    /**
     * Get change description
     */
    public function getChangeDescriptionAttribute(): string
    {
        if (!$this->status_dari) {
            return "Status diset ke {$this->status_ke_label}";
        }

        return "Status diubah dari {$this->status_dari_label} ke {$this->status_ke_label}";
    }
}
