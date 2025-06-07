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
                'judul_buku' => 'Laravel: Up & Running',
                'penulis' => 'Matt Stauffer',
                'deskripsi' => 'Panduan lengkap untuk membangun aplikasi web modern dengan Laravel framework. Buku ini mencakup semua aspek pengembangan dari dasar hingga tingkat lanjut.',
                'stok_fisik' => 100,
                'harga_asli' => 500000,
                'harga_diskon' => 350000,
                'harga_ebook' => 250000,
            ],
            [
                'judul_buku' => 'Clean Code',
                'penulis' => 'Robert C. Martin',
                'deskripsi' => 'Belajar menulis kode yang bersih, mudah dibaca, dan mudah dipelihara. Buku wajib untuk setiap programmer yang ingin meningkatkan kualitas kodenya.',
                'stok_fisik' => 100,
                'harga_asli' => 450000,
                'harga_diskon' => 320000,
                'harga_ebook' => 220000,
            ],
            [
                'judul_buku' => 'Design Patterns',
                'penulis' => 'Gang of Four',
                'deskripsi' => 'Pola desain yang dapat digunakan kembali dalam pengembangan perangkat lunak berorientasi objek. Referensi klasik untuk arsitektur software.',
                'stok_fisik' => 100,
                'harga_asli' => 600000,
                'harga_diskon' => 420000,
                'harga_ebook' => 300000,
            ],
            [
                'judul_buku' => 'JavaScript: The Good Parts',
                'penulis' => 'Douglas Crockford',
                'deskripsi' => 'Mengungkap bagian terbaik dari JavaScript dan cara menggunakannya secara efektif. Panduan praktis untuk pengembangan web modern.',
                'stok_fisik' => 100,
                'harga_asli' => 400000,
                'harga_diskon' => 280000,
                'harga_ebook' => 200000,
            ],
            [
                'judul_buku' => 'Pemrograman Web dengan PHP & MySQL',
                'penulis' => 'Abdul Kadir',
                'deskripsi' => 'Buku panduan lengkap untuk mempelajari pemrograman web menggunakan PHP dan MySQL. Cocok untuk pemula hingga menengah.',
                'stok_fisik' => 100,
                'harga_asli' => 350000,
                'harga_diskon' => 245000,
                'harga_ebook' => 175000,
            ],
            [
                'judul_buku' => 'Algoritma dan Struktur Data',
                'penulis' => 'Thomas H. Cormen',
                'deskripsi' => 'Referensi utama untuk mempelajari algoritma dan struktur data. Buku wajib untuk mahasiswa ilmu komputer dan programmer.',
                'stok_fisik' => 100,
                'harga_asli' => 750000,
                'harga_diskon' => 525000,
                'harga_ebook' => 375000,
            ],
            [
                'judul_buku' => 'Database System Concepts',
                'penulis' => 'Abraham Silberschatz',
                'deskripsi' => 'Konsep dasar sistem basis data yang komprehensif. Mencakup desain, implementasi, dan manajemen database.',
                'stok_fisik' => 100,
                'harga_asli' => 650000,
                'harga_diskon' => 455000,
                'harga_ebook' => 325000,
            ],
            [
                'judul_buku' => 'React: The Complete Guide',
                'penulis' => 'Maximilian Schwarzmüller',
                'deskripsi' => 'Panduan lengkap untuk menguasai React.js dari dasar hingga tingkat lanjut. Termasuk hooks, context, dan best practices.',
                'stok_fisik' => 100,
                'harga_asli' => 480000,
                'harga_diskon' => 336000,
                'harga_ebook' => 240000,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
