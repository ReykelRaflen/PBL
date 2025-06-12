<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publish_Buku;

class PublishBukuController extends Controller
{
    // Menampilkan daftar buku terbitan dengan pagination
    public function index()
    {
        $publish_books = Publish_Buku::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.publish_buku.index', compact('publish_books'));
    }

    // Menampilkan form untuk membuat buku baru
    public function create()
    {
        return view('admin.publish_buku.create');
    }

    // Menyimpan data buku baru ke database
    public function store(Request $request)
{
    // Validasi input dari pengguna
    $request->validate([
        'judul_buku'      => 'required|string|max:255',
        'penulis'         => 'required|string|max:255',
        'penerbit'        => 'required|string|max:255',
        'isbn'            => 'required|numeric',
        'jumlah_halaman'  => 'required|integer',
        'harga'           => 'required|numeric',
        'diskon'          => 'required|numeric',
        'deskripsi'       => 'nullable|string|max:500',
        'cover_buku'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'tanggal_terbit'  => 'required|date',
    ]);

    // Membuat instance model Publish_Buku
    $book = new Publish_Buku();
    $book->judul_buku = $request->judul_buku;
    $book->penulis = $request->penulis;
    $book->penerbit = $request->penerbit;
    $book->isbn = $request->isbn;
    $book->jumlah_halaman = $request->jumlah_halaman;
    $book->harga = $request->harga;
    $book->diskon = $request->diskon;
    $book->deskripsi = $request->deskripsi;
    $book->tanggal_terbit = $request->tanggal_terbit;

    // Menyimpan file cover jika ada
    if ($request->hasFile('cover_buku')) {
        $coverPath = $request->file('cover_buku')->store('covers', 'public');
        $book->cover_buku = $coverPath;
    }

    // Simpan data buku ke database
    $book->save();

    // Arahkan kembali dengan pesan sukses
    return redirect()->route('admin.index.publish_buku')->with('success', 'Buku berhasil diterbitkan.');
}


    // Menampilkan detail buku
    public function show($id)
    {
        $publish_book = Publish_Buku::findOrFail($id);
        return view('admin.publish_buku.show', compact('publish_book'));
    }

    // Form edit buku
    public function edit($id)
    {
        $publish_book = Publish_Buku::findOrFail($id);
        return view('admin.publish_buku.edit', compact('publish_book'));
    }

    // Update data buku
   public function update(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'judul_buku' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'diskon' => 'nullable|numeric|min:0|max:100',
        'penulis' => 'required|string|max:255',
        'penerbit' => 'required|string|max:255',
        'isbn' => 'nullable|string|max:13',
        'jumlah_halaman' => 'nullable|numeric',
        'tanggal_terbit' => 'nullable|date',
        'cover_buku' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'deskripsi' => 'required|string',
    ]);

    // Find the book by ID
    $publish_book = Publish_Buku::findOrFail($id);
    
    // Update the book details
    $publish_book->judul_buku = $request->judul_buku;
    $publish_book->harga = $request->harga;
    $publish_book->diskon = $request->diskon;
    $publish_book->penulis = $request->penulis;
    $publish_book->penerbit = $request->penerbit;
    $publish_book->isbn = $request->isbn;
    $publish_book->jumlah_halaman = $request->jumlah_halaman;
    $publish_book->tanggal_terbit = $request->tanggal_terbit;
    $publish_book->deskripsi = $request->deskripsi;
    
    // Handle cover upload if provided
    if ($request->hasFile('cover_buku')) {
        $path = $request->file('cover_buku')->store('covers', 'public');
        $publish_book->cover_buku = $path;
    }

    // Save the changes
    $publish_book->save();

    // Redirect to the admin.index.publish_buku route
    return redirect()->route('admin.index.publish_buku')->with('success', 'Buku berhasil diperbarui!');
}


    // Menghapus buku
    public function destroy($id)
    {
        $publish_book = Publish_Buku::findOrFail($id);
        $publish_book->delete();

        return redirect()->route('admin.publish_buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
