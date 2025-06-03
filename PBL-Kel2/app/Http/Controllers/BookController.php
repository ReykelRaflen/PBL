<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan halaman home dengan daftar buku
     */
    public function home()
    {
        $books = Book::latest()->limit(12)->get();
        return view('home', compact('books'));
    }

    /**
     * Menampilkan detail buku berdasarkan ID
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        // Buku terkait berdasarkan penulis yang sama (kecuali buku saat ini)
        $relatedBooks = Book::where('penulis', $book->penulis)
                           ->where('id', '!=', $book->id)
                           ->limit(4)
                           ->get();

        // Jika tidak ada buku dari penulis yang sama, ambil buku random
        if ($relatedBooks->count() < 4) {
            $additionalBooks = Book::where('id', '!=', $book->id)
                                  ->whereNotIn('id', $relatedBooks->pluck('id'))
                                  ->inRandomOrder()
                                  ->limit(4 - $relatedBooks->count())
                                  ->get();
            
            $relatedBooks = $relatedBooks->merge($additionalBooks);
        }

        return view('books.detail', compact('book', 'relatedBooks'));
    }

    /**
     * Menampilkan daftar semua buku
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Pencarian berdasarkan judul atau penulis
        if ($request->has('search') && $request->search) {
            $query->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        $books = $query->latest()->paginate(12);

        return view('books.index', compact('books'));
    }
}
