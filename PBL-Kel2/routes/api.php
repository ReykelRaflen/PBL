<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Promo;

Route::post('/check-promo', function (Request $request) {
    $request->validate([
        'kode_promo' => 'required|string',
        'buku_id' => 'required|exists:buku,id',
        'tipe_buku' => 'required|in:fisik,ebook',
        'subtotal' => 'required|numeric|min:0'
    ]);

    $promo = Promo::where('kode_promo', $request->kode_promo)
                  ->where('tanggal_mulai', '<=', now())
                  ->where('tanggal_selesai', '>=', now())
                  ->where('status', 'aktif')
                  ->first();

    if (!$promo) {
        return response()->json([
            'valid' => false,
            'message' => 'Kode promo tidak valid atau sudah expired'
        ]);
    }

    // Check kuota
    if ($promo->kuota && $promo->kuota_terpakai >= $promo->kuota) {
        return response()->json([
            'valid' => false,
            'message' => 'Kuota promo sudah habis'
        ]);
    }

    // Check minimum pembelian
    if ($promo->minimum_pembelian && $request->subtotal < $promo->minimum_pembelian) {
        return response()->json([
            'valid' => false,
            'message' => 'Minimum pembelian Rp ' . number_format($promo->minimum_pembelian, 0, ',', '.')
        ]);
    }

    // Hitung diskon
    $diskon = 0;
    if ($promo->tipe_diskon === 'persen') {
        $diskon = ($request->subtotal * $promo->nilai_diskon) / 100;
        if ($promo->maksimal_diskon) {
            $diskon = min($diskon, $promo->maksimal_diskon);
        }
    } else {
        $diskon = $promo->nilai_diskon;
    }

    return response()->json([
        'valid' => true,
        'message' => 'Promo berhasil diterapkan',
        'diskon' => $diskon,
        'promo' => [
            'kode' => $promo->kode_promo,
            'keterangan' => $promo->keterangan,
            'tipe_diskon' => $promo->tipe_diskon,
            'nilai_diskon' => $promo->nilai_diskon
        ]
    ]);
});
