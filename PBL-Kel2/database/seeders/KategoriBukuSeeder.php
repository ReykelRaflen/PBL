<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBuku;

class KategoriBukuSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['nama' => 'Pemrograman', 'status' => true],
            ['nama' => 'Web Development', 'status' => true],
            ['nama' => 'Mobile Development', 'status' => true],
            ['nama' => 'Database', 'status' => true],
            ['nama' => 'Desain', 'status' => true],
            ['nama' => 'Bisnis', 'status' => true],
            ['nama' => 'Data Science', 'status' => true],
            ['nama' => 'Artificial Intelligence', 'status' => true],
            ['nama' => 'Cybersecurity', 'status' => true],
            ['nama' => 'DevOps', 'status' => true],
        ];

        foreach ($categories as $category) {
            KategoriBuku::firstOrCreate(
                ['nama' => $category['nama']], 
                $category
            );
        }

        $this->command->info('Kategori Buku seeder completed!');
    }
}
