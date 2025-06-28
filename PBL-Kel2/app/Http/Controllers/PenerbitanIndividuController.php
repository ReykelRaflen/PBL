<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerbitanIndividu;
use App\Models\Rekening;

class PenerbitanIndividuController extends Controller
{
    public function showForm()
    {
        $rekening = Rekening::find(1); // Ambil rekening id 1
        return view('penerbitan_individu.form', compact('rekening'));
    }

    public function submit(Request $request)
    {
        // Validasi semua inputan
        $request->validate([
            'package' => 'required|string',
            'title' => 'required|string|max:100',
            'author' => 'required|string|max:100',
            'synopsis' => 'required|string',
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg,|max:2048', 
            'file_naskah' => 'required|mimes:pdf,doc,docx',
        ]);

        // Siapkan nama file (jika file dikirim)
        $paymentReceiptName = null;
        $fileNaskahName = null;

        // Upload file bukti pembayaran
        if ($request->hasFile('payment_receipt')) {
            $paymentReceiptName = time() . '_' . $request->file('payment_receipt')->getClientOriginalName();
            $request->file('payment_receipt')->move(public_path('uploads/payment_receipt'), $paymentReceiptName);
        }

        // Upload file naskah
        if ($request->hasFile('file_naskah')) {
            $fileNaskahName = time() . '_' . $request->file('file_naskah')->getClientOriginalName();
            $request->file('file_naskah')->move(public_path('uploads/file_naskah'), $fileNaskahName);
        }

        // Simpan ke database
        PenerbitanIndividu::create([
            'pilihan_paket' => $request->package,
            'rekening_id' => 1, // Default ke rekening id 1
            'judul_naskah' => $request->title,
            'nama_penulis' => $request->author,
            'deskripsi_singkat' => $request->synopsis,
            'tanggal_penerbitan' => now()->toDateString(),
            'payment_receipt' => $paymentReceiptName,
            'file_naskah' => $fileNaskahName,
        ]);

        return redirect()->back()->with('message', 'Silahkan tunggu beberapa saat sampai admin memvalidasi pembayaran anda.');
    }
}
