<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\Promo;

class BookSeeder extends Seeder
{
    public function run()
    {
        // Pastikan kategori dan promo sudah ada
        $kategoriProgramming = KategoriBuku::firstOrCreate(['nama' => 'Pemrograman'], ['status' => true]);
        $kategoriDesign = KategoriBuku::firstOrCreate(['nama' => 'Desain'], ['status' => true]);
        $kategoriBusiness = KategoriBuku::firstOrCreate(['nama' => 'Bisnis'], ['status' => true]);
        $kategoriDatabase = KategoriBuku::firstOrCreate(['nama' => 'Database'], ['status' => true]);
        $kategoriWebDev = KategoriBuku::firstOrCreate(['nama' => 'Web Development'], ['status' => true]);

        // Buat promo sample jika belum ada
        $promoDiskon = Promo::firstOrCreate(
            ['kode_promo' => 'DISKON20'],
            [
                'keterangan' => 'Diskon 20% untuk semua buku',
                'tipe' => 'Persentase',
                'besaran' => 20,
                'kuota' => 100,
                'kuota_terpakai' => 0,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(3),
                'status' => 'Aktif'
            ]
        );

        $books = [
            [
                'judul_buku' => 'Laravel: Up & Running',
                'penulis' => 'Matt Stauffer',
                'penerbit' => 'O\'Reilly Media',
                'tahun_terbit' => 2019,
                'isbn' => '978-1491936672',
                'kategori_id' => $kategoriWebDev->id,
                'harga' => 500000,
                'stok' => 100,
                'deskripsi' => 'Panduan lengkap untuk membangun aplikasi web modern dengan Laravel framework. Buku ini mencakup semua aspek pengembangan dari dasar hingga tingkat lanjut, termasuk routing, middleware, database, authentication, dan deployment.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2008,
                'isbn' => '978-0132350884',
                'kategori_id' => $kategoriProgramming->id,
                'harga' => 450000,
                'stok' => 150,
                'deskripsi' => 'Belajar menulis kode yang bersih, mudah dibaca, dan mudah dipelihara. Buku wajib untuk setiap programmer yang ingin meningkatkan kualitas kodenya. Membahas prinsip-prinsip clean code, naming conventions, functions, comments, dan error handling.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 360000, // 20% discount
            ],
            [
                'judul_buku' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'penulis' => 'Gang of Four',
                'penerbit' => 'Addison-Wesley Professional',
                'tahun_terbit' => 1994,
                'isbn' => '978-0201633610',
                'kategori_id' => $kategoriProgramming->id,
                'harga' => 600000,
                'stok' => 80,
                'deskripsi' => 'Pola desain yang dapat digunakan kembali dalam pengembangan perangkat lunak berorientasi objek. Referensi klasik untuk arsitektur software yang membahas 23 design patterns fundamental.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'JavaScript: The Good Parts',
                'penulis' => 'Douglas Crockford',
                'penerbit' => 'O\'Reilly Media',
                'tahun_terbit' => 2008,
                'isbn' => '978-0596517748',
                'kategori_id' => $kategoriWebDev->id,
                'harga' => 400000,
                'stok' => 120,
                'deskripsi' => 'Mengungkap bagian terbaik dari JavaScript dan cara menggunakannya secara efektif. Panduan praktis untuk pengembangan web modern yang fokus pada fitur-fitur JavaScript yang paling berguna dan powerful.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 320000, // 20% discount
            ],
            [
                'judul_buku' => 'Pemrograman Web dengan PHP & MySQL',
                'penulis' => 'Abdul Kadir',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2020,
                'isbn' => '978-6023750234',
                'kategori_id' => $kategoriWebDev->id,
                'harga' => 350000,
                'stok' => 200,
                'deskripsi' => 'Buku panduan lengkap untuk mempelajari pemrograman web menggunakan PHP dan MySQL. Cocok untuk pemula hingga menengah. Membahas dasar-dasar PHP, MySQL, session, cookies, dan pembuatan aplikasi web dinamis.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'Introduction to Algorithms',
                'penulis' => 'Thomas H. Cormen',
                'penerbit' => 'MIT Press',
                'tahun_terbit' => 2009,
                'isbn' => '978-0262033848',
                'kategori_id' => $kategoriProgramming->id,
                'harga' => 750000,
                'stok' => 60,
                'deskripsi' => 'Referensi utama untuk mempelajari algoritma dan struktur data. Buku wajib untuk mahasiswa ilmu komputer dan programmer. Mencakup sorting, searching, graph algorithms, dynamic programming, dan analisis kompleksitas.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 600000, // 20% discount
            ],
            [
                'judul_buku' => 'Database System Concepts',
                'penulis' => 'Abraham Silberschatz',
                'penerbit' => 'McGraw-Hill Education',
                'tahun_terbit' => 2019,
                'isbn' => '978-0078022159',
                'kategori_id' => $kategoriDatabase->id,
                'harga' => 650000,
                'stok' => 90,
                'deskripsi' => 'Konsep dasar sistem basis data yang komprehensif. Mencakup desain, implementasi, dan manajemen database. Membahas relational model, SQL, normalization, transaction processing, dan database security.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'React: The Complete Guide',
                'penulis' => 'Maximilian Schwarzmüller',
                'penerbit' => 'Packt Publishing',
                'tahun_terbit' => 2021,
                'isbn' => '978-1801812603',
                'kategori_id' => $kategoriWebDev->id,
                'harga' => 480000,
                'stok' => 110,
                'deskripsi' => 'Panduan lengkap untuk menguasai React.js dari dasar hingga tingkat lanjut. Termasuk hooks, context, Redux, testing, dan best practices. Dilengkapi dengan project-project praktis untuk memperdalam pemahaman.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 384000, // 20% discount
            ],
            [
                'judul_buku' => 'Python Crash Course',
                'penulis' => 'Eric Matthes',
                'penerbit' => 'No Starch Press',
                'tahun_terbit' => 2019,
                'isbn' => '978-1593279288',
                'kategori_id' => $kategoriProgramming->id,
                'harga' => 420000,
                'stok' => 140,
                'deskripsi' => 'Pengenalan Python yang cepat dan praktis untuk pemula. Buku ini mengajarkan dasar-dasar Python melalui project-project menarik seperti game, visualisasi data, dan web application.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'The Lean Startup',
                'penulis' => 'Eric Ries',
                'penerbit' => 'Crown Business',
                'tahun_terbit' => 2011,
                'isbn' => '978-0307887894',
                'kategori_id' => $kategoriBusiness->id,
                'harga' => 380000,
                'stok' => 85,
                'deskripsi' => 'Metodologi untuk membangun startup yang sukses dengan pendekatan lean. Membahas konsep MVP (Minimum Viable Product), validated learning, dan pivot strategy untuk entrepreneur modern.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 304000, // 20% discount
            ],
            [
                'judul_buku' => 'Node.js Design Patterns',
                'penulis' => 'Mario Casciaro',
                'penerbit' => 'Packt Publishing',
                'tahun_terbit' => 2020,
                'isbn' => '978-1839214110',
                'kategori_id' => $kategoriWebDev->id,
                'harga' => 520000,
                'stok' => 75,
                'deskripsi' => 'Pola desain dan best practices untuk pengembangan aplikasi Node.js yang scalable dan maintainable. Membahas asynchronous programming, streams, design patterns, dan microservices architecture.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => null,
                'harga_promo' => null,
            ],
            [
                'judul_buku' => 'UI/UX Design Fundamentals',
                'penulis' => 'Sarah Johnson',
                'penerbit' => 'Design Press',
                'tahun_terbit' => 2022,
                'isbn' => '978-1234567890',
                'kategori_id' => $kategoriDesign->id,
                'harga' => 450000,
                'stok' => 95,
                'deskripsi' => 'Panduan fundamental untuk UI/UX design yang mencakup user research, wireframing, prototyping, dan usability testing. Dilengkapi dengan case studies dan tools modern untuk designer.',
                'cover' => null,
                'file_buku' => null,
                'promo_id' => $promoDiskon->id,
                'harga_promo' => 360000, // 20% discount
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }

        $this->command->info('Book seeder completed successfully!');
        $this->command->info('Created ' . count($books) . ' books with categories and promos.');
    }
}
