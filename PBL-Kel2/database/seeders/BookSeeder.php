<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            [
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'description' => 'Panduan lengkap untuk membangun aplikasi web modern dengan Laravel framework. Buku ini mencakup semua aspek pengembangan dari dasar hingga tingkat lanjut.',
                'original_price' => 500000,
                'discount_price' => 350000,
                'ebook_price' => 250000,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'description' => 'Belajar menulis kode yang bersih, mudah dibaca, dan mudah dipelihara. Buku wajib untuk setiap programmer yang ingin meningkatkan kualitas kodenya.',
                'original_price' => 450000,
                'discount_price' => 320000,
                'ebook_price' => 220000,
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'description' => 'Pola desain yang dapat digunakan kembali dalam pengembangan perangkat lunak berorientasi objek. Referensi klasik untuk arsitektur software.',
                'original_price' => 600000,
                'discount_price' => 420000,
                'ebook_price' => 300000,
            ],
            [
                'title' => 'JavaScript: The Good Parts',
                'author' => 'Douglas Crockford',
                'description' => 'Mengungkap bagian terbaik dari JavaScript dan cara menggunakannya secara efektif. Panduan praktis untuk pengembangan web modern.',
                'original_price' => 400000,
                'discount_price' => 280000,
                'ebook_price' => 200000,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
