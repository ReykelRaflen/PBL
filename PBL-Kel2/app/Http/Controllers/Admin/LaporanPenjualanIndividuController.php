<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanIndividu;
use App\Models\PenerbitanIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LaporanPenjualanIndividuController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanPenjualanIndividu::with('penerbitanIndividu.user')->latest('tanggal');

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter berdasarkan paket
        if ($request->filled('paket')) {
            $query->where('paket', $request->paket);
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
                $q->where('invoice', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $laporans = $query->paginate(15);

        // Statistik
        $statistik = [
            'total' => LaporanPenjualanIndividu::count(),
            'menunggu_verifikasi' => LaporanPenjualanIndividu::where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'sukses' => LaporanPenjualanIndividu::where('status_pembayaran', 'sukses')->count(),
            'tidak_sesuai' => LaporanPenjualanIndividu::where('status_pembayaran', 'tidak_sesuai')->count(),
            'silver' => LaporanPenjualanIndividu::where('paket', 'silver')->count(),
            'gold' => LaporanPenjualanIndividu::where('paket', 'gold')->count(),
            'diamond' => LaporanPenjualanIndividu::where('paket', 'diamond')->count(),
        ];

        return view('admin.laporan-penjualan-individu.index', compact('laporans', 'statistik'));
    }

    public function show($id)
    {
        $laporan = LaporanPenjualanIndividu::with('penerbitanIndividu.user', 'penerbitanIndividu.admin')->findOrFail($id);
        $penerbitan = $laporan->penerbitanIndividu;
        
        return view('admin.laporan-penjualan-individu.show', compact('laporan', 'penerbitan'));
    }

    public function setujui(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $laporan = LaporanPenjualanIndividu::findOrFail($id);
            
            // Update status laporan
            $laporan->update([
                'status_pembayaran' => 'sukses',
            ]);

            // Update status penerbitan terkait
            $penerbitan = $laporan->penerbitanIndividu;
            
            if ($penerbitan) {
                $penerbitan->update([
                    'status_pembayaran' => 'lunas',
                    'status_penerbitan' => 'dapat_mulai',
                    'admin_id' => auth()->id(),
                    'tanggal_verifikasi' => now(),
                    'catatan_admin' => $request->catatan_admin
                ]);
            }

            DB::commit();

            Log::info('Pembayaran laporan penjualan individu disetujui', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $laporan->invoice
            ]);

            return redirect()->route('admin.laporan-penjualan-individu.index')
                ->with('success', 'Pembayaran berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error menyetujui pembayaran laporan penjualan individu', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui pembayaran.');
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ], [
            'catatan_admin.required' => 'Alasan penolakan harus diisi'
        ]);

        try {
            DB::beginTransaction();

            $laporan = LaporanPenjualanIndividu::findOrFail($id);
            
            // Update status laporan
            $laporan->update([
                'status_pembayaran' => 'tidak_sesuai',
            ]);

            // Update status penerbitan terkait
            $penerbitan = $laporan->penerbitanIndividu;
            
            if ($penerbitan) {
                $penerbitan->update([
                    'status_pembayaran' => 'ditolak',
                    'admin_id' => auth()->id(),
                    'tanggal_verifikasi' => now(),
                    'catatan_admin' => $request->catatan_admin
                ]);
            }

            DB::commit();

            Log::info('Pembayaran laporan penjualan individu ditolak', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $laporan->invoice,
                'alasan' => $request->catatan_admin
            ]);

            return redirect()->route('admin.laporan-penjualan-individu.index')
                ->with('success', 'Pembayaran berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error menolak pembayaran laporan penjualan individu', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak pembayaran.');
        }
    }

    public function create()
    {
        // Ambil data penerbitan yang belum ada laporannya dan status pembayaran pending
        $penerbitanList = PenerbitanIndividu::with('user')
            ->whereIn('status_pembayaran', ['pending', 'menunggu'])
            ->whereDoesntHave('laporanPenjualan')
            ->latest()
            ->get();

        return view('admin.laporan-penjualan-individu.create', compact('penerbitanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penerbitan_individu_id' => 'nullable|exists:penerbitan_individu,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'paket' => 'required|in:silver,gold,diamond',
            'bukti_pembayaran' => 'nullable|string|max:255',
            'status_pembayaran' => 'required|in:menunggu_verifikasi,sukses,tidak_sesuai',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Generate invoice
            $invoice = $this->generateInvoiceNumber($request->penerbitan_individu_id);

            // Ambil harga dari penerbitan jika ada
            $harga = null;
            if ($request->penerbitan_individu_id) {
                $penerbitan = PenerbitanIndividu::find($request->penerbitan_individu_id);
                $harga = $penerbitan ? $penerbitan->harga_paket : null;
            }

            $laporan = LaporanPenjualanIndividu::create([
                'penerbitan_individu_id' => $request->penerbitan_individu_id,
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'paket' => $request->paket,
                'bukti_pembayaran' => $request->bukti_pembayaran,
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal' => $request->tanggal,
                'invoice' => $invoice,
                'harga' => $harga,
            ]);

            DB::commit();

            Log::info('Laporan penjualan individu created', [
                'admin_id' => auth()->id(),
                'invoice' => $invoice,
                'laporan_id' => $laporan->id
            ]);

            return redirect()->route('admin.laporan-penjualan-individu.index')
                ->with('success', 'Laporan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating laporan penjualan individu', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan.');
        }
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);
        
        // Ambil data penerbitan untuk dropdown
        $penerbitanList = PenerbitanIndividu::with('user')
            ->where(function($query) use ($laporan) {
                $query->whereIn('status_pembayaran', ['pending', 'menunggu'])
                      ->whereDoesntHave('laporanPenjualan')
                      ->orWhere('id', $laporan->penerbitan_individu_id);
            })
            ->latest()
            ->get();

        return view('admin.laporan-penjualan-individu.edit', compact('laporan', 'penerbitanList'));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanPenjualanIndividu::findOrFail($id);

        $request->validate([
            'penerbitan_individu_id' => 'nullable|exists:penerbitan_individu,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'paket' => 'required|in:silver,gold,diamond',
            'bukti_pembayaran' => 'nullable|string|max:255',
                        'status_pembayaran' => 'required|in:menunggu_verifikasi,sukses,tidak_sesuai',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Ambil harga dari penerbitan jika ada
            $harga = $laporan->harga; // Keep existing price
            if ($request->penerbitan_individu_id && $request->penerbitan_individu_id != $laporan->penerbitan_individu_id) {
                $penerbitan = PenerbitanIndividu::find($request->penerbitan_individu_id);
                $harga = $penerbitan ? $penerbitan->harga_paket : $harga;
            }

            $laporan->update([
                'penerbitan_individu_id' => $request->penerbitan_individu_id,
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'paket' => $request->paket,
                'bukti_pembayaran' => $request->bukti_pembayaran,
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal' => $request->tanggal,
                'harga' => $harga,
            ]);

            DB::commit();

            Log::info('Laporan penjualan individu updated', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $laporan->invoice
            ]);

            return redirect()->route('admin.laporan-penjualan-individu.index')
                ->with('success', 'Laporan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating laporan penjualan individu', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui laporan.');
        }
    }

    public function destroy($id)
    {
        try {
            $laporan = LaporanPenjualanIndividu::findOrFail($id);
            $invoice = $laporan->invoice;
            
            $laporan->delete();

            Log::info('Laporan penjualan individu deleted', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'invoice' => $invoice
            ]);

            return redirect()->route('admin.laporan-penjualan-individu.index')
                ->with('success', 'Laporan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting laporan penjualan individu', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }

    public function download($id)
    {
        try {
            $laporan = LaporanPenjualanIndividu::findOrFail($id);

            if (!$laporan->bukti_pembayaran) {
                return redirect()->back()->with('error', 'Bukti pembayaran tidak tersedia.');
            }

            if (!Storage::disk('public')->exists($laporan->bukti_pembayaran)) {
                return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan di server.');
            }

            Log::info('Bukti pembayaran downloaded by admin', [
                'laporan_id' => $id,
                'admin_id' => auth()->id(),
                'filename' => $laporan->bukti_pembayaran,
                'invoice' => $laporan->invoice
            ]);

            $downloadName = 'bukti_pembayaran_' . $laporan->invoice . '.' . pathinfo($laporan->bukti_pembayaran, PATHINFO_EXTENSION);

            return Storage::disk('public')->download($laporan->bukti_pembayaran, $downloadName);

        } catch (\Exception $e) {
            Log::error('Error downloading bukti pembayaran', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    private function generateInvoiceNumber($penerbitanId = null)
    {
        if ($penerbitanId) {
            $penerbitan = PenerbitanIndividu::find($penerbitanId);
            if ($penerbitan) {
                // Ambil 4 digit terakhir dari nomor pesanan
                $pesananNumber = substr(str_replace(['PI-', '-'], '', $penerbitan->nomor_pesanan), -4);
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . $pesananNumber;
            } else {
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        } else {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        // Pastikan unique
        $counter = 1;
        $originalInvoice = $invoiceNumber;

        while (LaporanPenjualanIndividu::where('invoice', $invoiceNumber)->exists()) {
            $invoiceNumber = $originalInvoice . '-' . $counter;
            $counter++;
        }

        return $invoiceNumber;
    }

    // Method untuk backward compatibility
    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sukses,tidak_sesuai',
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        if ($request->status === 'sukses') {
            return $this->setujui($request, $id);
        } else {
            return $this->tolak($request, $id);
        }
    }
}
