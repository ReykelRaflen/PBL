<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanBukuKolaborasi;
use Illuminate\Http\Request;

class LaporanPenerbitanKolaborasiController extends Controller
{
    public function index()
    {

        $laporans = LaporanBukuKolaborasi::all();
        return view('admin.penerbitanKolaborasi.index', compact('laporans'));
    }

    public function create()
    {
        return view('admin.penerbitanKolaborasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|unique:laporan_penerbitan_kolaborasi',
            'judul' => 'required',
            'bab_buku'=> 'required',
            'penulis' => 'required',
            'tanggal_terbit' => 'required|date',
            'jumlah_terjual' => 'required|integer',
            'status' => 'required|in:proses,terbit,pending',
        ]);

        LaporanBukuKolaborasi::create($request->all());
        return redirect()->route('penerbitanKolaborasi.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $laporan = LaporanBukuKolaborasi::findOrFail($id);
        return view('admin.penerbitanKolaborasi.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanBukuKolaborasi::findOrFail($id);

        $request->validate([
            'kode_buku' => 'required|unique:laporan_penerbitan,kode_buku,' . $id,
            'bab_buku' => 'required',
            'judul' => 'required',
            'penulis' => 'required',
            'tanggal_terbit' => 'required|date',
            'jumlah_terjual' => 'required|integer',
            'status' => 'required|in:proses,terbit,pending',
        ]);

        $laporan->update($request->all());
        return redirect()->route('penerbitanKolaborasi.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        LaporanBukuKolaborasi::destroy($id);
        return redirect()->route('penerbitanKolaborasi.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
