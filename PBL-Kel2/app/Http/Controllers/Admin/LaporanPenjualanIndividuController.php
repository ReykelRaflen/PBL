<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanIndividu;
use Illuminate\Http\Request;

class LaporanPenjualanIndividuController extends Controller
{
    public function index()
    {
        $laporan = LaporanPenjualanIndividu::latest()->get();
        return view('admin.penjualanIndividu.index', compact('laporan'));
    }

    public function create()
    {
        return view('admin.penjualanIndividu.create');
    }

    public function store(Request $request)
    {
     
    
        $data = $request->validate([
            'penulis' => 'required|string|max:100',
            'judul_buku' => 'required|string|max:255',
            'jumlah_terjual' => 'required|integer|min:1',
            'tanggal_penjualan' => 'required|date',
            'status_pembayaran' => 'required|in:Valid,Tidak Valid',
        ]);
    
        LaporanPenjualanIndividu::create([
            'penulis' => $data['penulis'],
            'judul_buku' => $data['judul_buku'],
            'jumlah_terjual' => $data['jumlah_terjual'],
            'tanggal_penjualan' => $data['tanggal_penjualan'],
            'status_pembayaran' => $data['status_pembayaran'],
        ]);
    
        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }
    


    public function edit($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        return view('admin.penjualanIndividu.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'penulis' => 'required|string|max:100',
            'judul_buku' => 'required|string|max:255',
            'jumlah_terjual' => 'required|integer|min:1',
            'tanggal_penjualan' => 'required|date',
            'status_pembayaran' => 'required|in:valid,tidak valid',
        ]);

        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        $laporan->update($validated);

        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        $laporan->delete();

        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}