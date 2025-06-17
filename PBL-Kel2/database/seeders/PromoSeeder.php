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
                'kode_promo' => 'DISKON10',
                'keterangan' => 'Diskon 10% untuk semua buku',
                'tipe' => 'Persentase',
                'besaran' => 10.00,
                'kuota' => 100,
                'kuota_terpakai' => 0,
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addDays(30),
                'status' => 'Aktif'
            ],
            [
                'kode_promo' => 'HEMAT50K',
                'keterangan' => 'Potongan Rp 50.000 untuk pembelian minimal Rp 200.000',
                'tipe' => 'Nominal',
                'besaran' => 50000.00,
                'kuota' => 50,
                'kuota_terpakai' => 0,
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addDays(15),
                'status' => 'Aktif'
            ],
            [
                'kode_promo' => 'NEWUSER20',
                'keterangan' => 'Diskon 20% untuk pengguna baru',
                'tipe' => 'Persentase',
                'besaran' => 20.00,
                'kuota' => null, // Unlimited
                'kuota_terpakai' => 0,
                'tanggal_mulai' => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addDays(60),
                'status' => 'Aktif'
            ]
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
