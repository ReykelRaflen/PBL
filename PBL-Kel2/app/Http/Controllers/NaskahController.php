<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Naskah;

class NaskahController extends Controller
{
    // Menampilkan daftar naskah
    public function index()
{
    // Hitung jumlah berdasarkan status
    $dalamReview = Naskah::where('status', 'Dalam Review')->count();
    $siapTerbit  = Naskah::where('status', 'Siap Terbit')->count();
    $ditolak     = Naskah::where('status', 'Ditolak')->count();
    $totalNaskah = $dalamReview + $siapTerbit + $ditolak;

    // Ambil data naskah dengan pagination, urut berdasarkan created_at terbaru
    $naskahs = Naskah::orderBy('created_at', 'desc')->paginate(25);

    // Kirim data ke view admin.naskah (sesuaikan dengan nama view Anda)
    return view('admin.naskah', compact('naskahs', 'dalamReview', 'siapTerbit', 'ditolak', 'totalNaskah'));
}

    // Menampilkan detail naskah tertentu
    public function show($naskahs)
    {
        $naskah = Naskah::findOrFail($naskahs);
        return view('admin.naskah.show', compact('naskah'));
    }
}
