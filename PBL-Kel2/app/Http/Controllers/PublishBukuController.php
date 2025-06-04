<?php

namespace App\Http\Controllers;

use App\Models\Publish_buku;
use Illuminate\Http\Request;
use App\Models\Book;

class PublishBukuController extends Controller
{
    public function index()
    {
        // Mengambil data buku dari database
        $publish_books = Publish_buku::latest()->paginate(25);
        return view('admin.publish_buku', compact('publish_books')); // Perbaiki 'books' menjadi 'publish_books'
    }

    public function create()
    {
        // Menampilkan form untuk membuat buku baru
        return view('admin.publish_buku.create');
    }
    
    public function store(Request $request)
    {
        // Validasi input sesuai kolom tabel
        $request->validate([
            'judul_buku'      => 'required|string|max:255',
            'harga'           => 'required|integer',
            'penulis'         => 'required|string|max:255',
            'penerbit'        => 'required|string|max:255',
            'isbn'            => 'required|numeric',
            'jumlah_halaman'  => 'required|integer',
            'tanggal_terbit'  => 'required|date',
            'deskripsi'       => 'required|string|max:500',
            'cover_buku'      => 'nullable|string|max:255',
        ]);

        // Membuat buku baru
        Book::create([
            'judul_buku'      => $request->judul_buku,
            'harga'           => $request->harga,
            'penulis'         => $request->penulis,
            'penerbit'        => $request->penerbit,
            'isbn'            => $request->isbn,
            'jumlah_halaman'  => $request->jumlah_halaman,
            'tanggal_terbit'  => $request->tanggal_terbit,
            'deskripsi'       => $request->deskripsi,
            'cover_buku'      => $request->cover_buku,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.publish_buku')->with('success', 'Buku berhasil diterbitkan.');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.publish_buku.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.publish_buku.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input sesuai kolom tabel
        $request->validate([
            'judul_buku'      => 'required|string|max:255',
            'harga'           => 'required|integer',
            'penulis'         => 'required|string|max:255',
            'penerbit'        => 'required|string|max:255',
            'isbn'            => 'required|numeric',
            'jumlah_halaman'  => 'required|integer',
            'tanggal_terbit'  => 'required|date',
            'deskripsi'       => 'required|string|max:500',
            'cover_buku'      => 'nullable|string|max:255',
        ]);

        $book = Book::findOrFail($id);
        $book->update([
            'judul_buku'      => $request->judul_buku,
            'harga'           => $request->harga,
            'penulis'         => $request->penulis,
            'penerbit'        => $request->penerbit,
            'isbn'            => $request->isbn,
            'jumlah_halaman'  => $request->jumlah_halaman,
            'tanggal_terbit'  => $request->tanggal_terbit,
            'deskripsi'       => $request->deskripsi,
            'cover_buku'      => $request->cover_buku,
        ]);

        return redirect()->route('admin.publish_buku')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('admin.publish_buku')->with('success', 'Buku berhasil dihapus.');
    }
}
