<?php
namespace App\Http\Controllers\Admin;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriBukuController extends Controller
{
    public function index()
    {
        $kategori = KategoriBuku::paginate(10);
        return view('admin.kategoriBuku.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategoriBuku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        KategoriBuku::create([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('kategori-buku.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriBuku = KategoriBuku::findOrFail($id);
        return view('admin.kategoriBuku.edit', compact('kategoriBuku'));
    }

    public function update(Request $request, $id)
    {
        $kategoriBuku = KategoriBuku::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);
        
        $kategoriBuku->update([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('kategori-buku.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        KategoriBuku::destroy($id);
        return redirect()->route('kategori-buku.index')->with('success', 'Kategori berhasil dihapus');
    }
}
