<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class PublishBukuController extends Controller
{
    public function index()
    {
        // Mengambil data buku dari database
        $books = Book::latest()->paginate(10);

        // Menampilkan view dengan data buku
        return view('admin.publish_buku', compact('books'));

    }
}
