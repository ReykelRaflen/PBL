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

    public function isVerified()
    {
        return $this->is_verified;
    }
}
