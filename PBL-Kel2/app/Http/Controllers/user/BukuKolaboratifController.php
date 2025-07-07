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

    /**
     * STEP 1: User memilih bab
     */
    public function pilihBab(BukuKolaboratif $bukuKolaboratif, BabBuku $babBuku)
    {
        // Pastikan bab ini milik buku yang benar
        if ($babBuku->buku_kolaboratif_id !== $bukuKolaboratif->id) {
            abort(404, 'Bab tidak ditemukan untuk buku ini.');
        }

        // Cek apakah bab masih tersedia
        if ($babBuku->status !== 'tersedia') {
            return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                ->with('error', 'Bab ini sudah tidak tersedia untuk dipesan.');
        }

        // Cek apakah user sudah pernah memesan bab ini
        if (Auth::check()) {
            $sudahPesan = PesananKolaborasi::where('user_id', Auth::id())
                ->where('bab_buku_id', $babBuku->id)
                ->whereIn('status_pembayaran', ['menunggu', 'pending', 'lunas'])
                ->exists();

            if ($sudahPesan) {
                return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                    ->with('error', 'Anda sudah pernah memesan bab ini.');
            }
        }

        return view('buku-kolaboratif.pilih-bab', compact('bukuKolaboratif', 'babBuku'));
    }

    /**
     * STEP 2: User konfirmasi pemesanan (belum bayar)
     */
   public function prosesPesanan(Request $request, BukuKolaboratif $bukuKolaboratif, BabBuku $babBuku)
{
    // Tambahkan logging untuk debug
    Log::info('Proses pesanan dimulai', [
        'user_id' => Auth::id(),
        'buku_id' => $bukuKolaboratif->id,
        'bab_id' => $babBuku->id,
        'request_data' => $request->all()
    ]);

    try {
        // Validasi input
        $validated = $request->validate([
            'catatan' => 'nullable|string|max:1000',
            'setuju' => 'required|accepted'
        ], [
            'setuju.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'setuju.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
            'catatan.max' => 'Catatan maksimal 1000 karakter'
        ]);

        Log::info('Validasi berhasil', ['validated' => $validated]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            Log::warning('User tidak login');
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan bab ini milik buku yang benar
        if ($babBuku->buku_kolaboratif_id !== $bukuKolaboratif->id) {
            Log::error('Bab tidak valid untuk buku ini', [
                'bab_buku_id' => $babBuku->buku_kolaboratif_id,
                'buku_kolaboratif_id' => $bukuKolaboratif->id
            ]);
            return redirect()->back()->with('error', 'Bab tidak valid untuk buku ini.');
        }

        DB::beginTransaction();

        // Lock bab untuk update dan refresh data
        $babBuku = BabBuku::lockForUpdate()->find($babBuku->id);

        Log::info('Status bab saat ini', [
            'bab_id' => $babBuku->id,
            'status' => $babBuku->status
        ]);

        // Cek status bab terbaru
        if ($babBuku->status !== 'tersedia') {
            DB::rollback();
            Log::warning('Bab tidak tersedia', ['status' => $babBuku->status]);
            return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                ->with('error', 'Bab ini sudah tidak tersedia untuk dipesan.');
        }

        // Cek apakah ada pesanan aktif untuk bab ini
        $pesananAktif = PesananKolaborasi::where('bab_buku_id', $babBuku->id)
            ->whereIn('status_pembayaran', ['menunggu', 'pending', 'lunas'])
            ->exists();

        if ($pesananAktif) {
            DB::rollback();
            Log::warning('Bab sudah ada pesanan aktif');
            return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                ->with('error', 'Bab ini baru saja dipesan oleh penulis lain.');
        }

        // Cek apakah user ini sudah pernah memesan bab ini
        $userSudahPesan = PesananKolaborasi::where('user_id', Auth::id())
            ->where('bab_buku_id', $babBuku->id)
            ->whereIn('status_pembayaran', ['menunggu', 'pending', 'lunas'])
            ->exists();

        if ($userSudahPesan) {
            DB::rollback();
            Log::warning('User sudah pernah memesan bab ini');
            return redirect()->route('buku-kolaboratif.tampilkan', $bukuKolaboratif)
                ->with('error', 'Anda sudah pernah memesan bab ini.');
        }

        // Generate nomor pesanan
        $nomorPesanan = $this->generateNomorPesanan();
        Log::info('Nomor pesanan generated', ['nomor' => $nomorPesanan]);

        // Hitung harga
        $harga = $babBuku->harga ?? $bukuKolaboratif->harga_per_bab ?? 0;
        Log::info('Harga dihitung', ['harga' => $harga]);

        // Buat pesanan dengan status menunggu pembayaran
        $pesanan = PesananKolaborasi::create([
            'user_id' => Auth::id(),
            'buku_kolaboratif_id' => $bukuKolaboratif->id,
            'bab_buku_id' => $babBuku->id,
            'nomor_pesanan' => $nomorPesanan,
            'jumlah_bayar' => $harga,
            'status_pembayaran' => 'menunggu',
            'status_penulisan' => 'belum_mulai',
            'catatan' => $validated['catatan'] ?? null,
            'tanggal_pesanan' => now(),
            'batas_pembayaran' => now()->addHours(24)
        ]);

        Log::info('Pesanan berhasil dibuat', [
            'pesanan_id' => $pesanan->id,
            'nomor_pesanan' => $pesanan->nomor_pesanan
        ]);

        // Update status bab menjadi dipesan
        $babBuku->update(['status' => 'dipesan']);
        Log::info('Status bab diupdate ke dipesan');

        DB::commit();

        Log::info('Transaksi berhasil, redirect ke pembayaran', [
            'pesanan_id' => $pesanan->id
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route('buku-kolaboratif.pembayaran', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran dalam 24 jam.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        Log::error('Validation error', ['errors' => $e->errors()]);
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Data yang dimasukkan tidak valid.');

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


    /**
     * STEP 3: Halaman pembayaran
     */
    public function pembayaran($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        // Cek apakah pesanan sudah expired
        if (
            isset($pesananBuku->batas_pembayaran) &&
            $pesananBuku->batas_pembayaran < now() &&
            $pesananBuku->status_pembayaran === 'menunggu'
        ) {
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

        // Hanya bisa akses halaman pembayaran jika status menunggu
        if ($pesananBuku->status_pembayaran !== 'menunggu') {
            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('info', 'Pembayaran sudah diproses sebelumnya.');
        }

        return view('buku-kolaboratif.pembayaran', compact('pesananBuku'));
    }

        /**
     * STEP 4: User upload bukti pembayaran
     */
    public function prosesPembayaran(Request $request, $pesananId)
    {
        // Validasi input
        $validated = $request->validate([
            'metode_pembayaran' => 'required|string|in:transfer_bank,e_wallet,virtual_account,qris',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'catatan_pembayaran' => 'nullable|string|max:500'
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
            'catatan_pembayaran.max' => 'Catatan maksimal 500 karakter'
        ]);

        try {
            $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                ->where('user_id', Auth::id())
                ->findOrFail($pesananId);

            // Cek apakah pesanan masih dalam status menunggu
            if ($pesananBuku->status_pembayaran !== 'menunggu') {
                return redirect()->back()
                    ->with('error', 'Pesanan ini sudah tidak dapat diproses pembayaran.');
            }

            // Cek apakah pesanan sudah expired
            if (
                isset($pesananBuku->batas_pembayaran) &&
                $pesananBuku->batas_pembayaran < now()
            ) {
                return redirect()->back()
                    ->with('error', 'Pesanan telah kedaluwarsa. Silakan pesan ulang.');
            }

            DB::beginTransaction();

            // Upload bukti pembayaran
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $pesananBuku->nomor_pesanan . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('bukti-pembayaran', $filename, 'public');

            // Update pesanan dengan bukti pembayaran
            $pesananBuku->update([
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'bukti_pembayaran' => $filePath,
                'status_pembayaran' => 'pending', // Menunggu verifikasi admin
                'tanggal_bayar' => now(),
                'catatan' => $validated['catatan_pembayaran'] ?? $pesananBuku->catatan
            ]);

            // Buat laporan penjualan untuk admin
            $pesananBuku->createLaporanPenjualan();

            DB::commit();

            Log::info('Bukti pembayaran uploaded', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan,
                'metode_pembayaran' => $validated['metode_pembayaran']
            ]);

            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin (1-2 hari kerja).');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error uploading payment proof', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran.')
                ->withInput();
        }
    }

    /**
     * STEP 5: Halaman status pesanan
     */
    public function statusPesanan($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        return view('buku-kolaboratif.status-pesanan', compact('pesananBuku'));
    }

    /**
     * STEP 6: Halaman upload naskah (setelah pembayaran diverifikasi)
     */
    public function uploadNaskah($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        // Cek apakah pembayaran sudah lunas
        if ($pesananBuku->status_pembayaran !== 'lunas') {
            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('error', 'Pembayaran belum diverifikasi. Tidak dapat mengupload naskah.');
        }

        // Cek apakah sudah bisa mulai menulis
        if (!in_array($pesananBuku->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi'])) {
            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('error', 'Status penulisan tidak memungkinkan untuk upload naskah.');
        }

        return view('buku-kolaboratif.upload-naskah', compact('pesananBuku'));
    }

    /**
     * STEP 7: Proses upload naskah
     */
    public function prosesUploadNaskah(Request $request, $pesananId)
    {
        // Validasi input
        $validated = $request->validate([
            'judul_naskah' => 'required|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'file_naskah' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_kata' => 'nullable|integer|min:1',
            'catatan_penulis' => 'nullable|string|max:1000'
        ], [
            'judul_naskah.required' => 'Judul naskah harus diisi',
            'judul_naskah.max' => 'Judul naskah maksimal 500 karakter',
            'deskripsi_naskah.max' => 'Deskripsi naskah maksimal 2000 karakter',
            'file_naskah.required' => 'File naskah harus diupload',
            'file_naskah.mimes' => 'Format file harus PDF, DOC, atau DOCX',
            'file_naskah.max' => 'Ukuran file maksimal 10MB',
            'jumlah_kata.integer' => 'Jumlah kata harus berupa angka',
            'jumlah_kata.min' => 'Jumlah kata minimal 1',
            'catatan_penulis.max' => 'Catatan penulis maksimal 1000 karakter'
        ]);

        try {
            $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                ->where('user_id', Auth::id())
                ->findOrFail($pesananId);

            // Cek apakah pembayaran sudah lunas
            if ($pesananBuku->status_pembayaran !== 'lunas') {
                return redirect()->back()
                    ->with('error', 'Pembayaran belum diverifikasi. Tidak dapat mengupload naskah.');
            }

            // Cek apakah bisa upload naskah
            if (!$pesananBuku->canUploadNaskah()) {
                return redirect()->back()
                    ->with('error', 'Status tidak memungkinkan untuk upload naskah.');
            }

            DB::beginTransaction();

            // Hapus file lama jika ada
            if ($pesananBuku->file_naskah && Storage::disk('public')->exists($pesananBuku->file_naskah)) {
                Storage::disk('public')->delete($pesananBuku->file_naskah);
            }

            // Upload file naskah
            $file = $request->file('file_naskah');
            $filename = time() . '_' . str_replace(' ', '_', $validated['judul_naskah']) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('naskah', $filename, 'public');

            // Update pesanan dengan data naskah
            $pesananBuku->update([
                'judul_naskah' => $validated['judul_naskah'],
                'deskripsi_naskah' => $validated['deskripsi_naskah'],
                'file_naskah' => $filePath,
                'jumlah_kata' => $validated['jumlah_kata'],
                'catatan_penulis' => $validated['catatan_penulis'],
                'status_penulisan' => 'sudah_kirim', // Menunggu review admin
                'tanggal_upload_naskah' => now()
            ]);

            DB::commit();

            Log::info('Naskah uploaded', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan,
                'judul_naskah' => $validated['judul_naskah']
            ]);

            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('success', 'Naskah berhasil diupload! Menunggu review dari admin.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error uploading naskah', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload naskah.')
                ->withInput();
        }
    }

    /**
     * Download naskah yang sudah diupload
     */
    public function downloadNaskah($pesananId)
    {
        try {
            $pesananBuku = PesananKolaborasi::where('user_id', Auth::id())
                ->findOrFail($pesananId);

            if (!$pesananBuku->file_naskah) {
                return redirect()->back()->with('error', 'File naskah tidak tersedia.');
            }

            if (!Storage::disk('public')->exists($pesananBuku->file_naskah)) {
                return redirect()->back()->with('error', 'File naskah tidak ditemukan di server.');
            }

            Log::info('Naskah downloaded by user', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'filename' => $pesananBuku->file_naskah
            ]);

            // Generate nama file untuk download
            $downloadName = $pesananBuku->judul_naskah
                ? $pesananBuku->judul_naskah . '.' . pathinfo($pesananBuku->file_naskah, PATHINFO_EXTENSION)
                : basename($pesananBuku->file_naskah);

            return Storage::disk('public')->download($pesananBuku->file_naskah, $downloadName);

        } catch (\Exception $e) {
            Log::error('Error downloading naskah', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }

    /**
     * Halaman daftar pesanan user
     */
    public function daftarPesanan()
    {
        $pesananList = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('buku-kolaboratif.daftar-pesanan', compact('pesananList'));
    }

    /**
     * Batalkan pesanan (hanya jika status menunggu)
     */
    public function batalkanPesanan($pesananId)
    {
        try {
            $pesananBuku = PesananKolaborasi::with(['babBuku'])
                ->where('user_id', Auth::id())
                ->findOrFail($pesananId);

            // Hanya bisa dibatalkan jika status menunggu
            if ($pesananBuku->status_pembayaran !== 'menunggu') {
                return redirect()->back()
                    ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
            }

            DB::beginTransaction();

            // Update status pesanan
            $pesananBuku->update([
                'status_pembayaran' => 'dibatalkan',
                'tanggal_batal' => now()
            ]);

            // Kembalikan status bab menjadi tersedia
            if ($pesananBuku->babBuku) {
                $pesananBuku->babBuku->update(['status' => 'tersedia']);
            }

            DB::commit();

            Log::info('Pesanan dibatalkan oleh user', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan
            ]);

            return redirect()->route('buku-kolaboratif.daftar-pesanan')
                ->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error cancelling order', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }

      /**
     * Generate nomor pesanan unik
     */
    private function generateNomorPesanan()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $nomor = 'PES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                $nomor = 'PES-' . date('Ymd') . '-' . time();
                break;
            }
        } while (PesananKolaborasi::where('nomor_pesanan', $nomor)->exists());

        return $nomor;
    }

    /**
     * Auto-expire pesanan yang sudah lewat batas waktu
     */
    public function autoExpirePesanan()
    {
        try {
            $expiredOrders = PesananKolaborasi::with(['babBuku'])
                ->where('status_pembayaran', 'menunggu')
                ->where('batas_pembayaran', '<', now())
                ->get();

            DB::beginTransaction();

            foreach ($expiredOrders as $order) {
                // Update status pesanan
                $order->update(['status_pembayaran' => 'expired']);

                // Kembalikan status bab
                if ($order->babBuku) {
                    $order->babBuku->update(['status' => 'tersedia']);
                }

                Log::info('Pesanan auto-expired', [
                    'pesanan_id' => $order->id,
                    'nomor_pesanan' => $order->nomor_pesanan,
                    'user_id' => $order->user_id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'expired_count' => $expiredOrders->count(),
                'message' => $expiredOrders->count() . ' pesanan telah kedaluwarsa'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error auto-expiring orders', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan kedaluwarsa'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan status bab secara real-time
     */
    public function getBabStatus($bukuId)
    {
        try {
            $babStatus = BabBuku::where('buku_kolaboratif_id', $bukuId)
                ->select('id', 'status', 'nomor_bab', 'judul_bab')
                ->orderBy('nomor_bab')
                ->get()
                ->keyBy('id');

            return response()->json([
                'success' => true,
                'data' => $babStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting bab status', [
                'buku_id' => $bukuId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status bab'
            ], 500);
        }
    }

    /**
     * Cek status pembayaran via AJAX
     */
    public function cekStatusPembayaran($pesananId)
    {
        try {
            $pesanan = PesananKolaborasi::where('user_id', Auth::id())
                ->findOrFail($pesananId);

            return response()->json([
                'success' => true,
                'status_pembayaran' => $pesanan->status_pembayaran,
                'status_penulisan' => $pesanan->status_penulisan,
                'status_pembayaran_text' => $pesanan->status_pembayaran_text,
                'status_penulisan_text' => $pesanan->status_penulisan_text,
                'feedback_editor' => $pesanan->feedback_editor,
                'catatan_persetujuan' => $pesanan->catatan_persetujuan,
                'can_upload_naskah' => $pesanan->canUploadNaskah(),
                'has_naskah' => $pesanan->hasNaskah(),
                'is_naskah_direvisi' => $pesanan->isNaskahDirevisi(),
                'is_naskah_disetujui' => $pesanan->isNaskahDisetujui()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status pesanan'
            ], 500);
        }
    }

    /**
     * Update naskah (untuk revisi)
     */
    public function updateNaskah(Request $request, $pesananId)
    {
        // Validasi input
        $validated = $request->validate([
            'judul_naskah' => 'required|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'file_naskah' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_kata' => 'nullable|integer|min:1',
            'catatan_penulis' => 'nullable|string|max:1000'
        ], [
            'judul_naskah.required' => 'Judul naskah harus diisi',
            'judul_naskah.max' => 'Judul naskah maksimal 500 karakter',
            'deskripsi_naskah.max' => 'Deskripsi naskah maksimal 2000 karakter',
            'file_naskah.mimes' => 'Format file harus PDF, DOC, atau DOCX',
            'file_naskah.max' => 'Ukuran file maksimal 10MB',
            'jumlah_kata.integer' => 'Jumlah kata harus berupa angka',
            'jumlah_kata.min' => 'Jumlah kata minimal 1',
            'catatan_penulis.max' => 'Catatan penulis maksimal 1000 karakter'
        ]);

        try {
            $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
                ->where('user_id', Auth::id())
                ->findOrFail($pesananId);

            // Cek apakah bisa update naskah
            if (!in_array($pesananBuku->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi'])) {
                return redirect()->back()
                    ->with('error', 'Status tidak memungkinkan untuk update naskah.');
            }

            DB::beginTransaction();

            // Update data naskah
            $updateData = [
                'judul_naskah' => $validated['judul_naskah'],
                'deskripsi_naskah' => $validated['deskripsi_naskah'],
                'jumlah_kata' => $validated['jumlah_kata'],
                'catatan_penulis' => $validated['catatan_penulis']
            ];

            // Upload file baru jika ada
            if ($request->hasFile('file_naskah')) {
                // Hapus file lama jika ada
                if ($pesananBuku->file_naskah && Storage::disk('public')->exists($pesananBuku->file_naskah)) {
                    Storage::disk('public')->delete($pesananBuku->file_naskah);
                }

                // Upload file baru
                $file = $request->file('file_naskah');
                $filename = time() . '_' . str_replace(' ', '_', $validated['judul_naskah']) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('naskah', $filename, 'public');
                
                $updateData['file_naskah'] = $filePath;
                $updateData['tanggal_upload_naskah'] = now();
            }

            // Update status penulisan
            if ($pesananBuku->status_penulisan === 'revisi' || $request->hasFile('file_naskah')) {
                $updateData['status_penulisan'] = 'sudah_kirim';
                $updateData['feedback_editor'] = null; // Clear feedback lama
                $updateData['tanggal_feedback'] = null;
            }

            $pesananBuku->update($updateData);

            DB::commit();

            Log::info('Naskah updated', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'nomor_pesanan' => $pesananBuku->nomor_pesanan,
                'judul_naskah' => $validated['judul_naskah']
            ]);

            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('success', 'Naskah berhasil diupdate! Menunggu review dari admin.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating naskah', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate naskah.')
                ->withInput();
        }
    }

    /**
     * Halaman edit naskah
     */
    public function editNaskah($pesananId)
    {
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        // Cek apakah bisa edit naskah
        if (!in_array($pesananBuku->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi'])) {
            return redirect()->route('buku-kolaboratif.status-pesanan', $pesananId)
                ->with('error', 'Status tidak memungkinkan untuk edit naskah.');
        }

        return view('buku-kolaboratif.edit-naskah', compact('pesananBuku'));
    }

    /**
     * Simpan draft naskah (tanpa mengubah status)
     */
    public function simpanDraft(Request $request, $pesananId)
    {
        // Validasi input
        $validated = $request->validate([
            'judul_naskah' => 'nullable|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'jumlah_kata' => 'nullable|integer|min:0',
            'catatan_penulis' => 'nullable|string|max:1000'
        ]);

        try {
            $pesananBuku = PesananKolaborasi::where('user_id', Auth::id())
                ->findOrFail($pesananId);

            // Cek apakah bisa simpan draft
            if (!in_array($pesananBuku->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak memungkinkan untuk simpan draft.'
                ], 400);
            }

            // Update data tanpa mengubah status
            $updateData = array_filter($validated); // Remove null values
            
            // Set status menjadi sedang_proses jika masih dapat_mulai
            if ($pesananBuku->status_penulisan === 'dapat_mulai' && !empty($updateData)) {
                $updateData['status_penulisan'] = 'sedang_proses';
            }

            $pesananBuku->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Draft berhasil disimpan.',
                'data' => [
                    'status_penulisan' => $pesananBuku->fresh()->status_penulisan,
                    'updated_at' => $pesananBuku->fresh()->updated_at->format('d M Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving draft', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan draft.'
            ], 500);
        }
    }
}
