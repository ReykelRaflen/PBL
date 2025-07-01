<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekening;

class RekeningController extends Controller
{
    public function index()
    {
        $rekening = Rekening::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.rekening.index', compact('rekening'));
    }

    public function create()
    {
        return view('admin.rekening.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'bank' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:100',
        ]);

        // Simpan ke database
        Rekening::create($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Temukan rekening berdasarkan ID
        $rekening = Rekening::findOrFail($id);
        return view('admin.rekening.edit', compact('rekening'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'bank' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:100',
        ]);

        // Temukan rekening berdasarkan ID dan perbarui
        $rekening = Rekening::findOrFail($id);
        $rekening->update($validated);

        return redirect()->route('rekening.index')->with('success', 'Rekening berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        return redirect()->back()->with('success', 'Data rekening berhasil dihapus!');
    }
}
    