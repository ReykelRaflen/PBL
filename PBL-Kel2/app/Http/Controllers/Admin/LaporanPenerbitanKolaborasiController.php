<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesananKolaborasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LaporanPenerbitanKolaborasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->whereNotNull('file_naskah');

        // Filter berdasarkan status penulisan
        if ($request->filled('status')) {
            $query->where('status_penulisan', $request->status);
        }

        // Filter berdasarkan tanggal upload
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_upload_naskah', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_upload_naskah', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_naskah', 'like', "%{$search}%")
                  ->orWhere('nomor_pesanan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('bukuKolaboratif', function($bukuQuery) use ($search) {
                      $bukuQuery->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        $laporans = $query->orderBy('tanggal_upload_naskah', 'desc')->paginate(15);

        // Statistik untuk dashboard
        $statistik = [
            'total' => PesananKolaborasi::whereNotNull('file_naskah')->count(),
            'perlu_review' => PesananKolaborasi::where('status_penulisan', 'sudah_kirim')->count(),
            'revisi' => PesananKolaborasi::where('status_penulisan', 'revisi')->count(),
            'selesai' => PesananKolaborasi::whereIn('status_penulisan', ['selesai', 'disetujui'])->count(),
            'ditolak' => PesananKolaborasi::where('status_penulisan', 'ditolak')->count()
        ];

        return view('admin.penerbitanKolaborasi.index', compact('laporans', 'statistik'));
    }

    public function show($id)
    {
        $laporan = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->findOrFail($id);

        return view('admin.penerbitanKolaborasi.show', compact('laporan'));
    }

    public function downloadNaskah($id)
    {
        $pesanan = PesananKolaborasi::findOrFail($id);
        
        if (!$pesanan->file_naskah || !Storage::disk('public')->exists($pesanan->file_naskah)) {
            return redirect()->back()->with('error', 'File naskah tidak ditemukan.');
        }

        $fileName = $pesanan->judul_naskah . '.' . pathinfo($pesanan->file_naskah, PATHINFO_EXTENSION);
        
        return Storage::disk('public')->download($pesanan->file_naskah, $fileName);
    }

    public function terimaNaskah(Request $request, $id)
    {
        $request->validate([
            'catatan_persetujuan' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $pesanan = PesananKolaborasi::findOrFail($id);
            
            $pesanan->update([
                'status_penulisan' => 'disetujui',
                'catatan_persetujuan' => $request->catatan_persetujuan,
                'tanggal_disetujui' => now(),
                'admin_id' => auth()->id()
            ]);

            // Update status bab menjadi selesai
            if ($pesanan->babBuku) {
                $pesanan->babBuku->update(['status' => 'selesai']);
            }

            DB::commit();

            Log::info('Naskah disetujui', [
                'pesanan_id' => $pesanan->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('penerbitanKolaborasi.index')
                           ->with('success', 'Naskah berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error menyetujui naskah: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function revisiNaskah(Request $request, $id)
    {
        $request->validate([
            'feedback_editor' => 'required|string|max:1000'
        ], [
            'feedback_editor.required' => 'Feedback untuk revisi harus diisi'
        ]);

        try {
            DB::beginTransaction();

            $pesanan = PesananKolaborasi::findOrFail($id);
            
            $pesanan->update([
                'status_penulisan' => 'revisi',
                'feedback_editor' => $request->feedback_editor,
                'tanggal_feedback' => now(),
                'admin_id' => auth()->id()
            ]);

            DB::commit();

            Log::info('Naskah diminta revisi', [
                'pesanan_id' => $pesanan->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('penerbitanKolaborasi.index')
                           ->with('success', 'Feedback revisi berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error meminta revisi naskah: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tolakNaskah(Request $request, $id)
    {
        $request->validate([
            'feedback_editor' => 'required|string|max:1000'
        ], [
            'feedback_editor.required' => 'Alasan penolakan harus diisi'
        ]);

        try {
            DB::beginTransaction();

            $pesanan = PesananKolaborasi::findOrFail($id);
            
            $pesanan->update([
                'status_penulisan' => 'ditolak',
                'feedback_editor' => $request->feedback_editor,
                'tanggal_feedback' => now(),
                'admin_id' => auth()->id()
            ]);

            // Kembalikan status bab menjadi tersedia
            if ($pesanan->babBuku) {
                $pesanan->babBuku->update(['status' => 'tersedia']);
            }

            DB::commit();

            Log::info('Naskah ditolak', [
                'pesanan_id' => $pesanan->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('penerbitanKolaborasi.index')
                           ->with('success', 'Naskah berhasil ditolak!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error menolak naskah: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Legacy methods for backward compatibility
    public function create()
    {
        return view('admin.penerbitanKolaborasi.create');
    }

    public function store(Request $request)
    {
        // Legacy store method - keep for backward compatibility
        $request->validate([
            'kode_buku' => 'required|unique:laporan_penerbitan_kolaborasi',
            'judul' => 'required',
            'bab_buku'=> 'required',
            'penulis' => 'required',
            'tanggal_terbit' => 'required|date',
            'jumlah_terjual' => 'required|integer',
            'status' => 'required|in:proses,terbit,pending',
        ]);

        // Create legacy record if needed
        return redirect()->route('penerbitanKolaborasi.index')
                       ->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $laporan = PesananKolaborasi::findOrFail($id);
        return view('admin.penerbitanKolaborasi.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        // Legacy update method
        return redirect()->route('penerbitanKolaborasi.index')
                       ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $pesanan = PesananKolaborasi::findOrFail($id);
            
            // Hapus file naskah jika ada
            if ($pesanan->file_naskah && Storage::disk('public')->exists($pesanan->file_naskah)) {
                Storage::disk('public')->delete($pesanan->file_naskah);
            }
            
            // Reset status bab jika perlu
            if ($pesanan->babBuku && $pesanan->status_penulisan !== 'selesai') {
                $pesanan->babBuku->update(['status' => 'tersedia']);
            }
            
            $pesanan->delete();

            return redirect()->route('penerbitanKolaborasi.index')
                           ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
