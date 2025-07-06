<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Bab;
use App\Models\Rekening;

class PenerbitanKolaborasiController extends Controller
{
    public function listBuku()
    {
        $buku = Buku::all(); // Fetch all books
        return view('penerbitan_kolaborasi.list', compact('buku'));
    }

    public function showBuku($id)
    {
        $buku = Buku::findOrFail($id);
        $bab = $buku->bab()->where('status', 'available')->get(); // Fetch accessible chapters
        return view('penerbitan_kolaborasi.detail', compact('buku', 'bab'));
    }

    public function pesanBab(Request $request)
    {
        $request->validate([
            'bab_id' => 'required|exists:bab,id',
        ]);

        $bab = Bab::findOrFail($request->bab_id);
        return view('penerbitan_kolaborasi.pesanBab', compact('bab'));
    }

    public function submitPesanBab(Request $request)
    {
        $request->validate([
            'bab_id' => 'required|exists:bab,id',
        ]);

        $bab = Bab::findOrFail($request->bab_id);
        $bab->status = 'reserved';
        $bab->status_pembayaran = 'pending';
        $bab->save();

        return redirect()->route('penerbitan_kolaborasi.uploadBuktiPembayaran', $bab->id)
                         ->with('message', 'Pesanan berhasil. Silakan upload bukti pembayaran.');
    }

    public function showUploadBuktiPembayaran($bab_id)
    {
        return view('penerbitan_kolaborasi.uploadBuktiPembayaran', compact('bab_id'));
    }

    public function uploadBuktiPembayaran($id)
    {
        $bab = Bab::findOrFail($id);
        return view('penerbitan_kolaborasi.uploadBuktiPembayaran', compact('bab'));
    }

   public function submitUploadBuktiPembayaran(Request $request)
    {
        // Validasi input
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Temukan bab berdasarkan ID
        $bab = Bab::findOrFail($request->bab_id);
        
        // Simpan bukti pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran');

        // Update informasi bab
        $bab->bukti_pembayaran = $path;
        $bab->status_pembayaran = 'pending'; // Reset status ke pending
        $bab->save();

        // Redirect ke halaman notifikasi
        return redirect()->route('penerbitan_kolaborasi.notification', $bab->id)
                        ->with('message', 'Bukti pembayaran berhasil diupload. Tunggu konfirmasi admin.');
    }
    

    public function showNotification($id)
    {
        $bab = Bab::findOrFail($id);
        return view('penerbitan_kolaborasi.notification', compact('bab'));
    }


    
    public function showUploadNaskah($id)
    {
        return view('penerbitan_kolaborasi.uploadNaskah', [
            'bab_id' => $id
        ]);
    }



    public function uploadNaskah(Request $request)
    {
        $request->validate([
            'bab_id' => 'required|exists:bab,id',
            'nama_penulis' => 'required|string|max:100',
            'file_naskah' => 'required|mimes:pdf,doc,docx',
        ]);

        // Siapkan nama file (jika file dikirim)
        $fileNaskahName = null;

        // Upload file naskah
        if ($request->hasFile('file_naskah')) {
            $fileNaskahName = time() . '_' . $request->file('file_naskah')->getClientOriginalName();
            $request->file('file_naskah')->move(public_path('uploads/file_naskah'), $fileNaskahName);
        }
    
        // Simpan ke database
        $bab = Bab::find($request->bab_id);
        $bab->update([
            'nama_penulis' => $request->nama_penulis,
            'file_naskah' => $fileNaskahName,
            'tanggal_penerbitan' => now()->toDateString(),
            
        ]);
        

        return redirect()->route('home')->with('message', 'Pengajuan naskah berhasil dikirim.');
    }
}
