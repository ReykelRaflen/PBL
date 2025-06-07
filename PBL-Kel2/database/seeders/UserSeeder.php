<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '628123456789',
            'address' => 'Jakarta, Indonesia',
            'birthdate' => '1990-01-01',
            'gender' => 'Laki-laki',
            'agama' => 'Islam',
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '628987654321',
            'role' => 'user',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}
