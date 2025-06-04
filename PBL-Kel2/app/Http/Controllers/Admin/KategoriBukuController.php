<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;

class KategoriBukuController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBuku::paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|unique:kategori_buku',
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        KategoriBuku::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
