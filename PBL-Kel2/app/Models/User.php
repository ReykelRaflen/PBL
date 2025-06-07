<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'birthdate',
        'gender',
        'agama',
        'foto',
        'role',
        'otp',
        'otp_created_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'otp_created_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get user's profile photo URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path('uploads/foto_profil/' . $this->foto))) {
            return asset('uploads/foto_profil/' . $this->foto);
        }
        
        // Default avatar dengan initial nama
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://via.placeholder.com/150x150/a5b4fc/ffffff?text={$initial}";
    }

    /**
     * Check if user has profile photo
     */
    public function hasFoto()
    {
        return !empty($this->foto) && file_exists(public_path('uploads/foto_profil/' . $this->foto));
    }
}
