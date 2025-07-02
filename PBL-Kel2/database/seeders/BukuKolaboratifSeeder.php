<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BukuKolaboratif;
use App\Models\BabBuku;

class BukuKolaboratifSeeder extends Seeder
{
    public function run()
    {
        // Buku 1
        $buku1 = BukuKolaboratif::create([
            'judul' => 'Teknologi Masa Depan Indonesia',
            'deskripsi' => 'Sebuah buku kolaboratif yang membahas perkembangan teknologi di Indonesia dan visi masa depannya. Ditulis bersama oleh para ahli teknologi dan praktisi industri.',
            'harga_per_bab' => 150000,
            'total_bab' => 10,
            'status' => 'aktif'
        ]);

        $babBuku1 = [
            ['nomor_bab' => 1, 'judul_bab' => 'Sejarah Teknologi Indonesia', 'deskripsi' => 'Perjalanan perkembangan teknologi di Indonesia dari masa ke masa'],
            ['nomor_bab' => 2, 'judul_bab' => 'Startup dan Inovasi Digital', 'deskripsi' => 'Ekosistem startup Indonesia dan inovasi digital yang berkembang'],
            ['nomor_bab' => 3, 'judul_bab' => 'Artificial Intelligence di Indonesia', 'deskripsi' => 'Implementasi dan pengembangan AI di berbagai sektor'],
            ['nomor_bab' => 4, 'judul_bab' => 'Blockchain dan Cryptocurrency', 'deskripsi' => 'Adopsi teknologi blockchain dan mata uang digital'],
            ['nomor_bab' => 5, 'judul_bab' => 'Internet of Things (IoT)', 'deskripsi' => 'Penerapan IoT dalam kehidupan sehari-hari dan industri'],
            ['nomor_bab' => 6, 'judul_bab' => 'Cybersecurity Indonesia', 'deskripsi' => 'Tantangan dan solusi keamanan siber di Indonesia'],
                       ['nomor_bab' => 7, 'judul_bab' => 'E-Commerce dan Digital Payment', 'deskripsi' => 'Perkembangan perdagangan elektronik dan sistem pembayaran digital'],
            ['nomor_bab' => 8, 'judul_bab' => 'Smart City Indonesia', 'deskripsi' => 'Konsep dan implementasi kota pintar di berbagai daerah'],
            ['nomor_bab' => 9, 'judul_bab' => 'Pendidikan Digital', 'deskripsi' => 'Transformasi pendidikan melalui teknologi digital'],
            ['nomor_bab' => 10, 'judul_bab' => 'Visi Teknologi 2030', 'deskripsi' => 'Proyeksi dan harapan teknologi Indonesia di masa depan']
        ];

        foreach ($babBuku1 as $bab) {
            BabBuku::create([
                'buku_kolaboratif_id' => $buku1->id,
                'nomor_bab' => $bab['nomor_bab'],
                'judul_bab' => $bab['judul_bab'],
                'deskripsi' => $bab['deskripsi'],
                'status' => 'tersedia'
            ]);
        }

        // Buku 2
        $buku2 = BukuKolaboratif::create([
            'judul' => 'Kearifan Lokal Nusantara',
            'deskripsi' => 'Dokumentasi kearifan lokal dari berbagai daerah di Indonesia. Setiap bab ditulis oleh penulis yang berasal dari daerah tersebut.',
            'harga_per_bab' => 100000,
            'total_bab' => 12,
            'status' => 'aktif'
        ]);

        $babBuku2 = [
            ['nomor_bab' => 1, 'judul_bab' => 'Kearifan Lokal Jawa', 'deskripsi' => 'Filosofi dan tradisi masyarakat Jawa'],
            ['nomor_bab' => 2, 'judul_bab' => 'Budaya Sunda', 'deskripsi' => 'Nilai-nilai luhur dalam budaya Sunda'],
            ['nomor_bab' => 3, 'judul_bab' => 'Tradisi Batak', 'deskripsi' => 'Adat istiadat dan kearifan masyarakat Batak'],
            ['nomor_bab' => 4, 'judul_bab' => 'Filosofi Minangkabau', 'deskripsi' => 'Sistem matrilineal dan nilai-nilai Minang'],
            ['nomor_bab' => 5, 'judul_bab' => 'Kearifan Bali', 'deskripsi' => 'Tri Hita Karana dan harmoni kehidupan Bali'],
            ['nomor_bab' => 6, 'judul_bab' => 'Budaya Dayak', 'deskripsi' => 'Tradisi dan kepercayaan masyarakat Dayak'],
            ['nomor_bab' => 7, 'judul_bab' => 'Kearifan Bugis-Makassar', 'deskripsi' => 'Siri na pacce dan budaya maritim Sulawesi'],
            ['nomor_bab' => 8, 'judul_bab' => 'Tradisi Papua', 'deskripsi' => 'Kearifan lokal masyarakat Papua'],
            ['nomor_bab' => 9, 'judul_bab' => 'Budaya Lombok', 'deskripsi' => 'Tradisi Sasak dan nilai-nilai lokal Lombok'],
            ['nomor_bab' => 10, 'judul_bab' => 'Kearifan Aceh', 'deskripsi' => 'Tradisi dan adat istiadat masyarakat Aceh'],
            ['nomor_bab' => 11, 'judul_bab' => 'Filosofi Toraja', 'deskripsi' => 'Kepercayaan dan ritual masyarakat Toraja'],
            ['nomor_bab' => 12, 'judul_bab' => 'Sintesis Kearifan Nusantara', 'deskripsi' => 'Benang merah kearifan lokal Indonesia']
        ];

        foreach ($babBuku2 as $bab) {
            BabBuku::create([
                'buku_kolaboratif_id' => $buku2->id,
                'nomor_bab' => $bab['nomor_bab'],
                'judul_bab' => $bab['judul_bab'],
                'deskripsi' => $bab['deskripsi'],
                'status' => 'tersedia'
            ]);
        }

        // Buku 3
        $buku3 = BukuKolaboratif::create([
            'judul' => 'Panduan Bisnis Digital Indonesia',
            'deskripsi' => 'Buku panduan lengkap untuk memulai dan mengembangkan bisnis digital di Indonesia. Ditulis oleh para entrepreneur sukses.',
            'harga_per_bab' => 200000,
            'total_bab' => 8,
            'status' => 'aktif'
        ]);

        $babBuku3 = [
            ['nomor_bab' => 1, 'judul_bab' => 'Mindset Entrepreneur Digital', 'deskripsi' => 'Pola pikir yang diperlukan untuk sukses di era digital'],
            ['nomor_bab' => 2, 'judul_bab' => 'Riset Pasar Digital', 'deskripsi' => 'Cara melakukan riset pasar untuk bisnis digital'],
            ['nomor_bab' => 3, 'judul_bab' => 'Membangun Brand Online', 'deskripsi' => 'Strategi branding untuk bisnis digital'],
            ['nomor_bab' => 4, 'judul_bab' => 'Digital Marketing Strategy', 'deskripsi' => 'Strategi pemasaran digital yang efektif'],
            ['nomor_bab' => 5, 'judul_bab' => 'Manajemen Keuangan Digital', 'deskripsi' => 'Pengelolaan keuangan untuk bisnis digital'],
            ['nomor_bab' => 6, 'judul_bab' => 'Scaling Up Business', 'deskripsi' => 'Cara mengembangkan bisnis digital ke level selanjutnya'],
            ['nomor_bab' => 7, 'judul_bab' => 'Legal dan Compliance', 'deskripsi' => 'Aspek hukum dalam bisnis digital Indonesia'],
            ['nomor_bab' => 8, 'judul_bab' => 'Studi Kasus Sukses', 'deskripsi' => 'Analisis bisnis digital sukses di Indonesia']
        ];

        foreach ($babBuku3 as $bab) {
            BabBuku::create([
                'buku_kolaboratif_id' => $buku3->id,
                'nomor_bab' => $bab['nomor_bab'],
                'judul_bab' => $bab['judul_bab'],
                'deskripsi' => $bab['deskripsi'],
                'status' => 'tersedia'
            ]);
        }
    }
}
