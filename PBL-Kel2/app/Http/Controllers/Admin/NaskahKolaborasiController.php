<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PesananKolaborasi;
use App\Models\BukuKolaboratif;
use App\Models\BabBuku;
use App\Models\User;

class NaskahKolaborasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas') // Hanya yang sudah bayar
            ->latest();

        // Filter berdasarkan status penulisan
        if ($request->filled('status')) {
            $query->where('status_penulisan', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%{$search}%")
                    ->orWhere('judul_naskah', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('bukuKolaboratif', function ($bukuQuery) use ($search) {
                        $bukuQuery->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        $laporans = $query->paginate(15);

        // Statistik untuk dashboard
        $statistik = [
            'total' => PesananKolaborasi::where('status_pembayaran', 'lunas')->count(),
            'perlu_review' => PesananKolaborasi::where('status_pembayaran', 'lunas')
                ->where('status_penulisan', 'sudah_kirim')->count(),
            'revisi' => PesananKolaborasi::where('status_pembayaran', 'lunas')
                ->where('status_penulisan', 'revisi')->count(),
            'selesai' => PesananKolaborasi::where('status_pembayaran', 'lunas')
                ->where('status_penulisan', 'disetujui')->count(),
            'ditolak' => PesananKolaborasi::where('status_pembayaran', 'lunas')
                ->where('status_penulisan', 'ditolak')->count(),
        ];

        return view('admin.naskahKolaborasi.index', compact('laporans', 'statistik'));
    }

    public function create()
    {
        $users = User::where('role', 'penulis')->get();
        $bukuKolaboratif = BukuKolaboratif::where('status', 'aktif')->get();
        $pesananBuku = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->get();

        return view('admin.naskahKolaborasi.create', compact('users', 'bukuKolaboratif', 'pesananBuku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_kolaboratif_id' => 'required|exists:buku_kolaboratif,id',
            'bab_buku_id' => 'required|exists:bab_buku,id',
            'pesanan_kolaborasi_id' => 'nullable|exists:pesanan_kolaborasi,id',
            'nomor_pesanan' => 'required|string|max:50|unique:pesanan_kolaborasi,nomor_pesanan',
            'judul_naskah' => 'nullable|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'file_naskah' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_kata' => 'nullable|integer|min:0',
            'tanggal_deadline' => 'nullable|date|after:today',
            'catatan_penulis' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload - menggunakan disk public
            if ($request->hasFile('file_naskah')) {
                $file = $request->file('file_naskah');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('naskah', $filename, 'public');
                $validated['file_naskah'] = $filePath;
                $validated['tanggal_upload_naskah'] = now();
            }

            // Set default values
            $validated['status_pembayaran'] = 'lunas';
            $validated['status_penulisan'] = $request->hasFile('file_naskah') ? 'sudah_kirim' : 'dapat_mulai';
            $validated['tanggal_pesanan'] = now();
            $validated['admin_id'] = auth()->id();

            // Generate nomor pesanan if not provided
            if (!$validated['nomor_pesanan']) {
                $validated['nomor_pesanan'] = $this->generateNomorPesanan();
            }

            $naskah = PesananKolaborasi::create($validated);

            // Update status bab menjadi tidak tersedia
            if ($naskah->babBuku) {
                $naskah->babBuku->update(['status' => 'tidak_tersedia']);
            }

            Log::info('Naskah kolaborasi created', [
                'admin_id' => auth()->id(),
                'pesanan_id' => $naskah->id,
                'nomor_pesanan' => $naskah->nomor_pesanan
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah kolaborasi berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating naskah kolaborasi', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan naskah.');
        }
    }

    public function show($id)
    {
        $naskah = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->findOrFail($id);

        return view('admin.naskahKolaborasi.show', compact('naskah'));
    }

    public function edit($id)
    {
        $naskah = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->findOrFail($id);

        $users = User::where('role', 'penulis')->get();
        $bukuKolaboratif = BukuKolaboratif::where('status', 'aktif')->get();

        // Perbaikan: tambahkan variabel pesananKolaborasi
        $pesananKolaborasi = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->get();

        return view('admin.naskahKolaborasi.edit', compact('naskah', 'users', 'bukuKolaboratif', 'pesananKolaborasi'));
    }

    public function update(Request $request, $id)
    {
        $naskah = PesananKolaborasi::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_kolaboratif_id' => 'required|exists:buku_kolaboratif,id',
            'bab_buku_id' => 'required|exists:bab_buku,id',
            'judul_naskah' => 'nullable|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'file_naskah' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_kata' => 'nullable|integer|min:0',
            'tanggal_deadline' => 'nullable|date',
            'catatan_penulis' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload - menggunakan disk public
            if ($request->hasFile('file_naskah')) {
                // Delete old file if exists
                if ($naskah->file_naskah && Storage::disk('public')->exists($naskah->file_naskah)) {
                    Storage::disk('public')->delete($naskah->file_naskah);
                }

                $file = $request->file('file_naskah');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('naskah', $filename, 'public');
                $validated['file_naskah'] = $filePath;
                $validated['tanggal_upload_naskah'] = now();

                // Update status if file uploaded
                if (!$naskah->file_naskah) {
                    $validated['status_penulisan'] = 'sudah_kirim';
                }
            }

            // Update bab status if changed
            if ($naskah->bab_buku_id != $validated['bab_buku_id']) {
                // Return old bab to available
                if ($naskah->babBuku) {
                    $naskah->babBuku->update(['status' => 'tersedia']);
                }

                // Set new bab to unavailable
                $newBab = BabBuku::find($validated['bab_buku_id']);
                if ($newBab) {
                    $newBab->update(['status' => 'tidak_tersedia']);
                }
            }

            $naskah->update($validated);

            Log::info('Naskah kolaborasi updated', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah kolaborasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating naskah kolaborasi', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui naskah.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $naskah = PesananKolaborasi::findOrFail($id);

            // Delete file if exists - menggunakan disk public
            if ($naskah->file_naskah && Storage::disk('public')->exists($naskah->file_naskah)) {
                Storage::disk('public')->delete($naskah->file_naskah);
            }

            // Return bab to available status
            if ($naskah->babBuku) {
                $naskah->babBuku->update(['status' => 'tersedia']);
            }

            $nomor_pesanan = $naskah->nomor_pesanan;
            $naskah->delete();

            Log::info('Naskah kolaborasi deleted', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $nomor_pesanan
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah kolaborasi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting naskah kolaborasi', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus naskah.');
        }
    }

    public function download($id)
    {
        try {
            // Ambil data dari tabel pesanan_kolaborasi
            $pesanan = PesananKolaborasi::findOrFail($id);

            if (!$pesanan->file_naskah) {
                return redirect()->back()->with('error', 'File naskah tidak tersedia.');
            }

            // Path file di storage/app/public/ - sesuaikan dengan disk public
            if (!Storage::disk('public')->exists($pesanan->file_naskah)) {
                return redirect()->back()->with('error', 'File naskah tidak ditemukan di server.');
            }

            Log::info('Naskah downloaded', [
                'pesanan_id' => $id,
                'admin_id' => auth()->id(),
                'filename' => $pesanan->file_naskah,
                'nomor_pesanan' => $pesanan->nomor_pesanan
            ]);

            // Generate nama file untuk download
            $downloadName = $pesanan->judul_naskah
                ? $pesanan->judul_naskah . '.' . pathinfo($pesanan->file_naskah, PATHINFO_EXTENSION)
                : basename($pesanan->file_naskah);

            return Storage::disk('public')->download($pesanan->file_naskah, $downloadName);

        } catch (\Exception $e) {
            Log::error('Error downloading naskah', [
                'pesanan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Get Bab by Buku ID (for AJAX)
     */
    public function getBabByBuku($bukuId)
    {
        try {
            $bab = BabBuku::where('buku_kolaboratif_id', $bukuId)
                ->select('id', 'nomor_bab', 'judul_bab', 'status')
                ->orderBy('nomor_bab')
                ->get();

            return response()->json($bab);
        } catch (\Exception $e) {
            Log::error('Error getting bab by buku', [
                'buku_id' => $bukuId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Gagal mengambil data bab'], 500);
        }
    }

    /**
     * Get Pesanan Detail (for AJAX)
     */
    public function getPesananDetail($pesananId)
    {
        try {
            $pesanan = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku', 'user'])
                ->findOrFail($pesananId);

            return response()->json([
                'id' => $pesanan->id,
                'nomor_pesanan' => $pesanan->nomor_pesanan,
                'user_id' => $pesanan->user_id,
                'buku_kolaboratif_id' => $pesanan->buku_kolaboratif_id,
                'bab_buku_id' => $pesanan->bab_buku_id,
                'jumlah_bayar' => $pesanan->jumlah_bayar,
                'status_pembayaran' => $pesanan->status_pembayaran,
                'catatan' => $pesanan->catatan,
                'user' => [
                    'id' => $pesanan->user->id,
                    'name' => $pesanan->user->name,
                    'email' => $pesanan->user->email
                ],
                'buku' => [
                    'id' => $pesanan->bukuKolaboratif->id,
                    'judul' => $pesanan->bukuKolaboratif->judul
                ],
                'bab' => $pesanan->babBuku ? [
                    'id' => $pesanan->babBuku->id,
                    'nomor_bab' => $pesanan->babBuku->nomor_bab,
                    'judul_bab' => $pesanan->babBuku->judul_bab
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting pesanan detail', [
                'pesanan_id' => $pesananId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Gagal mengambil data pesanan'], 500);
        }
    }

    /**
     * Terima naskah
     */
    public function terima(Request $request, $id)
    {
        $request->validate([
            'catatan_persetujuan' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $naskah = PesananKolaborasi::findOrFail($id);

            if ($naskah->status_penulisan !== 'sudah_kirim') {
                return redirect()->back()
                    ->with('error', 'Naskah tidak dalam status yang dapat diterima.');
            }

            $naskah->update([
                'status_penulisan' => 'disetujui',
                'catatan_persetujuan' => $request->catatan_persetujuan,
                'tanggal_disetujui' => now(),
                'admin_id' => auth()->id()
            ]);

            Log::info('Naskah accepted', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah berhasil diterima!');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error accepting naskah', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Minta revisi naskah
     */
    public function revisi(Request $request, $id)
    {
        $request->validate([
            'feedback_editor' => 'required|string|min:10|max:1000'
        ], [
            'feedback_editor.required' => 'Feedback untuk revisi harus diisi',
            'feedback_editor.min' => 'Feedback minimal 10 karakter',
            'feedback_editor.max' => 'Feedback maksimal 1000 karakter'
        ]);

        try {
            DB::beginTransaction();

            $naskah = PesananKolaborasi::findOrFail($id);

            if ($naskah->status_penulisan !== 'sudah_kirim') {
                return redirect()->back()
                    ->with('error', 'Naskah tidak dalam status yang dapat direvisi.');
            }

            $naskah->update([
                'status_penulisan' => 'revisi',
                'feedback_editor' => $request->feedback_editor,
                'tanggal_feedback' => now(),
                'admin_id' => auth()->id()
            ]);

            Log::info('Naskah revision requested', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan,
                'feedback' => $request->feedback_editor
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Permintaan revisi berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error requesting revision', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tolak naskah
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'feedback_editor' => 'required|string|min:10|max:1000'
        ], [
            'feedback_editor.required' => 'Alasan penolakan harus diisi',
            'feedback_editor.min' => 'Alasan penolakan minimal 10 karakter',
            'feedback_editor.max' => 'Alasan penolakan maksimal 1000 karakter'
        ]);

        try {
            DB::beginTransaction();

            $naskah = PesananKolaborasi::findOrFail($id);

            if ($naskah->status_penulisan !== 'sudah_kirim') {
                return redirect()->back()
                    ->with('error', 'Naskah tidak dalam status yang dapat ditolak.');
            }

            $naskah->update([
                'status_penulisan' => 'ditolak',
                'feedback_editor' => $request->feedback_editor,
                'tanggal_feedback' => now(),
                'admin_id' => auth()->id()
            ]);

            // Kembalikan status bab menjadi tersedia
            if ($naskah->babBuku) {
                $naskah->babBuku->update(['status' => 'tersedia']);

                Log::info('Chapter status returned to available after rejection', [
                    'bab_id' => $naskah->babBuku->id,
                    'bab_judul' => $naskah->babBuku->judul_bab ?? 'N/A'
                ]);
            }

            Log::info('Naskah rejected', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan,
                'reason' => $request->feedback_editor
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah berhasil ditolak. Bab telah dikembalikan ke status tersedia.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error rejecting naskah', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateNomorPesanan()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $nomor = 'NK-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                $nomor = 'NK-' . date('Ymd') . '-' . time();
                break;
            }
        } while (PesananKolaborasi::where('nomor_pesanan', $nomor)->exists());

        return $nomor;
    }
}
