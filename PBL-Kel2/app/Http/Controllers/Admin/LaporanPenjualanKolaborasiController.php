<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanKolaborasi;
use Illuminate\Http\Request;

class LaporanPenjualanKolaborasiController extends Controller
{
    public function index()
    {
        $laporan = LaporanPenjualanKolaborasi::latest()->get();
        return view('admin.penjualanKolaborasi.index', compact('laporan'));
    }

    public function create()
    {
        return view('admin.penjualanKolaborasi.create');
    }

    public function store(Request $request)
    {
     
    
        $data = $request->validate([
            'penulis' => 'required|string|max:100',
            'judul_buku' => 'required|string|max:255',
            'jumlah_terjual' => 'required|integer|min:1',
            'total_harga' => 'required|double',
            'tanggal_penjualan' => 'required|date',
            'status_pembayaran' => 'required|in:Valid,Tidak Valid',
        ]);
    
        LaporanPenjualanKolaborasi::create([
           'penulis' => $data['penulis'],
            'judul_buku' => $data['judul_buku'],
            'jumlah_terjual' => $data['jumlah_terjual'],
            'total_harga' => $data['total_harga'],
            'tanggal_penjualan' => $data['tanggal_penjualan'],
            'status_pembayaran' => $data['status_pembayaran'],
        ]);
    
        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }
    


    public function edit($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        return view('admin.penjualanKolaborasi.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'penulis' => 'required|string|max:100',
            'judul_buku' => 'required|string|max:255',
            'jumlah_terjual' => 'required|integer|min:1',
            'total_harga' => 'required|double',
            'tanggal_penjualan' => 'required|date',
            'status_pembayaran' => 'required|in:valid,tidak valid',
        ]);

        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        $laporan->update($validated);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        $laporan->delete();

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
