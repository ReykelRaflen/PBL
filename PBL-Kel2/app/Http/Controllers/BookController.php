<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan halaman home dengan daftar buku
     */
    public function home()
    {
        $books = Book::with(['kategori', 'promo'])->latest()->limit(12)->get();
        return view('home', compact('books'));
    }

    /**
     * Menampilkan detail buku berdasarkan ID
     */
    public function show($id)
    {
        $book = Book::with(['kategori', 'promo'])->findOrFail($id);
        
        // Buku terkait berdasarkan kategori yang sama
        $relatedBooks = Book::with(['kategori', 'promo'])
                           ->where('kategori_id', $book->kategori_id)
                           ->where('id', '!=', $book->id)
                           ->limit(4)
                           ->get();

        // Jika tidak cukup, ambil berdasarkan penulis yang sama
        if ($relatedBooks->count() < 4) {
            $additionalBooks = Book::with(['kategori', 'promo'])
                                  ->where('penulis', $book->penulis)
                                  ->where('id', '!=', $book->id)
                                  ->whereNotIn('id', $relatedBooks->pluck('id'))
                                  ->limit(4 - $relatedBooks->count())
                                  ->get();
            
            $relatedBooks = $relatedBooks->merge($additionalBooks);
        }

        // Jika masih kurang, ambil buku random
        if ($relatedBooks->count() < 4) {
            $randomBooks = Book::with(['kategori', 'promo'])
                              ->where('id', '!=', $book->id)
                              ->whereNotIn('id', $relatedBooks->pluck('id'))
                              ->inRandomOrder()
                              ->limit(4 - $relatedBooks->count())
                              ->get();
            
            $relatedBooks = $relatedBooks->merge($randomBooks);
        }

        return view('books.detail', compact('book', 'relatedBooks'));
    }

    /**
     * Menampilkan daftar semua buku
     */
    public function index(Request $request)
    {
        $query = Book::with(['kategori', 'promo']);

        // Pencarian berdasarkan judul atau penulis
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan promo
        if ($request->has('promo') && $request->promo == '1') {
            $query->whereNotNull('promo_id')->whereNotNull('harga_promo');
        }

        $books = $query->latest()->paginate(12);
        
        // Ambil semua kategori untuk filter
        $categories = KategoriBuku::where('status', true)->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Get categories yang digunakan oleh buku
     */
    public function getUsedCategories()
    {
        return KategoriBuku::whereHas('books')->get();
    }
}
