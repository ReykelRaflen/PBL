<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookManagementController extends Controller
{
    public function index(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $query = Book::with(['kategori', 'promo']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan promo
        if ($request->has('promo_id') && $request->promo_id) {
            $query->where('promo_id', $request->promo_id);
        }

        $books = $query->latest()->paginate(10);

        // Ambil kategori untuk filter - PERBAIKAN DI SINI
        $categories = KategoriBuku::where('status', true)->get();
        
        // Ambil promo untuk filter
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.index', compact('books', 'categories', 'promos'));
    }

    public function create()
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $categories = KategoriBuku::where('status', true)->get();
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.create', compact('categories', 'promos'));
    }

    public function store(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku,isbn',
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'nullable|file|mimes:pdf|max:10240',
            'promo_id' => 'nullable|exists:promos,id',
        ]);

        // Handle file upload
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        if ($request->hasFile('file_buku')) {
            $validated['file_buku'] = $request->file('file_buku')->store('books', 'public');
        }

        // Hitung harga promo jika ada
        if ($validated['promo_id']) {
            $promo = Promo::find($validated['promo_id']);
            if ($promo && $promo->isActive()) {
                if ($promo->tipe === 'Persentase') {
                    $validated['harga_promo'] = $validated['harga'] * (1 - $promo->besaran / 100);
                } else {
                    $validated['harga_promo'] = max(0, $validated['harga'] - $promo->besaran);
                }
            }
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::with(['kategori', 'promo'])->findOrFail($id);
        return view('admin.books.show', compact('book'));
    }

    public function edit($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::with(['kategori', 'promo'])->findOrFail($id);
        $categories = KategoriBuku::where('status', true)->get();
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.edit', compact('book', 'categories', 'promos'));
    }

    public function update(Request $request, $id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku,isbn,' . $book->id,
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'nullable|file|mimes:pdf|max:10240',
            'promo_id' => 'nullable|exists:promos,id',
        ]);

        // Handle file upload
        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        if ($request->hasFile('file_buku')) {
            // Hapus file lama
            if ($book->file_buku) {
                Storage::disk('public')->delete($book->file_buku);
            }
            $validated['file_buku'] = $request->file('file_buku')->store('books', 'public');
        }

        // Hitung harga promo jika ada
        if ($validated['promo_id']) {
            $promo = Promo::find($validated['promo_id']);
            if ($promo && $promo->isActive()) {
                if ($promo->tipe === 'Persentase') {
                    $validated['harga_promo'] = $validated['harga'] * (1 - $promo->besaran / 100);
                } else {
                    $validated['harga_promo'] = max(0, $validated['harga'] - $promo->besaran);
                }
            }
        } else {
            $validated['harga_promo'] = null;
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::findOrFail($id);

        // Hapus file terkait
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }
        if ($book->file_buku) {
            Storage::disk('public')->delete($book->file_buku);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
