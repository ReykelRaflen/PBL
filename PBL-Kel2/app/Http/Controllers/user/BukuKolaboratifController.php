<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BukuKolaboratif;
use App\Models\KategoriBuku;
use App\Models\BabBuku;
use App\Models\PesananKolaborasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BukuKolaboratifController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuKolaboratif::with(['babBuku', 'kategoriBuku'])
            ->where('status', 'aktif');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_buku_id', $request->kategori);
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('harga_min') || $request->filled('harga_max')) {
            $query->whereHas('babBuku', function ($q) use ($request) {
                if ($request->filled('harga_min')) {
                    $q->where('harga', '>=', $request->harga_min);
                }
                if ($request->filled('harga_max')) {
                    $q->where('harga', '<=', $request->harga_max);
                }
            });
        }

        // Filter berdasarkan ketersediaan
        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan === 'tersedia') {
                $query->whereHas('babBuku', function ($q) {
                    $q->where('status', 'tersedia');
                });
            } elseif ($request->ketersediaan === 'penuh') {
                $query->whereDoesntHave('babBuku', function ($q) {
                    $q->where('status', 'tersedia');
                });
            }
        }

        // Sorting
        switch ($request->get('sort', 'terbaru')) {
            case 'terlama':
                $query->oldest();
                break;
            case 'harga_terendah':
                $query->join('bab_buku', 'buku_kolaboratif.id', '=', 'bab_buku.buku_kolaboratif_id')
                    ->select('buku_kolaboratif.*')
                    ->groupBy('buku_kolaboratif.id')
                    ->orderBy(DB::raw('MIN(bab_buku.harga)'), 'asc');
                break;
            case 'harga_tertinggi':
                $query->join('bab_buku', 'buku_kolaboratif.id', '=', 'bab_buku.buku_kolaboratif_id')
                    ->select('buku_kolaboratif.*')
                    ->groupBy('buku_kolaboratif.id')
                    ->orderBy(DB::raw('MAX(bab_buku.harga)'), 'desc');
                break;
            case 'bab_terbanyak':
                $query->orderBy('total_bab', 'desc');
                break;
            default: // terbaru
                $query->latest();
                break;
        }

        $bukuKolaboratif = $query->paginate(12);

        // Data untuk filter - ambil dari tabel kategori_buku yang aktif
        $kategoriBuku = KategoriBuku::where('status', true)->orderBy('nama')->get();

        return view('buku-kolaboratif.index', compact('bukuKolaboratif', 'kategoriBuku'));
    }

    public function tampilkan($id, Request $request)
    {
        $bukuKolaboratif = BukuKolaboratif::with([
            'babBuku' => function ($query) {
                $query->orderBy('nomor_bab');
            },
            'kategoriBuku'
        ])->findOrFail($id);

        // Statistik bab berdasarkan tingkat kesulitan
        $statistikBab = [
            'mudah' => $bukuKolaboratif->babBuku->where('tingkat_kesulitan', 'mudah')->count(),
            'sedang' => $bukuKolaboratif->babBuku->where('tingkat_kesulitan', 'sedang')->count(),
            'sulit' => $bukuKolaboratif->babBuku->where('tingkat_kesulitan', 'sulit')->count(),
        ];

        // Jika request AJAX untuk auto-refresh
        if ($request->ajax() || $request->get('ajax') == '1') {
            $babStatus = [];
            foreach ($bukuKolaboratif->babBuku as $bab) {
                $babStatus[$bab->id] = $bab->status;
            }
            return response()->json([
                'success' => true,
                'babStatus' => $babStatus,
                'statistik' => $statistikBab,
                'progress' => [
                    'total' => $bukuKolaboratif->total_bab,
                    'tersedia' => $bukuKolaboratif->babBuku->where('status', 'tersedia')->count(),
                    'dipesan' => $bukuKolaboratif->babBuku->where('status', 'dipesan')->count(),
                    'selesai' => $bukuKolaboratif->babBuku->where('status', 'selesai')->count(),
                ]
            ]);
        }

        return view('buku-kolaboratif.tampilkan', compact('bukuKolaboratif', 'statistikBab'));
    }

    public function pilihBab(BukuKolaboratif $bukuKolaboratif, BabBuku $babBuku)
    {
        // Pastikan bab ini milik buku yang benar dan masih tersedia
        if ($babBuku->buku_kolaboratif_id !== $bukuKolaboratif->id) {
            abort(404, 'Bab tidak ditemukan dalam buku ini.');
        }

        if ($babBuku->status !== 'tersedia') {
            return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                           ->with('error', 'Bab ini sudah tidak tersedia.');
        }

        return view('buku-kolaboratif.pilih-bab', compact('bukuKolaboratif', 'babBuku'));
    }

    public function prosesPesanan(Request $request, BukuKolaboratif $bukuKolaboratif, BabBuku $babBuku)
    {
        // Validasi input
        $request->validate([
            'catatan' => 'nullable|string|max:500',
            'setuju' => 'required|accepted'
        ], [
            'setuju.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'setuju.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
            'catatan.max' => 'Catatan maksimal 500 karakter'
        ]);

        // Log untuk debugging
        Log::info('Proses pesanan dimulai', [
            'user_id' => Auth::id(),
            'buku_id' => $bukuKolaboratif->id,
            'bab_id' => $babBuku->id,
            'bab_status' => $babBuku->status
        ]);

        // Pastikan bab ini milik buku yang benar
        if ($babBuku->buku_kolaboratif_id !== $bukuKolaboratif->id) {
            return redirect()->back()->with('error', 'Bab tidak valid untuk buku ini.');
        }

        // Pastikan bab masih tersedia
        if ($babBuku->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Bab ini sudah tidak tersedia.');
        }

        // Cek apakah user sudah pernah memesan bab ini
        $existingOrder = PesananKolaborasi::where('user_id', Auth::id())
                                         ->where('buku_kolaboratif_id', $bukuKolaboratif->id)
                                         ->where('bab_buku_id', $babBuku->id)
                                         ->whereNotIn('status_pembayaran', ['dibatalkan', 'gagal'])
                                         ->first();

        if ($existingOrder) {
            return redirect()->back()->with('error', 'Anda sudah pernah memesan bab ini.');
        }

        try {
            DB::beginTransaction();

            // Refresh data bab untuk memastikan status terbaru
            $babBuku->refresh();
            
            if ($babBuku->status !== 'tersedia') {
                DB::rollback();
                return redirect()->back()->with('error', 'Bab ini baru saja dipesan oleh orang lain.');
            }

            // Buat pesanan
            $pesanan = PesananKolaborasi::create([
                'user_id' => Auth::id(),
                'buku_kolaboratif_id' => $bukuKolaboratif->id,
                'bab_buku_id' => $babBuku->id,
                'nomor_pesanan' => $this->generateNomorPesanan(),
                'jumlah_bayar' => $babBuku->harga ?? $bukuKolaboratif->harga_per_bab,
                'status_pembayaran' => 'menunggu',
                'status_penulisan' => 'belum_mulai',
                'catatan' => $request->catatan,
                'tanggal_pesanan' => now(),
                'batas_pembayaran' => now()->addHours(24)
            ]);

            // Update status bab menjadi dipesan
            $babBuku->update(['status' => 'dipesan']);

            DB::commit();

            Log::info('Pesanan berhasil dibuat', [
                'pesanan_id' => $pesanan->id,
                'nomor_pesanan' => $pesanan->nomor_pesanan
            ]);

            return redirect()->route('buku-kolaboratif.pembayaran', $pesanan->id)
                           ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran dalam 24 jam.');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error dalam proses pesanan', [
                'user_id' => Auth::id(),
                'buku_id' => $bukuKolaboratif->id,
                'bab_id' => $babBuku->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.')
                           ->withInput();
        }
    }

    public function pembayaran($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                                       ->where('user_id', Auth::id())
                                       ->findOrFail($pesananId);

        // Cek apakah pesanan sudah expired
        if (isset($pesananBuku->batas_pembayaran) && 
            $pesananBuku->batas_pembayaran < now() && 
            $pesananBuku->status_pembayaran === 'menunggu') {
            
            // Update status menjadi expired dan kembalikan status bab
            DB::transaction(function () use ($pesananBuku) {
                $pesananBuku->update(['status_pembayaran' => 'expired']);
                if ($pesananBuku->babBuku) {
                    $pesananBuku->babBuku->update(['status' => 'tersedia']);
                }
            });

            return redirect()->route('buku-kolaboratif.tampilkan', $pesananBuku->buku_kolaboratif_id)
                           ->with('error', 'Pesanan telah kedaluwarsa. Silakan pesan ulang.');
        }

        if (!in_array($pesananBuku->status_pembayaran, ['menunggu', 'pending'])) {
            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                           ->with('info', 'Pembayaran sudah diproses sebelumnya.');
        }

        return view('buku-kolaboratif.pembayaran', compact('pesananBuku'));
    }

   public function prosesPembayaran(Request $request, $pesananId)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer_bank,kartu_kredit,e_wallet',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB'
        ]);

        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                                       ->where('user_id', Auth::id())
                                       ->findOrFail($pesananId);

        // Cek apakah pembayaran masih bisa diproses
        if (!in_array($pesananBuku->status_pembayaran, ['menunggu', 'pending'])) {
            return redirect()->back()
                           ->with('error', 'Pembayaran sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiPembayaranPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'bukti_pembayaran_' . $pesananBuku->nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
                $buktiPembayaranPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
            }

            // Update data pembayaran - ini akan trigger event untuk buat laporan
            $pesananBuku->update([
                'status_pembayaran' => 'pending', // Ini akan trigger createLaporanPenjualan()
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPembayaranPath,
                'tanggal_bayar' => now()
            ]);

            DB::commit();

            Log::info('Bukti pembayaran berhasil diupload dan laporan dibuat', [
                'pesanan_id' => $pesananBuku->id,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan,
                'file_path' => $buktiPembayaranPath
            ]);

            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananBuku->id)
                           ->with('success', 'Bukti pembayaran berhasil diupload. Pembayaran akan diverifikasi dalam 1x24 jam.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika ada error
            if ($buktiPembayaranPath && Storage::disk('public')->exists($buktiPembayaranPath)) {
                Storage::disk('public')->delete($buktiPembayaranPath);
            }
            
            Log::error('Error dalam proses upload bukti pembayaran', [
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran. Silakan coba lagi.')
                           ->withInput();
        }
    }
    public function statusPesanan($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
                                       ->where('user_id', Auth::id())
                                       ->findOrFail($pesananId);

        return view('buku-kolaboratif.status-pesanan', compact('pesananBuku'));
    }

    private function generateNomorPesanan()
    {
        do {
            $nomor = 'PES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PesananKolaborasi::where('nomor_pesanan', $nomor)->exists());
        
        return $nomor;
    }

    private function generateInvoiceNumber()
    {
        do {
            $invoice = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (\App\Models\LaporanPenjualanKolaborasi::where('nomor_invoice', $invoice)->exists());
        
        return $invoice;
    }

     public function uploadNaskah(Request $request, $pesananId)
    {
        // Validasi input
        $request->validate([
            'file_naskah' => 'required|file|mimes:doc,docx,pdf|max:10240', // 10MB
            'judul_naskah' => 'required|string|max:255',
            'deskripsi_naskah' => 'nullable|string|max:1000',
            'jumlah_kata' => 'nullable|integer|min:500',
            'catatan_penulis' => 'nullable|string|max:500',
            'catatan_revisi' => 'nullable|string|max:500',
            'is_revision' => 'nullable|boolean'
        ], [
            'file_naskah.required' => 'File naskah harus diupload',
            'file_naskah.mimes' => 'Format file harus DOC, DOCX, atau PDF',
            'file_naskah.max' => 'Ukuran file maksimal 10MB',
            'judul_naskah.required' => 'Judul naskah harus diisi',
            'jumlah_kata.min' => 'Jumlah kata minimal 500'
        ]);

        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                                       ->where('user_id', Auth::id())
                                       ->findOrFail($pesananId);

        // Cek apakah pembayaran sudah lunas
        if ($pesananBuku->status_pembayaran !== 'lunas') {
            return redirect()->back()
                           ->with('error', 'Pembayaran belum terverifikasi. Tidak dapat upload naskah.');
        }

        // Cek apakah status penulisan memungkinkan upload
        if (!in_array($pesananBuku->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi'])) {
            return redirect()->back()
                           ->with('error', 'Status penulisan tidak memungkinkan untuk upload naskah.');
        }

        try {
            DB::beginTransaction();

            // Hapus file lama jika ada (untuk revisi)
            if ($pesananBuku->file_naskah && Storage::disk('public')->exists($pesananBuku->file_naskah)) {
                Storage::disk('public')->delete($pesananBuku->file_naskah);
            }

            // Upload file naskah
            $file = $request->file('file_naskah');
            $fileName = 'naskah_' . $pesananBuku->nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('naskah', $fileName, 'public');

            // Update data pesanan
            $updateData = [
                'file_naskah' => $filePath,
                'judul_naskah' => $request->judul_naskah,
                'deskripsi_naskah' => $request->deskripsi_naskah,
                'jumlah_kata' => $request->jumlah_kata,
                'catatan_penulis' => $request->catatan_penulis,
                'tanggal_upload_naskah' => now(),
                'status_penulisan' => 'sudah_kirim'
            ];

            // Jika ini adalah revisi, tambahkan catatan revisi
            if ($request->is_revision && $request->catatan_revisi) {
                $updateData['catatan_penulis'] = $request->catatan_revisi;
                $updateData['status_penulisan'] = 'sudah_kirim'; // Reset ke sudah kirim untuk review ulang
            }

            $pesananBuku->update($updateData);

            DB::commit();

            Log::info('Naskah berhasil diupload', [
                'pesanan_id' => $pesananBuku->id,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan,
                'file_path' => $filePath,
                'is_revision' => $request->is_revision ?? false
            ]);

            $message = $request->is_revision ? 
                'Revisi naskah berhasil diupload. Editor akan melakukan review ulang.' :
                'Naskah berhasil diupload. Editor akan melakukan review dalam 3-5 hari kerja.';

            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananBuku->id)
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            Log::error('Error dalam upload naskah', [
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat mengupload naskah. Silakan coba lagi.')
                           ->withInput();
        }
    }

    public function downloadNaskah($pesananId)
    {
        $pesananBuku = PesananKolaborasi::where('user_id', Auth::id())
                                       ->findOrFail($pesananId);

        if (!$pesananBuku->file_naskah || !Storage::disk('public')->exists($pesananBuku->file_naskah)) {
            return redirect()->back()
                           ->with('error', 'File naskah tidak ditemukan.');
        }

        $fileName = $pesananBuku->judul_naskah . '.' . pathinfo($pesananBuku->file_naskah, PATHINFO_EXTENSION);
        
        return Storage::disk('public')->download($pesananBuku->file_naskah, $fileName);
    }

}
