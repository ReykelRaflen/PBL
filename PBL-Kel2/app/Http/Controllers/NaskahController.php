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
public function show($naskahId)
{
    // Validasi bahwa $naskahId adalah integer
    if (!is_numeric($naskahId)) {
        return redirect()->back()->with('error', 'ID naskah tidak valid.');
    }

    // Ambil detail naskah
    $naskah = Naskah::find($naskahId);

    // Cek apakah naskah ditemukan
    if (!$naskah) {
        return redirect()->route('naskah.index')->with('error', 'Naskah tidak ditemukan.');
    }

    // Kembali ke tampilan dengan data naskah
    return view('admin.naskah.show', compact('naskah'));
}


// Menampilkan form edit naskah tertentu
    public function edit($naskahs)
    {
        $naskah = Naskah::findOrFail($naskahs);
        return view('admin.naskah.edit', compact('naskah'));
    }

    // Mengupdate naskah tertentu
    public function update(Request $request, $id)
{
    // Validasi input
    $validatedData = $request->validate([
        'judul' => 'required|string|max:255',
        'pengarang' => 'required|string|max:100',
        'deskripsi_singkat' => 'required|string|max:100',
        'tanggal' => 'required|date',
        'status' => 'required|in:Dalam Review,Siap Terbit,Ditolak',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:20480', // adjust max size as needed
        
    ]);

    $naskah = Naskah::findOrFail($id);
    $naskah->judul = $validatedData['judul'];
    $naskah->pengarang = $validatedData['pengarang'];
    $naskah->deskripsi_singkat = $validatedData['deskripsi_singkat'];
    $naskah->tanggal = $validatedData['tanggal'];
    $naskah->status = $validatedData['status'];

    if ($request->hasFile('file')) {
        // Process file upload and save filename
        $path = $request->file('file')->store('files', 'public');
        $naskah->file_naskah = $path; // Get the file path
    }

    $naskah->save();

    return redirect()->route('admin.naskah')->with('success', 'Naskah updated successfully!');
}



    // Menghapus naskah tertentu
    public function destroy($id)
    {
        $naskah = Naskah::findOrFail($id);
        $naskah->delete();

        return redirect()->route('admin.naskah.destroy')->with('success', 'Naskah berhasil dihapus.');
    }


}


