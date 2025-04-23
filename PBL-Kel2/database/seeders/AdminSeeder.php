<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin; // Gunakan model Admin, bukan User

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambah data admin
        Admin::create([
            'name' => 'Admin Fanya',
            'email' => 'admin@fanya.com',
            'password' => Hash::make('admin123'), // Ganti password sesuai kebutuhan
        ]);
    }
}
