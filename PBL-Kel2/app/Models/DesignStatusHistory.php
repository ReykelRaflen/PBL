<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_id',
        'status_from',
        'status_to',
        'user_id',
        'catatan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStatusFromLabelAttribute()
    {
        return Design::getStatusOptions()[$this->status_from] ?? $this->status_from;
    }

    public function getStatusToLabelAttribute()
    {
        return Design::getStatusOptions()[$this->status_to] ?? $this->status_to;
    }
}
