<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'birthdate',
        'gender',
        'agama',
        'foto',
        'role',
        'password',
        'otp',
        'otp_created_at',
        'is_verified',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
        'otp_created_at' => 'datetime',
        'is_verified' => 'boolean',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isEditor()
    {
        return $this->role === 'editor';
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    // Tambahkan relasi-relasi ini

    /**
     * Naskah yang diupload oleh user ini (sebagai pengirim)
     */
    public function naskah()
    {
        return $this->hasMany(Naskah::class, 'user_id');
    }

    /**
     * Naskah yang direview oleh user ini (sebagai reviewer)
     */
    public function naskahDireview()
    {
        return $this->hasMany(Naskah::class, 'reviewer_id');
    }

    /**
     * Status histories yang dibuat oleh user ini
     */
    public function statusHistories()
    {
        return $this->hasMany(NaskahStatusHistory::class);
    }

    // Relasi yang sudah ada sebelumnya
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function pembayarans()
    {
        return $this->hasManyThrough(Pembayaran::class, Pesanan::class);
    }

    // Method tambahan untuk statistik naskah
    public function getTotalNaskahAttribute()
    {
        return $this->naskah()->count();
    }

    public function getNaskahPendingAttribute()
    {
        return $this->naskah()->where('status', 'pending')->count();
    }

    public function getNaskahDisetujuiAttribute()
    {
        return $this->naskah()->where('status', 'disetujui')->count();
    }

    public function getNaskahDitolakAttribute()
    {
        return $this->naskah()->where('status', 'ditolak')->count();
    }


    // Tambahkan method ini ke model User yang sudah ada

    public function designs()
    {
        return $this->hasMany(Design::class, 'pembuat_id');
    }

    public function reviewedDesigns()
    {
        return $this->hasMany(Design::class, 'reviewer_id');
    }

    public function designStatusHistories()
    {
        return $this->hasMany(DesignStatusHistory::class);
    }

}
