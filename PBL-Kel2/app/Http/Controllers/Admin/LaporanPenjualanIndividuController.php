<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'paket' => 'required|in:silver,gold,diamond',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png',
            'status_pembayaran' => 'required|in:sukses,tidak sesuai'
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            Storage::makeDirectory('public/bukti');
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $validated['tanggal'] = now()->toDateString();
        $validated['invoice'] = random_int(100000, 999999);

        LaporanPenjualanIndividu::create($validated);

        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        return view('admin.penjualanIndividu.show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        return view('admin.penjualanIndividu.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'paket' => 'required|in:silver,gold,diamond',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png',
            'status_pembayaran' => 'required|in:sukses,tidak sesuai'
        ]);

        $laporan = LaporanPenjualanIndividu::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
                Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
            }
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $laporan->update($validated);

        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
            Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
        }
        $laporan->delete();

        return redirect()->route('penjualanIndividu.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}