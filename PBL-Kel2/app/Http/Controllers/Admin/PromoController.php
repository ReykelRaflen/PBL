<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->get();
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promos',
            'keterangan' => 'required|string',
            'tipe' => 'required|in:Persentase,Nominal',
            'besaran' => 'required|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Set kuota_terpakai to 0 by default
        $validated['kuota_terpakai'] = 0;

        Promo::create($validated);

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function show(Promo $promo)
    {
        return view('admin.promos.show', compact('promo'));
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promos,kode_promo,' . $promo->id,
            'keterangan' => 'required|string',
            'tipe' => 'required|in:Persentase,Nominal',
            'besaran' => 'required|numeric|min:0',
            'kuota' => 'nullable|integer|min:' . $promo->kuota_terpakai,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $promo->update($validated);

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}
