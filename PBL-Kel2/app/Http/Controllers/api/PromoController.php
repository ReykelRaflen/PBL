<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromoController extends Controller
{
    public function checkPromo(Request $request)
    {
        try {
            $request->validate([
                'kode_promo' => 'required|string',
                'buku_id' => 'required|exists:buku,id',
                'tipe_buku' => 'required|in:fisik,ebook',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $kodePromo = strtoupper(trim($request->kode_promo));
            
            // Cari promo berdasarkan kode
            $promo = Promo::where('kode_promo', $kodePromo)
                          ->where('status', 'Aktif')
                          ->first();

            if (!$promo) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode promo tidak ditemukan atau tidak aktif'
                ]);
            }

            // Validasi tanggal promo
            $today = Carbon::now()->toDateString();
            if ($promo->tanggal_mulai > $today) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo belum dimulai. Berlaku mulai ' . Carbon::parse($promo->tanggal_mulai)->format('d F Y')
                ]);
            }

            if ($promo->tanggal_selesai < $today) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo sudah berakhir pada ' . Carbon::parse($promo->tanggal_selesai)->format('d F Y')
                ]);
            }

            // Validasi kuota
            if ($promo->kuota !== null && $promo->kuota_terpakai >= $promo->kuota) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kuota promo sudah habis'
                ]);
            }

            // Hitung diskon
            $diskon = $this->calculateDiscount($promo, $request->subtotal);

            // Validasi minimum pembelian (jika ada)
            $minPurchase = $this->getMinimumPurchase($promo);
            if ($minPurchase > 0 && $request->subtotal < $minPurchase) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Minimum pembelian Rp ' . number_format($minPurchase, 0, ',', '.') . ' untuk menggunakan promo ini'
                ]);
            }

            return response()->json([
                'valid' => true,
                'message' => 'Promo berhasil diterapkan',
                'diskon' => $diskon,
                'promo' => [
                    'kode' => $promo->kode_promo,
                    'keterangan' => $promo->keterangan,
                    'tipe' => $promo->tipe,
                    'besaran' => $promo->besaran,
                    'kuota_tersisa' => $promo->kuota ? ($promo->kuota - $promo->kuota_terpakai) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi promo'
            ], 500);
        }
    }

    private function calculateDiscount($promo, $subtotal)
    {
        $diskon = 0;

        if ($promo->tipe === 'Persentase') {
            $diskon = ($subtotal * $promo->besaran) / 100;
            
            // Batasi maksimal diskon jika perlu (opsional)
            $maxDiscount = $subtotal * 0.5; // Maksimal 50% dari subtotal
            $diskon = min($diskon, $maxDiscount);
        } else { // Nominal
            $diskon = $promo->besaran;
        }

        // Pastikan diskon tidak melebihi subtotal
        return min($diskon, $subtotal);
    }

    private function getMinimumPurchase($promo)
    {
        // Jika ada field minimum_purchase di tabel promo
        if (isset($promo->minimum_purchase)) {
            return $promo->minimum_purchase;
        }

        // Atau bisa berdasarkan besaran promo
        if ($promo->tipe === 'Nominal' && $promo->besaran >= 50000) {
            return 200000; // Min 200k untuk diskon nominal >= 50k
        }

        if ($promo->tipe === 'Persentase' && $promo->besaran >= 20) {
            return 100000; // Min 100k untuk diskon >= 20%
        }

        return 0; // Tidak ada minimum
    }

    public function getActivePromos(Request $request)
    {
        try {
            $today = Carbon::now()->toDateString();
            
            $promos = Promo::where('status', 'Aktif')
                          ->where('tanggal_mulai', '<=', $today)
                          ->where('tanggal_selesai', '>=', $today)
                          ->whereRaw('(kuota IS NULL OR kuota_terpakai < kuota)')
                          ->select('kode_promo', 'keterangan', 'tipe', 'besaran', 'kuota', 'kuota_terpakai')
                          ->get();

            return response()->json([
                'success' => true,
                'promos' => $promos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data promo'
            ], 500);
        }
    }
}
