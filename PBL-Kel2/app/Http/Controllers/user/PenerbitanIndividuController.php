<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanIndividu;
use App\Models\PenerbitanIndividu;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenerbitanIndividuController extends Controller
{
    public function index()
    {
        $paketOptions = [
            'silver' => [
                'harga' => 1500000,
                'gambar' => 'img/paket-silver.jpg',
            ],
            'gold' => [
                'harga' => 3500000,
                'gambar' => 'img/paket-gold.jpg',
                'fitur' => [
                    'Editing dan Proofreading Premium',
                    'Design Cover Premium',
                    'Layout Buku Premium',
                    'ISBN',
                    '100 Eksemplar Cetak',
                    'Distribusi Online & Offline',
                    'Marketing Support',
                    'Author Photo Session'
                ]
            ],
            'diamond' => [
                'harga' => 5000000,
                'gambar' => 'img/paket-diamond.jpg',
                'fitur' => [
                    'Editing dan Proofreading Premium',
                    'Design Cover Eksklusif',
                    'Layout Buku Eksklusif',
                    'ISBN',
                    '200 Eksemplar Cetak',
                    'Distribusi Nasional',
                    'Marketing Campaign',
                    'Author Photo Session',
                    'Book Launch Event',
                    'Media Relations'
                ]
            ]
        ];

        $riwayatPenerbitan = PenerbitanIndividu::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('penerbitan-individu.index', compact('paketOptions', 'riwayatPenerbitan'));
    }

    public function pilihPaket(Request $request)
    {
        $request->validate([
            'paket' => 'required|in:silver,gold,diamond'
        ]);

        $paketOptions = PenerbitanIndividu::getPaketOptions();
        $selectedPaket = $paketOptions[$request->paket];

        return view('penerbitan-individu.pilih-paket', [
            'paket' => $request->paket,
            'paketData' => $selectedPaket
        ]);
    }

    /**
     * Generate nomor pesanan unik
     */
    private function generateNomorPesanan()
    {
        do {
            // Format: PI-YYYYMMDD-XXXX
            $nomor = 'PI-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PenerbitanIndividu::where('nomor_pesanan', $nomor)->exists());
        
        return $nomor;
    }

    public function prosesPesanan(Request $request)
    {
        $request->validate([
            'paket' => 'required|in:silver,gold,diamond',
            'setuju' => 'required|accepted'
        ], [
            'setuju.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'setuju.accepted' => 'Anda harus menyetujui syarat dan ketentuan'
        ]);

        try {
            DB::beginTransaction();

            $paketOptions = PenerbitanIndividu::getPaketOptions();
            $selectedPaket = $paketOptions[$request->paket];

            $penerbitan = PenerbitanIndividu::create([
                'user_id' => Auth::id(),
                'nomor_pesanan' => $this->generateNomorPesanan(),
                'paket' => $request->paket,
                'harga_paket' => $selectedPaket['harga'],
                'status_pembayaran' => 'menunggu',
                'status_penerbitan' => 'belum_mulai',
                'tanggal_pesanan' => now()
            ]);

            DB::commit();

            Log::info('Pesanan penerbitan individu berhasil dibuat', [
                'penerbitan_id' => $penerbitan->id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('penerbitan-individu.pembayaran', $penerbitan->id)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error dalam proses pesanan penerbitan individu', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pesanan.')
                ->withInput();
        }
    }

    public function pembayaran($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
                ->findOrFail($id);

            if (!in_array($penerbitan->status_pembayaran, ['menunggu', 'pending', 'ditolak'])) {
                return redirect()->route('penerbitan-individu.status', $id)
                    ->with('info', 'Pembayaran sudah diproses sebelumnya.');
            }

            // Ambil data rekening dari database
            $rekenings = Rekening::orderBy('bank', 'asc')->get();

            Log::info('Halaman pembayaran diakses', [
                'penerbitan_id' => $id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'user_id' => Auth::id(),
                'jumlah_rekening' => $rekenings->count()
            ]);

            return view('penerbitan-individu.pembayaran', compact('penerbitan', 'rekenings'));

        } catch (\Exception $e) {
            Log::error('Error mengakses halaman pembayaran', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('penerbitan-individu.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman pembayaran.');
        }
    }

    public function prosesPembayaran(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer_bank',
            'bank_pengirim' => 'required|string|max:50',
            'bank_tujuan' => 'required|string|max:100',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan_pembayaran' => 'nullable|string|max:500'
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
            'bank_pengirim.required' => 'Bank pengirim harus dipilih',
            'bank_tujuan.required' => 'Bank tujuan harus dipilih'
        ]);

        $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
            ->findOrFail($id);

        // Cek apakah pembayaran masih bisa diproses
        if (!in_array($penerbitan->status_pembayaran, ['menunggu', 'pending', 'ditolak'])) {
            return redirect()->back()
                ->with('error', 'Pembayaran sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiPembayaranPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');

                // Generate nama file unik
                $fileName = 'bukti_pembayaran_' . $penerbitan->nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Simpan file ke storage/app/public/bukti_pembayaran
                $buktiPembayaranPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');

                Log::info('File uploaded successfully', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_path' => $buktiPembayaranPath,
                    'file_size' => $file->getSize()
                ]);
            }

            // Update data pembayaran
            $updateData = [
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'bank_pengirim' => $request->bank_pengirim,
                'bank_tujuan' => $request->bank_tujuan,
                'bukti_pembayaran' => $buktiPembayaranPath,
                'catatan_pembayaran' => $request->catatan_pembayaran,
                'tanggal_bayar' => now()
            ];

            $penerbitan->update($updateData);

            // Buat laporan penjualan
            $this->createLaporanPenjualan($penerbitan);

            DB::commit();

            Log::info('Bukti pembayaran berhasil diupload', [
                'penerbitan_id' => $penerbitan->id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'file_path' => $buktiPembayaranPath,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('penerbitan-individu.status', $penerbitan->id)
                ->with('success', 'Bukti pembayaran berhasil diupload. Pembayaran akan diverifikasi dalam 1x24 jam.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if ($buktiPembayaranPath && Storage::disk('public')->exists($buktiPembayaranPath)) {
                Storage::disk('public')->delete($buktiPembayaranPath);
            }

            Log::error('Error dalam proses upload bukti pembayaran', [
                'penerbitan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Batalkan pesanan
     */
    public function batalkanPesanan(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:500'
        ], [
            'alasan_pembatalan.max' => 'Alasan pembatalan maksimal 500 karakter'
        ]);

        try {
            DB::beginTransaction();

            $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
                ->findOrFail($id);

            // Cek apakah pesanan bisa dibatalkan
            if (in_array($penerbitan->status_pembayaran, ['lunas', 'dibatalkan'])) {
                return redirect()->back()
                    ->with('error', 'Pesanan ini tidak dapat dibatalkan karena sudah lunas atau sudah dibatalkan sebelumnya.');
            }

            // Cek apakah sudah dalam proses penerbitan
            if (in_array($penerbitan->status_penerbitan, ['dalam_proses', 'selesai'])) {
                return redirect()->back()
                    ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dalam proses penerbitan.');
            }

            // Hapus bukti pembayaran jika ada
            if ($penerbitan->bukti_pembayaran && Storage::disk('public')->exists($penerbitan->bukti_pembayaran)) {
                Storage::disk('public')->delete($penerbitan->bukti_pembayaran);
                Log::info('Bukti pembayaran dihapus', [
                    'file_path' => $penerbitan->bukti_pembayaran,
                    'penerbitan_id' => $penerbitan->id
                ]);
            }

            // Hapus file naskah jika ada
            if ($penerbitan->file_naskah && Storage::disk('public')->exists($penerbitan->file_naskah)) {
                Storage::disk('public')->delete($penerbitan->file_naskah);
                Log::info('File naskah dihapus', [
                    'file_path' => $penerbitan->file_naskah,
                    'penerbitan_id' => $penerbitan->id
                ]);
            }

            // Update status menjadi dibatalkan
            $penerbitan->update([
                'status_pembayaran' => 'dibatalkan',
                'status_penerbitan' => 'dibatalkan',
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'tanggal_dibatalkan' => now()
            ]);

            // Update laporan penjualan jika ada
            $laporan = LaporanPenjualanIndividu::where('penerbitan_individu_id', $penerbitan->id)->first();
            if ($laporan) {
                $laporan->update([
                    'status_pembayaran' => 'dibatalkan'
                ]);
            }

            DB::commit();

            Log::info('Pesanan penerbitan individu berhasil dibatalkan', [
                'penerbitan_id' => $penerbitan->id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'user_id' => Auth::id(),
                'alasan_pembatalan' => $request->alasan_pembatalan
            ]);

            return redirect()->route('penerbitan-individu.index')
                              ->with('success', 'Pesanan berhasil dibatalkan. Jika sudah melakukan pembayaran, silakan hubungi admin untuk pengembalian dana.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error membatalkan pesanan penerbitan individu', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
        }
    }

    private function createLaporanPenjualan($penerbitan)
    {
        try {
            // Cek apakah sudah ada laporan untuk penerbitan ini
            $existingLaporan = LaporanPenjualanIndividu::where('penerbitan_individu_id', $penerbitan->id)->first();
            
            if ($existingLaporan) {
                Log::info('Laporan penjualan already exists', [
                    'penerbitan_id' => $penerbitan->id,
                    'existing_laporan_id' => $existingLaporan->id
                ]);
                return $existingLaporan;
            }

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($penerbitan);

            // Create laporan penjualan
            $laporan = LaporanPenjualanIndividu::create([
                'penerbitan_individu_id' => $penerbitan->id,
                'judul' => $penerbitan->judul_buku ?? 'Belum ada judul',
                'penulis' => $penerbitan->nama_penulis ?? $penerbitan->user->name,
                'paket' => $penerbitan->paket,
                'bukti_pembayaran' => $penerbitan->bukti_pembayaran,
                'status_pembayaran' => 'menunggu_verifikasi',
                'tanggal' => now()->toDateString(),
                'invoice' => $invoiceNumber,
            ]);

            Log::info('Laporan penjualan created successfully', [
                'penerbitan_id' => $penerbitan->id,
                'laporan_id' => $laporan->id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'invoice' => $invoiceNumber
            ]);

            return $laporan;

        } catch (\Exception $e) {
            Log::error('Error creating laporan penjualan', [
                'penerbitan_id' => $penerbitan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't throw error, let the payment process continue
            return null;
        }
    }

    private function generateInvoiceNumber($penerbitan)
    {
        // Ambil 4 digit terakhir dari nomor pesanan
        $pesananNumber = substr(str_replace(['PI-', '-'], '', $penerbitan->nomor_pesanan), -4);

        // Format: INV-YYYYMMDD-XXXX
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . $pesananNumber;

        // Pastikan unique
        $counter = 1;
        $originalInvoice = $invoiceNumber;

        while (LaporanPenjualanIndividu::where('invoice', $invoiceNumber)->exists()) {
            $invoiceNumber = $originalInvoice . '-' . $counter;
            $counter++;
        }

        return $invoiceNumber;
    }

    public function status($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
                ->findOrFail($id);

            return view('penerbitan-individu.status', compact('penerbitan'));

        } catch (\Exception $e) {
            Log::error('Error mengakses halaman status', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('penerbitan-individu.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman status.');
        }
    }

    public function formPengajuan($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
                ->findOrFail($id);

            // Cek apakah pembayaran sudah lunas
            if ($penerbitan->status_pembayaran !== 'lunas') {
                return redirect()->route('penerbitan-individu.status', $id)
                    ->with('error', 'Pembayaran belum terverifikasi. Tidak dapat mengisi form pengajuan.');
            }

            // Cek apakah sudah pernah mengisi form
            if (!in_array($penerbitan->status_penerbitan, ['dapat_mulai', 'revisi'])) {
                return redirect()->route('penerbitan-individu.status', $id)
                    ->with('info', 'Form pengajuan sudah diisi sebelumnya.');
            }

            return view('penerbitan-individu.form-pengajuan', compact('penerbitan'));

        } catch (\Exception $e) {
            Log::error('Error mengakses form pengajuan', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('penerbitan-individu.index')
                ->with('error', 'Terjadi kesalahan saat memuat form pengajuan.');
        }
    }

    public function submitPengajuan(Request $request, $id)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string|max:1000',
            'file_naskah' => 'required|file|mimes:doc,docx,pdf|max:10240'
        ], [
            'judul_buku.required' => 'Judul buku harus diisi',
            'nama_penulis.required' => 'Nama penulis harus diisi',
            'deskripsi_singkat.required' => 'Deskripsi singkat harus diisi',
            'file_naskah.required' => 'File naskah harus diupload',
            'file_naskah.mimes' => 'Format file harus DOC, DOCX, atau PDF',
            'file_naskah.max' => 'Ukuran file maksimal 10MB'
        ]);

        $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
            ->findOrFail($id);

        // Cek apakah pembayaran sudah lunas
        if ($penerbitan->status_pembayaran !== 'lunas') {
            return redirect()->back()
                ->with('error', 'Pembayaran belum terverifikasi.');
        }

        try {
            DB::beginTransaction();

            // Hapus file lama jika ada (untuk revisi)
            if ($penerbitan->file_naskah && Storage::disk('public')->exists($penerbitan->file_naskah)) {
                Storage::disk('public')->delete($penerbitan->file_naskah);
            }

            // Upload file naskah
            $file = $request->file('file_naskah');
            $fileName = 'naskah_individu_' . $penerbitan->nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('naskah_individu', $fileName, 'public');

            // Update data penerbitan
            $penerbitan->update([
                'judul_buku' => $request->judul_buku,
                'nama_penulis' => $request->nama_penulis,
                'deskripsi_singkat' => $request->deskripsi_singkat,
                'file_naskah' => $filePath,
                'tanggal_upload_naskah' => now(),
                'status_penerbitan' => 'sudah_kirim'
            ]);

            // Update laporan penjualan jika ada
            $laporan = LaporanPenjualanIndividu::where('penerbitan_individu_id', $penerbitan->id)->first();
            if ($laporan) {
                $laporan->update([
                    'judul' => $request->judul_buku,
                    'penulis' => $request->nama_penulis,
                ]);
            }

            DB::commit();

            Log::info('Form pengajuan penerbitan individu berhasil disubmit', [
                'penerbitan_id' => $penerbitan->id,
                'nomor_pesanan' => $penerbitan->nomor_pesanan,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('penerbitan-individu.status', $penerbitan->id)
                ->with('success', 'Form pengajuan berhasil disubmit. Editor akan melakukan review dalam 3-5 hari kerja.');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Error submit form pengajuan penerbitan individu', [
                'penerbitan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat submit form pengajuan.')
                ->withInput();
        }
    }

    public function downloadNaskah($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::where('user_id', Auth::id())
                ->findOrFail($id);

            if (!$penerbitan->file_naskah) {
                return redirect()->back()
                    ->with('error', 'File naskah tidak tersedia.');
            }

            if (!Storage::disk('public')->exists($penerbitan->file_naskah)) {
                return redirect()->back()
                    ->with('error', 'File naskah tidak ditemukan di server.');
            }

            Log::info('Naskah individu downloaded by user', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'filename' => $penerbitan->file_naskah,
                'nomor_pesanan' => $penerbitan->nomor_pesanan
            ]);

            $downloadName = $penerbitan->judul_buku
                ? $penerbitan->judul_buku . '.' . pathinfo($penerbitan->file_naskah, PATHINFO_EXTENSION)
                : basename($penerbitan->file_naskah);

            return Storage::disk('public')->download($penerbitan->file_naskah, $downloadName);

        } catch (\Exception $e) {
            Log::error('Error downloading naskah individu by user', [
                'penerbitan_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }
}
