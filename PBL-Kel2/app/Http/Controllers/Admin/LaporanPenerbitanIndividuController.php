<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanBukuIndividu;
use Illuminate\Http\Request;

class LaporanPenerbitanIndividuController extends Controller
{
    public function index()
    {
        $laporans = LaporanBukuIndividu::all();
        return view('admin.penerbitanIndividu.index', compact('laporans'));
    }

    public function create()
    {
        return view('admin.penerbitanIndividu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|unique:laporan_penerbitan_individu',
            'judul' => 'required',
            'penulis' => 'required',
            'tanggal_terbit' => 'required|date',
            'jumlah_terjual' => 'required|integer',
            'status' => 'required|in:proses,terbit,pending',
        ]);

        LaporanBukuIndividu::create($request->all());
        return redirect()->route('penerbitanIndividu.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $laporan = LaporanBukuIndividu::findOrFail($id);
        return view('admin.penerbitanIndividu.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanBukuIndividu::findOrFail($id);

        $request->validate([
            'kode_buku' => 'required|unique:laporan_penerbitan_individu,kode_buku,' . $id,
            'judul' => 'required',
            'penulis' => 'required',
            'tanggal_terbit' => 'required|date',
            'jumlah_terjual' => 'required|integer',
            'status' => 'required|in:proses,terbit,pending',
        ]);

        $laporan->update($request->all());
        return redirect()->route('penerbitanIndividu.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        LaporanBukuIndividu::destroy($id);
        return redirect()->route('penerbitanIndividu.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
