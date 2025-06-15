<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;

class RekeningController extends Controller
{
    // Menampilkan daftar rekening
    public function index()
    {
        $rekening = Rekening::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.rekening.index', compact('rekening'));
    }

    // Menampilkan form untuk membuat rekening baru
    public function create()
    {
        return view('admin.rekening.create');
    }

    // Menyimpan data rekening baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        Rekening::create($request->all());

        return redirect()->route('admin.rekening.index')->with('success', 'Rekening berhasil ditambahkan.');
    }

    // Menampilkan detail rekening
    public function show($id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('admin.rekening.show', compact('rekening'));
    }

    // Form edit rekening
    public function edit($id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('admin.rekening.edit', compact('rekening'));
    }

    // Update data rekening
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        $rekening = Rekening::findOrFail($id);
        $rekening->update($request->all());

        return redirect()->route('admin.rekening.index')->with('success', 'Rekening berhasil diperbarui!');
    }

    // Menghapus rekening
    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        return redirect()->route('admin.rekening.index')->with('success', 'Rekening berhasil dihapus.');
    }
}
