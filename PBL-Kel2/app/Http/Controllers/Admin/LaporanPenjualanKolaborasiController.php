<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LaporanPenjualanKolaborasi;
use App\Models\PesananKolaborasi;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanKolaborasiController extends Controller
{
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
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('nomor_invoice', 'like', "%{$search}%");
            });
        }

        $laporan = $query->paginate(15);

        // Statistik untuk dashboard
        $statistik = [
            'total' => LaporanPenjualanKolaborasi::count(),
            'menunggu' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'sukses' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'sukses')->count(),
            'ditolak' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'tidak sesuai')->count(),
            'total_nilai' => LaporanPenjualanKolaborasi::where('status_pembayaran', 'sukses')->sum('jumlah_pembayaran')
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

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $validated['tanggal'] = now()->toDateString();
        $validated['nomor_invoice'] = $this->generateInvoiceNumber();
        $validated['admin_id'] = auth()->id();

        LaporanPenjualanKolaborasi::create($validated);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil ditambahkan.');
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
            'status_pembayaran' => 'required|in:sukses,tidak sesuai,menunggu_verifikasi',
            'pesanan_kolaborasi_id' => 'nullable|exists:pesanan_kolaborasi,id',
        ]);

        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
                Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
            }
            $file = $request->file('bukti_pembayaran');
            $filename = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/bukti', $filename);
            $validated['bukti_pembayaran'] = $filename;
        }

        $laporan->update($validated);

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        
        if ($laporan->bukti_pembayaran && Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
            Storage::delete('public/bukti/'.$laporan->bukti_pembayaran);
        }
        
        $laporan->delete();

        return redirect()->route('penjualanKolaborasi.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sukses,tidak sesuai',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
            
            // Update status laporan
            $laporan->update([
                'status_pembayaran' => $request->status,
                'catatan_admin' => $request->catatan_admin,
                'admin_id' => auth()->id(),
                'tanggal_verifikasi' => now()
            ]);

            // Update status pesanan jika ada relasi ke pesanan_kolaborasi
            if ($laporan->pesananKolaborasi) {
                if ($request->status === 'sukses') {
                    $laporan->pesananKolaborasi->update([
                        'status_pembayaran' => 'lunas',
                        'status_penulisan' => 'dapat_mulai',
                        'admin_id' => auth()->id(),
                        'tanggal_verifikasi' => now(),
                        'hasil_verifikasi' => 'disetujui'
                    ]);
                } else {
                    $laporan->pesananKolaborasi->update([
                        'status_pembayaran' => 'tidak_sesuai',
                        'admin_id' => auth()->id(),
                        'tanggal_verifikasi' => now(),
                        'hasil_verifikasi' => 'ditolak',
                        'catatan_admin' => $request->catatan_admin
                    ]);
                    
                    // Kembalikan status bab menjadi tersedia
                    if ($laporan->pesananKolaborasi->babBuku) {
                        $laporan->pesananKolaborasi->babBuku->update(['status' => 'tersedia']);
                    }
                }
            }

            DB::commit();

            $message = $request->status === 'sukses' ? 'Pembayaran berhasil disetujui!' : 'Pembayaran ditolak.';
            return redirect()->route('penjualanKolaborasi.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadBukti($id)
    {
        $laporan = LaporanPenjualanKolaborasi::findOrFail($id);
        
        if (!$laporan->bukti_pembayaran || !Storage::exists('public/bukti/'.$laporan->bukti_pembayaran)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        return Storage::download('public/bukti/'.$laporan->bukti_pembayaran);
    }

    private function generateInvoiceNumber()
    {
        do {
            $invoice = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (LaporanPenjualanKolaborasi::where('nomor_invoice', $invoice)->exists());

        return $invoice;
    }
}
