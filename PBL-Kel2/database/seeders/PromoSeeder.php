<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    public function run()
    {
        $promos = [
            [
                'kode_promo' => 'DISKON20',
                'keterangan' => 'Diskon 20% untuk semua buku programming',
                'tipe' => 'Persentase',
                'besaran' => 20,
                'kuota' => 100,
                'kuota_terpakai' => 15,
                'tanggal_mulai' => Carbon::now(),
                'tanggal_selesai' => Carbon::now()->addMonths(3),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'HEMAT50K',
                'keterangan' => 'Potongan Rp 50.000 untuk pembelian minimal Rp 300.000',
                'tipe' => 'Nominal',
                'besaran' => 50000,
                'kuota' => 50,
                'kuota_terpakai' => 8,
                'tanggal_mulai' => Carbon::now()->subDays(10),
                'tanggal_selesai' => Carbon::now()->addMonths(2),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'NEWUSER15',
                'keterangan' => 'Diskon 15% khusus untuk pengguna baru',
                'tipe' => 'Persentase',
                'besaran' => 15,
                'kuota' => 200,
                'kuota_terpakai' => 45,
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->addMonths(6),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'FLASHSALE30',
                'keterangan' => 'Flash Sale! Diskon 30% untuk 24 jam',
                'tipe' => 'Persentase',
                'besaran' => 30,
                'kuota' => 25,
                'kuota_terpakai' => 20,
                'tanggal_mulai' => Carbon::now()->subHours(12),
                'tanggal_selesai' => Carbon::now()->addHours(12),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'WEEKEND25',
                'keterangan' => 'Promo weekend - Diskon 25% untuk buku desain',
                'tipe' => 'Persentase',
                'besaran' => 25,
                'kuota' => 75,
                'kuota_terpakai' => 12,
                'tanggal_mulai' => Carbon::now()->startOfWeek()->addDays(5), // Sabtu
                'tanggal_selesai' => Carbon::now()->startOfWeek()->addDays(6)->endOfDay(), // Minggu
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'STUDENT10',
                'keterangan' => 'Diskon khusus mahasiswa 10%',
                'tipe' => 'Persentase',
                'besaran' => 10,
                'kuota' => 500,
                'kuota_terpakai' => 89,
                'tanggal_mulai' => Carbon::now()->startOfYear(),
                'tanggal_selesai' => Carbon::now()->endOfYear(),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'CASHBACK100K',
                'keterangan' => 'Cashback Rp 100.000 untuk pembelian di atas Rp 1.000.000',
                'tipe' => 'Nominal',
                'besaran' => 100000,
                'kuota' => 10,
                'kuota_terpakai' => 3,
                'tanggal_mulai' => Carbon::now()->subDays(7),
                'tanggal_selesai' => Carbon::now()->addDays(23),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'EXPIRED50',
                'keterangan' => 'Promo yang sudah expired - Diskon 50%',
                'tipe' => 'Persentase',
                'besaran' => 50,
                'kuota' => 30,
                'kuota_terpakai' => 30,
                'tanggal_mulai' => Carbon::now()->subMonths(2),
                'tanggal_selesai' => Carbon::now()->subDays(10),
                'status' => 'Tidak Aktif',
            ],
            [
                'kode_promo' => 'FUTURE40',
                'keterangan' => 'Promo masa depan - Diskon 40%',
                'tipe' => 'Persentase',
                'besaran' => 40,
                'kuota' => 100,
                'kuota_terpakai' => 0,
                'tanggal_mulai' => Carbon::now()->addDays(30),
                'tanggal_selesai' => Carbon::now()->addDays(60),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'LOYALTY35',
                'keterangan' => 'Program loyalitas - Diskon 35% untuk member premium',
                'tipe' => 'Persentase',
                'besaran' => 35,
                'kuota' => null, // Unlimited
                'kuota_terpakai' => 156,
                'tanggal_mulai' => Carbon::now()->subMonths(1),
                'tanggal_selesai' => Carbon::now()->addMonths(11),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'BUNDLE75K',
                'keterangan' => 'Hemat Rp 75.000 untuk pembelian bundle 3 buku',
                'tipe' => 'Nominal',
                'besaran' => 75000,
                'kuota' => 40,
                'kuota_terpakai' => 18,
                'tanggal_mulai' => Carbon::now()->subDays(3),
                'tanggal_selesai' => Carbon::now()->addDays(27),
                'status' => 'Aktif',
            ],
            [
                'kode_promo' => 'RAMADAN22',
                'keterangan' => 'Promo Ramadan - Diskon 22% untuk semua kategori',
                'tipe' => 'Persentase',
                'besaran' => 22,
                'kuota' => 150,
                'kuota_terpakai' => 67,
                'tanggal_mulai' => Carbon::now()->subDays(15),
                'tanggal_selesai' => Carbon::now()->addDays(15),
                'status' => 'Aktif',
            ],
        ];

        foreach ($promos as $promo) {
            Promo::firstOrCreate(
                ['kode_promo' => $promo['kode_promo']], 
                $promo
            );
        }

        $this->command->info('Promo seeder completed successfully!');
        $this->command->info('Created ' . count($promos) . ' promo codes.');
    }
}
