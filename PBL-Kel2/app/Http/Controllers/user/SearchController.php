<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\Promo;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $kategori = $request->get('kategori');
        $promo = $request->get('promo');
        $has_ebook = $request->get('has_ebook');
        $stock_status = $request->get('stock_status');
        $sort = $request->get('sort', 'terbaru');

        // Base query dengan relasi
        $booksQuery = Book::with(['kategori', 'promo']);

        // Search by title, author, publisher, ISBN
        if ($query) {
            $booksQuery->where(function($q) use ($query) {
                $q->where('judul_buku', 'LIKE', '%' . $query . '%')
                  ->orWhere('penulis', 'LIKE', '%' . $query . '%')
                  ->orWhere('penerbit', 'LIKE', '%' . $query . '%')
                  ->orWhere('isbn', 'LIKE', '%' . $query . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $query . '%');
            });
        }

        // Filter by category
        if ($kategori) {
            $booksQuery->where('kategori_id', $kategori);
        }

        // Filter by promo
        if ($promo) {
            $booksQuery->where('promo_id', $promo);
        }

        // Filter by e-book availability
        if ($has_ebook) {
            $booksQuery->whereNotNull('file_buku');
        }

        // Filter by stock status
        if ($stock_status) {
            if ($stock_status === 'available') {
                $booksQuery->where('stok', '>', 0);
            } elseif ($stock_status === 'out_of_stock') {
                $booksQuery->where('stok', 0);
            } elseif ($stock_status === 'low_stock') {
                $booksQuery->where('stok', '>', 0)->where('stok', '<=', 5);
            }
        }

        // Sorting
        switch ($sort) {
            case 'terbaru':
                $booksQuery->orderBy('created_at', 'desc');
                break;
            case 'terlama':
                $booksQuery->orderBy('created_at', 'asc');
                break;
            case 'harga_terendah':
                $booksQuery->orderBy('harga', 'asc');
                break;
            case 'harga_tertinggi':
                $booksQuery->orderBy('harga', 'desc');
                break;
            case 'judul_az':
                $booksQuery->orderBy('judul_buku', 'asc');
                break;
            case 'judul_za':
                $booksQuery->orderBy('judul_buku', 'desc');
                break;
            case 'penulis_az':
                $booksQuery->orderBy('penulis', 'asc');
                break;
            default:
                $booksQuery->orderBy('created_at', 'desc');
        }

        $books = $booksQuery->paginate(12)->appends($request->all());

              // Get data for filters - perbaiki query untuk promos
        $categories = KategoriBuku::where('status', true)->orderBy('nama')->get();
        
        // Periksa kolom yang tersedia di tabel promos dan gunakan yang sesuai
        // Kemungkinan kolom: id, judul, deskripsi, created_at, dll
        $promos = Promo::where('status', 'Aktif')
            ->orderBy('created_at', 'desc') // atau gunakan kolom lain yang ada
            ->get();

        return view('user.search.index', compact(
            'books', 
            'categories', 
            'promos',
            'query', 
            'kategori', 
            'promo',
            'has_ebook',
            'stock_status',
            'sort'
        ));
    }
}
