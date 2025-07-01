<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerbitanIndividu;
use App\Models\Rekening;

class PenerbitanIndividuController extends Controller
{
public function showForm1()
{
    $rekening = Rekening::find(1); // Ambil rekening id 1
    return view('penerbitan_individu.pembayaran', compact('rekening'));
}

public function submitForm1(Request $request)
{
    $request->validate([
        'package' => 'required|string',
        'payment_receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Upload file
    $paymentReceiptName = time().'_'.$request->file('payment_receipt')->getClientOriginalName();
    $request->file('payment_receipt')->move(public_path('uploads/payment_receipt'), $paymentReceiptName);

    $penerbitan = PenerbitanIndividu::create([
        'pilihan_paket' => $request->package,
        'rekening_id' => 1,
        'payment_receipt' => $paymentReceiptName,
        'status_pembayaran' => 'pending' // default status
    ]);

    return redirect()->route('penerbitan_individu.notification', $penerbitan->id);
}

public function showNotification($id)
{
    $penerbitan = PenerbitanIndividu::findOrFail($id);
    return view('penerbitan_individu.notification', compact('penerbitan'));
}

public function showForm2($id)
{
    $penerbitan = PenerbitanIndividu::findOrFail($id);
    
    // Blokir akses jika belum diverifikasi
    if ($penerbitan->status_pembayaran != 'Valid') {
        return redirect()->route('penerbitan_individu.notification', $id)
               ->with('error', 'Pembayaran belum diverifikasi oleh admin');
    }

    return view('penerbitan_individu.uploadNaskah', compact('penerbitan'));
}

public function submitForm2(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:100',
        'author' => 'required|string|max:100',
        'synopsis' => 'required|string',
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
    $penerbitan = PenerbitanIndividu::find($request->penerbitan_id);
    $penerbitan->update([
        'judul_naskah' => $request->title,
        'nama_penulis' => $request->author,
        'deskripsi_singkat' => $request->synopsis,
        'file_naskah' => $fileNaskahName,
        'tanggal_penerbitan' => now()->toDateString(),
    ]);

    return redirect()->route('home')->with('message', 'Pengajuan buku berhasil dikirim.');
    return redirect()->back()->with('message', 'Pengajuan buku berhasil dikirim.');
}
}