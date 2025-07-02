<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualanKolaborasi;
use App\Models\PesananBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranKolaborasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = LaporanPenjualanKolaborasi::with(['pesananBuku.pengguna', 'pesananBuku.bukuKolaboratif', 'pesananBuku.babBuku', 'admin']);

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
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('nomor_invoice', 'like', "%{$search}%");
            });
        }

        $laporanPembayaran = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistik = [
            'total' => LaporanPenjualanKolaborasi::count(),
            'menunggu' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'disetujui' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'disetujui')->count(),
            'ditolak' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'ditolak')->count(),
        ];

        return view('admin.verifikasi-pembayaran.index', compact('laporanPembayaran', 'statistik'));
    }

    public function show(LaporanPenjualanKolaborasi $laporanPenjualan)
    {
        $laporanPenjualan->load(['pesananBuku.pengguna', 'pesananBuku.bukuKolaboratif', 'pesananBuku.babBuku', 'admin']);
        
        return view('admin.verifikasi-pembayaran.detail', compact('laporanPenjualan'));
    }

    public function setujui(Request $request, LaporanPenjualanKolaborasi $laporanPenjualan)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Update status laporan pembayaran
            $laporanPenjualan->update([
                'status_pembayaran' => 'disetujui',
                'admin_id' => Auth::id(),
                'catatan_admin' => $request->catatan_admin,
                'tanggal_verifikasi' => now()
            ]);

            // Update status pesanan
            $laporanPenjualan->pesananBuku->update([
                'status_pembayaran' => 'lunas',
                'tanggal_bayar' => now(),
                'status_penulisan' => 'belum_mulai'
            ]);

            DB::commit();

            return redirect()->route('admin.verifikasi-pembayaran.index')
                ->with('success', 'Pembayaran berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, LaporanPenjualanKolaborasi $laporanPenjualan)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Update status laporan pembayaran
            $laporanPenjualan->update([
                'status_pembayaran' => 'ditolak',
                'admin_id' => Auth::id(),
                'catatan_admin' => $request->catatan_admin,
                'tanggal_verifikasi' => now()
            ]);

            // Update status pesanan
            $laporanPenjualan->pesananBuku->update([
                'status_pembayaran' => 'gagal'
            ]);

            // Kembalikan status bab menjadi tersedia
            $laporanPenjualan->pesananBuku->babBuku->update([
                'status' => 'tersedia'
            ]);

            DB::commit();

            return redirect()->route('admin.verifikasi-pembayaran.index')
                ->with('success', 'Pembayaran berhasil ditolak!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadBukti(LaporanPenjualanKolaborasi $laporanPenjualan)
    {
        if (!$laporanPenjualan->bukti_pembayaran || !Storage::exists($laporanPenjualan->bukti_pembayaran)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        return Storage::download($laporanPenjualan->bukti_pembayaran);
    }
}
