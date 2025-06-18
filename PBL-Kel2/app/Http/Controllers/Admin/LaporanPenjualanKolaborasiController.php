<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LaporanPenjualanKolaborasi;

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
        $validated = $request->validate([
            'judul' => 'required|string|max:1000',
            'penulis' => 'required|string|max:255',
            'bab' => 'required|string|max:255',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_pembayaran' => 'required|in:sukses,tidak sesuai',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $validated['tanggal'] = now()->toDateString();
        $validated['invoice'] = random_int(100000, 999999);

        LaporanPenjualanKolaborasi::create($validated);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        return view('admin.penjualanKolaborasi.show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        return view('admin.penjualanKolaborasi.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:1000',
            'penulis' => 'required|string|max:255',
            'bab' => 'required|string|max:255',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_pembayaran' => 'required|in:sukses,tidak sesuai',
        ]);

        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
                Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
            }
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $laporan->update($validated);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
            Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
        }
        $laporan->delete();

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
