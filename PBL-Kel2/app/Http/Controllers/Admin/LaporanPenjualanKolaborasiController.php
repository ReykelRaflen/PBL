<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\LaporanPenjualanKolaborasi;
use App\Models\PesananKolaborasi;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanKolaborasiController extends Controller
{
    // Status constants for consistency
    const STATUS_MENUNGGU = 'menunggu_verifikasi';
    const STATUS_SUKSES = 'sukses';
    const STATUS_TIDAK_SESUAI = 'tidak sesuai';

    public function index(Request $request)
    {
        $query = LaporanPenjualanKolaborasi::with(['pesananKolaborasi.bukuKolaboratif', 'pesananKolaborasi.babBuku', 'pesananKolaborasi.user'])
            ->latest();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('nomor_invoice', 'like', "%{$search}%");
            });
        }

        $laporan = $query->paginate(15);

        // Statistik untuk dashboard
        $statistik = [
            'total' => LaporanPenjualanKolaborasi::count(),
            'menunggu' => LaporanPenjualanKolaborasi::where('status_pembayaran', self::STATUS_MENUNGGU)->count(),
            'sukses' => LaporanPenjualanKolaborasi::where('status_pembayaran', self::STATUS_SUKSES)->count(),
            'ditolak' => LaporanPenjualanKolaborasi::where('status_pembayaran', self::STATUS_TIDAK_SESUAI)->count(),
            'total_nilai' => LaporanPenjualanKolaborasi::where('status_pembayaran', self::STATUS_SUKSES)->sum('jumlah_pembayaran')
        ];

        return view('admin.penjualanKolaborasi.index', compact('laporan', 'statistik'));
    }

    public function create()
    {
        // Ambil pesanan yang sudah lunas tapi belum ada laporannya
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->whereDoesntHave('laporanPenjualan')
            ->get();

        return view('admin.penjualanKolaborasi.create', compact('pesananBuku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:1000',
            'penulis' => 'required|string|max:255',
            'bab' => 'required|string|max:255',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_pembayaran' => 'required|in:sukses,tidak sesuai,menunggu_verifikasi',
            'pesanan_kolaborasi_id' => 'nullable|exists:pesanan_kolaborasi,id',
        ]);

        try {
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->storeAs('public/bukti', $filename);
                $validated['bukti_pembayaran'] = $filename;
            }

            $validated['tanggal'] = now()->toDateString();
            $validated['nomor_invoice'] = $this->generateInvoiceNumber();
            $validated['admin_id'] = auth()->id();

            LaporanPenjualanKolaborasi::create($validated);

            Log::info('Laporan penjualan kolaborasi created', [
                'admin_id' => auth()->id(),
                'invoice' => $validated['nomor_invoice'],
                'judul' => $validated['judul']
            ]);

            return redirect()->route('penjualanKolaborasi.index')
                ->with('success', 'Laporan berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error creating laporan penjualan kolaborasi', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan.');
        }
    }

    public function show($id)
    {
        $laporan = LaporanPenjualanKolaborasi::with(['pesananKolaborasi.bukuKolaboratif', 'pesananKolaborasi.babBuku', 'pesananKolaborasi.user'])
            ->findOrFail($id);

        return view('admin.penjualanKolaborasi.show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

        // Ambil semua pesanan untuk dropdown
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->get();

        return view('admin.penjualanKolaborasi.edit', compact('laporan', 'pesananBuku'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:1000',
        'penulis' => 'required|string|max:255',
        'bab' => 'required|string|max:255',
        'jumlah_pembayaran' => 'required|numeric|min:0',
        'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status_pembayaran' => 'required|in:sukses,tidak_sesuai,menunggu_verifikasi',
        'pesanan_kolaborasi_id' => 'nullable|exists:pesanan_kolaborasi,id',
        'catatan_admin' => 'nullable|string|max:1000',
    ]);

    try {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

        // PERBAIKAN: Jangan update pesanan_kolaborasi_id jika kosong
        $updateData = [
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'bab' => $validated['bab'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'status_pembayaran' => $validated['status_pembayaran'],
            'catatan_admin' => $validated['catatan_admin'],
        ];

        // Hanya tambahkan pesanan_kolaborasi_id jika tidak kosong
        if (!empty($validated['pesanan_kolaborasi_id'])) {
            $updateData['pesanan_kolaborasi_id'] = $validated['pesanan_kolaborasi_id'];
        }

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/' . $laporan->bukti_pembayaran)) {
                Storage::delete('public/bukti/' . $laporan->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/bukti', $filename);
            $updateData['bukti_pembayaran'] = $filename;
        }

        Log::info('Update data prepared', [
            'laporan_id' => $id,
            'update_data' => $updateData,
            'admin_id' => auth()->id()
        ]);

        $updateResult = $laporan->update($updateData);
        
        Log::info('Update executed successfully', [
            'laporan_id' => $id,
            'update_result' => $updateResult,
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil diperbarui.');

    } catch (\Exception $e) {
        Log::error('Error updating laporan penjualan kolaborasi', [
            'laporan_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'admin_id' => auth()->id()
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat memperbarui laporan: ' . $e->getMessage());
    }
}



    public function destroy($id)
    {
        try {
            $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

            // Delete file if exists
            if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/' . $laporan->bukti_pembayaran)) {
                Storage::delete('public/bukti/' . $laporan->bukti_pembayaran);
            }

            $invoice = $laporan->nomor_invoice;
            $laporan->delete();

            Log::info('Laporan penjualan kolaborasi deleted', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $invoice
            ]);

            return redirect()->route('penjualanKolaborasi.index')
                ->with('success', 'Laporan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting laporan penjualan kolaborasi', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }

    /**
     * Accept/Approve Payment
     */
    public function acceptPayment(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $laporan = LaporanPenjualanKolaborasi::with(['pesananKolaborasi.babBuku'])
                ->findOrFail($id);

            // Cek apakah sudah diverifikasi sebelumnya
            if ($laporan->status_pembayaran !== self::STATUS_MENUNGGU) {
                return redirect()->back()
                    ->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
            }

            Log::info('Accept payment started', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $laporan->nomor_invoice
            ]);

            // Update status laporan
            $laporan->update([
                'status_pembayaran' => self::STATUS_SUKSES,
                'admin_id' => auth()->id(),
                'tanggal_verifikasi' => now(),
                'catatan_admin' => 'Pembayaran disetujui oleh admin'
            ]);

            // Update status pesanan jika ada relasi
            if ($laporan->pesananKolaborasi) {
                $laporan->pesananKolaborasi->update([
                    'status_pembayaran' => 'lunas',
                    'status_penulisan' => 'dapat_mulai',
                    'admin_id' => auth()->id(),
                    'tanggal_verifikasi' => now(),
                    'hasil_verifikasi' => 'disetujui',
                    'catatan_admin' => 'Pembayaran disetujui oleh admin'
                ]);

                Log::info('Payment accepted successfully', [
                    'pesanan_id' => $laporan->pesananKolaborasi->id,
                    'nomor_pesanan' => $laporan->pesananKolaborasi->nomor_pesanan,
                    'penulis' => $laporan->penulis
                ]);
            }

            DB::commit();

            return redirect()->route('penjualanKolaborasi.index')
                ->with('success', 'Pembayaran berhasil disetujui! Penulis dapat mulai menulis.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error accepting payment', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject Payment
     */
    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|min:10|max:500'
        ], [
            'catatan_admin.required' => 'Alasan penolakan harus diisi',
            'catatan_admin.min' => 'Alasan penolakan minimal 10 karakter',
            'catatan_admin.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        try {
            DB::beginTransaction();

            $laporan = LaporanPenjualanKolaborasi::with(['pesananKolaborasi.babBuku'])
                ->findOrFail($id);

            // Cek apakah sudah diverifikasi sebelumnya
            if ($laporan->status_pembayaran !== 'menunggu_verifikasi') {
                return redirect()->back()
                    ->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
            }

            Log::info('Reject payment started', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $laporan->nomor_invoice,
                'reason' => $request->catatan_admin
            ]);

            // Update status laporan - PERBAIKAN: gunakan 'tidak_sesuai' dengan underscore
            $laporan->update([
                'status_pembayaran' => 'tidak_sesuai', // Ubah dari 'tidak sesuai' ke 'tidak_sesuai'
                'catatan_admin' => $request->catatan_admin,
                'admin_id' => auth()->id(),
                'tanggal_verifikasi' => now()
            ]);

            // Update status pesanan jika ada relasi
            if ($laporan->pesananKolaborasi) {
                $laporan->pesananKolaborasi->update([
                    'status_pembayaran' => 'tidak_sesuai', // Ubah dari 'tidak sesuai' ke 'tidak_sesuai'
                    'admin_id' => auth()->id(),
                    'tanggal_verifikasi' => now(),
                    'hasil_verifikasi' => 'ditolak',
                    'catatan_admin' => $request->catatan_admin
                ]);

                // Kembalikan status bab menjadi tersedia
                if ($laporan->pesananKolaborasi->babBuku) {
                    $laporan->pesananKolaborasi->babBuku->update(['status' => 'tersedia']);

                    Log::info('Chapter status returned to available', [
                        'bab_id' => $laporan->pesananKolaborasi->babBuku->id,
                        'bab_judul' => $laporan->pesananKolaborasi->babBuku->judul_bab ?? 'N/A'
                    ]);
                }

                Log::info('Payment rejected successfully', [
                    'pesanan_id' => $laporan->pesananKolaborasi->id,
                    'nomor_pesanan' => $laporan->pesananKolaborasi->nomor_pesanan,
                    'reason' => $request->catatan_admin
                ]);
            }

            DB::commit();

            return redirect()->route('penjualanKolaborasi.index')
                ->with('success', 'Pembayaran ditolak. Bab telah dikembalikan ke status tersedia.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error rejecting payment', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Keep existing verifikasiPembayaran method for backward compatibility
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        if ($request->status === 'sukses') {
            return $this->acceptPayment($request, $id);
        } else {
            return $this->rejectPayment($request, $id);
        }
    }

    /**
     * Download payment proof file
     */
    public function downloadBukti($id)
    {
        try {
            $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

            if (!$laporan->bukti_pembayaran || !Storage::exists('public/bukti/' . $laporan->bukti_pembayaran)) {
                return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
            }

            Log::info('Bukti pembayaran downloaded', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'filename' => $laporan->bukti_pembayaran
            ]);

            return Storage::download('public/bukti/' . $laporan->bukti_pembayaran);

        } catch (\Exception $e) {
            Log::error('Error downloading bukti pembayaran', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $invoice = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                // Fallback with timestamp to ensure uniqueness
                $invoice = 'INV-' . date('Ymd') . '-' . time();
                break;
            }
        } while (LaporanPenjualanKolaborasi::where('nomor_invoice', $invoice)->exists());

        return $invoice;
    }
}
