<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil buku terbaru dengan relasi
        $books = Book::with(['kategori', 'promo'])
                    ->latest()
                    ->limit(12)
                    ->get();

        // Ambil kategori yang memiliki buku
        $categories = KategoriBuku::whereHas('books')
                                 ->where('status', true)
                                 ->get();

        // Ambil buku dengan promo aktif
        $promoBooks = Book::with(['kategori', 'promo'])
                         ->whereHas('promo', function($query) {
                             $query->where('status', 'Aktif')
                                   ->where('tanggal_mulai', '<=', now())
                                   ->where('tanggal_selesai', '>=', now());
                         })
                         ->limit(6)
                         ->get();

        return view('home', compact('books', 'categories', 'promoBooks'));
    }
}
