<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 buku terbaru untuk ditampilkan di home
        $books = Book::latest()->take(8)->get();
        
        return view('home', compact('books'));
    }
}
