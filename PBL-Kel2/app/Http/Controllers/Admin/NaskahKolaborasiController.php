<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenerbitanKolaborasi;
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
        // Ambil semua user dengan berbagai role yang bisa menjadi penulis
        $users = User::whereIn('role', ['penulis', 'user', 'member'])
            ->orderBy('name')
            ->get();

        // Jika tidak ada user dengan role tersebut, ambil semua user kecuali admin
        if ($users->isEmpty()) {
            $users = User::where('role', '!=', 'admin')
                ->orderBy('name')
                ->get();
        }

        // Jika masih kosong, ambil semua user
        if ($users->isEmpty()) {
            $users = User::orderBy('name')->get();
        }

        $bukuKolaboratif = BukuKolaboratif::where('status', 'aktif')->get();

        // Debug log
        Log::info('Create naskah - Users count: ' . $users->count());

        return view('admin.naskahKolaborasi.create', compact('users', 'bukuKolaboratif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_kolaboratif_id' => 'required|exists:buku_kolaboratif,id',
            'bab_buku_id' => 'required|exists:bab_buku,id',
            'nomor_pesanan' => 'required|string|max:50|unique:pesanan_kolaborasi,nomor_pesanan',
            'judul_naskah' => 'nullable|string|max:500',
            'deskripsi_naskah' => 'nullable|string|max:2000',
            'file_naskah' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_kata' => 'nullable|integer|min:0',
            'tanggal_deadline' => 'nullable|date|after:today',
            'catatan_penulis' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            'jumlah_bayar' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah bab masih tersedia
            $bab = BabBuku::find($validated['bab_buku_id']);
            if (!$bab || !$bab->isTersedia()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bab yang dipilih sudah tidak tersedia.');
            }

            // Handle file upload
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
            $validated['jumlah_bayar'] = $validated['jumlah_bayar'] ?? 0;

            // Generate nomor pesanan if not provided or empty
            if (empty($validated['nomor_pesanan'])) {
                $validated['nomor_pesanan'] = $this->generateNomorPesanan();
            }

            $naskah = PesananKolaborasi::create($validated);

            // Update status bab menjadi tidak tersedia
            $bab->markAsTidakTersedia();

            Log::info('Naskah kolaborasi created', [
                'admin_id' => auth()->id(),
                'pesanan_id' => $naskah->id,
                'nomor_pesanan' => $naskah->nomor_pesanan,
                'bab_id' => $bab->id
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah kolaborasi berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating naskah kolaborasi', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan naskah: ' . $e->getMessage());
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

        // Ambil semua user dengan berbagai role yang bisa menjadi penulis
        $users = User::whereIn('role', ['penulis', 'user', 'member'])
            ->orderBy('name')
            ->get();

        // Jika tidak ada user dengan role tersebut, ambil semua user kecuali admin
        if ($users->isEmpty()) {
            $users = User::where('role', '!=', 'admin')
                ->orderBy('name')
                ->get();
        }

        // Jika masih kosong, ambil semua user
        if ($users->isEmpty()) {
            $users = User::orderBy('name')->get();
        }

        $bukuKolaboratif = BukuKolaboratif::where('status', 'aktif')->get();

        return view('admin.naskahKolaborasi.edit', compact('naskah', 'users', 'bukuKolaboratif'));
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
            'status_penulisan' => 'required|in:belum_mulai,dapat_mulai,sedang_ditulis,sudah_kirim,revisi,disetujui,selesai,ditolak', // Tambahkan ini
        ]);

        try {
            DB::beginTransaction();

            // Store old status for comparison
            $oldStatus = $naskah->status_penulisan;
            $newStatus = $validated['status_penulisan'];

            // Handle file upload
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

                // Update status if file uploaded and status is still 'dapat_mulai'
                if ($naskah->status_penulisan === 'dapat_mulai') {
                    $validated['status_penulisan'] = 'sudah_kirim';
                }
            }

            // Handle status changes and their implications
            if ($oldStatus !== $newStatus) {
                // If changing from 'disetujui' to other status, handle bab status
                if ($oldStatus === 'disetujui' && $newStatus !== 'disetujui') {
                    if ($naskah->babBuku) {
                        $naskah->babBuku->markAsTidakTersedia();
                    }
                    // Clear approval data
                    $validated['catatan_persetujuan'] = null;
                    $validated['tanggal_disetujui'] = null;
                }

                // If changing to 'disetujui', mark bab as completed
                if ($newStatus === 'disetujui' && $oldStatus !== 'disetujui') {
                    if ($naskah->babBuku) {
                        $naskah->babBuku->markAsSelesai();
                    }
                    $validated['tanggal_disetujui'] = now();
                }

                // If changing to 'ditolak', return bab to available
                if ($newStatus === 'ditolak' && $oldStatus !== 'ditolak') {
                    if ($naskah->babBuku) {
                        $naskah->babBuku->markAsTersedia();
                    }
                }

                // If changing from 'ditolak' to other status, make bab unavailable again
                if ($oldStatus === 'ditolak' && $newStatus !== 'ditolak') {
                    if ($naskah->babBuku) {
                        $naskah->babBuku->markAsTidakTersedia();
                    }
                }
            }

            // Update bab status if changed
            if ($naskah->bab_buku_id != $validated['bab_buku_id']) {
                // Return old bab to available
                if ($naskah->babBuku) {
                    $naskah->babBuku->markAsTersedia();
                }

                // Check if new bab is available
                $newBab = BabBuku::find($validated['bab_buku_id']);
                if ($newBab && !$newBab->isTersedia()) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Bab yang dipilih sudah tidak tersedia.');
                }

                // Set new bab status based on current naskah status
                if ($newBab) {
                    if (in_array($validated['status_penulisan'], ['disetujui', 'selesai'])) {
                        $newBab->markAsSelesai();
                    } elseif ($validated['status_penulisan'] === 'ditolak') {
                        $newBab->markAsTersedia();
                    } else {
                        $newBab->markAsTidakTersedia();
                    }
                }
            }

            $naskah->update($validated);

            Log::info('Naskah kolaborasi updated', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
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
                ->with('error', 'Terjadi kesalahan saat memperbarui naskah: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $naskah = PesananKolaborasi::findOrFail($id);

            // Delete file if exists
            if ($naskah->file_naskah && Storage::disk('public')->exists($naskah->file_naskah)) {
                Storage::disk('public')->delete($naskah->file_naskah);
            }

            // Return bab to available status
            if ($naskah->babBuku) {
                $naskah->babBuku->markAsTersedia();
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
                ->with('error', 'Terjadi kesalahan saat menghapus naskah: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $pesanan = PesananKolaborasi::findOrFail($id);

            if (!$pesanan->file_naskah) {
                return redirect()->back()->with('error', 'File naskah tidak tersedia.');
            }

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
            Log::info('Getting bab for buku ID: ' . $bukuId);

            // Sesuaikan dengan kolom yang ada di database
            $bab = BabBuku::where('buku_kolaboratif_id', $bukuId)
                ->select('id', 'nomor_bab', 'judul_bab', 'status', 'deskripsi') // Hanya kolom yang ada
                ->orderBy('nomor_bab')
                ->get();

            Log::info('Found bab count: ' . $bab->count());
            Log::info('Bab data: ', $bab->toArray());

            // Transform data untuk response
            $babData = $bab->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nomor_bab' => $item->nomor_bab,
                    'judul_bab' => $item->judul_bab,
                    'status' => $item->status,
                    'deskripsi' => $item->deskripsi,
                    'status_text' => $item->status_text ?? ucfirst(str_replace('_', ' ', $item->status))
                ];
            });

            return response()->json($babData);

        } catch (\Exception $e) {
            Log::error('Error getting bab by buku', [
                'buku_id' => $bukuId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Gagal mengambil data bab',
                'message' => $e->getMessage()
            ], 500);
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

            // Update status bab menjadi selesai
            if ($naskah->babBuku) {
                $naskah->babBuku->markAsSelesai();
            }

            // Debug log sebelum membuat laporan
            Log::info('About to create laporan penerbitan', [
                'naskah_id' => $naskah->id,
                'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id,
                'buku_judul' => $naskah->bukuKolaboratif ? $naskah->bukuKolaboratif->judul : 'null'
            ]);

            // Buat atau update laporan penerbitan
            $this->createOrUpdateLaporanPenerbitan($naskah);

            Log::info('Naskah accepted', [
                'naskah_id' => $id,
                'admin_id' => auth()->id(),
                'nomor_pesanan' => $naskah->nomor_pesanan
            ]);

            DB::commit();

            return redirect()->route('naskahKolaborasi.index')
                ->with('success', 'Naskah berhasil diterima dan laporan penerbitan telah dibuat!');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error accepting naskah', [
                'naskah_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Buat atau update laporan penerbitan kolaborasi
     */
    private function createOrUpdateLaporanPenerbitan($naskah)
    {
        try {
            Log::info('Starting createOrUpdateLaporanPenerbitan', [
                'naskah_id' => $naskah->id,
                'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id
            ]);

            if (!$naskah->bukuKolaboratif) {
                Log::warning('BukuKolaboratif not found for naskah', [
                    'naskah_id' => $naskah->id,
                    'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id
                ]);
                return;
            }

            $bukuKolaboratif = $naskah->bukuKolaboratif;

            // Cek apakah sudah ada laporan untuk buku ini
            $laporan = LaporanPenerbitanKolaborasi::where('buku_kolaboratif_id', $naskah->buku_kolaboratif_id)->first();

            Log::info('Checking existing laporan', [
                'existing_laporan' => $laporan ? $laporan->id : 'not found',
                'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id
            ]);

            if (!$laporan) {
                $kodeBuku = $this->generateKodeBuku($bukuKolaboratif->judul);

                // Buat informasi penulis dan bab untuk catatan
                $penulis = $naskah->user ? $naskah->user->name : 'Tim Kolaborasi';
                $babInfo = $naskah->babBuku ? "Bab {$naskah->babBuku->nomor_bab}: {$naskah->babBuku->judul_bab}" : 'Kolaborasi';

                // Data sesuai dengan struktur tabel yang sebenarnya
                $dataLaporan = [
                    'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id,
                    'kode_buku' => $kodeBuku,
                    'judul' => $bukuKolaboratif->judul,
                    'isbn' => null, // Akan diisi nanti saat terbit
                    'tanggal_terbit' => null, // Akan diisi saat buku benar-benar terbit
                    'status' => 'proses',
                    'penerbit' => null, // Akan diisi nanti
                    'harga_jual' => null, // Akan diisi nanti
                    'jumlah_cetak' => null, // Akan diisi nanti
                    'jumlah_terjual' => 0,
                    'admin_id' => auth()->id(),
                    'catatan' => "Laporan dibuat otomatis saat naskah pertama disetujui.\nPenulis: {$penulis}\nBab: {$babInfo}"
                ];

                Log::info('Creating new laporan with data', $dataLaporan);

                $newLaporan = LaporanPenerbitanKolaborasi::create($dataLaporan);

                Log::info('Laporan penerbitan kolaborasi created successfully', [
                    'laporan_id' => $newLaporan->id,
                    'kode_buku' => $kodeBuku,
                    'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id,
                    'admin_id' => auth()->id()
                ]);

            } else {
                Log::info('Laporan penerbitan sudah ada', [
                    'laporan_id' => $laporan->id,
                    'kode_buku' => $laporan->kode_buku
                ]);

                // Update catatan untuk menunjukkan ada naskah baru yang disetujui
                $penulis = $naskah->user ? $naskah->user->name : 'Tim Kolaborasi';
                $babInfo = $naskah->babBuku ? "Bab {$naskah->babBuku->nomor_bab}: {$naskah->babBuku->judul_bab}" : 'Kolaborasi';

                $currentCatatan = $laporan->catatan ?? '';
                $newCatatan = $currentCatatan . "\n" . now()->format('d/m/Y H:i') . " - Naskah baru disetujui: {$penulis} ({$babInfo})";

                $laporan->update([
                    'catatan' => $newCatatan
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error creating/updating laporan penerbitan', [
                'naskah_id' => $naskah->id,
                'buku_kolaboratif_id' => $naskah->buku_kolaboratif_id ?? 'null',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate kode buku sederhana
     */
    private function generateKodeBuku($judul)
    {
        try {
            $words = explode(' ', $judul);
            $kode = '';

            foreach (array_slice($words, 0, 2) as $word) {
                $kode .= strtoupper(substr($word, 0, 3));
            }

            $year = date('Y');
            $baseKode = "BK-{$kode}-{$year}";

            // Cek apakah kode sudah ada
            $counter = 1;
            $finalKode = $baseKode;

            while (LaporanPenerbitanKolaborasi::where('kode_buku', $finalKode)->exists()) {
                $finalKode = $baseKode . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
                $counter++;
            }

            Log::info('Generated kode buku', [
                'judul' => $judul,
                'kode_buku' => $finalKode
            ]);

            return $finalKode;

        } catch (\Exception $e) {
            Log::error('Error generating kode buku', [
                'judul' => $judul,
                'error' => $e->getMessage()
            ]);

            // Fallback kode
            return 'BK-' . date('Y') . '-' . time();
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
                $naskah->babBuku->markAsTersedia();

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
     * Generate nomor pesanan unik
     */
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

    /**
     * Debug method untuk cek users (hapus setelah selesai)
     */
    public function getUsers()
    {
        $users = User::all();
        $penulis = User::where('role', 'penulis')->get();

        return response()->json([
            'all_users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ];
            }),
            'penulis_only' => $penulis->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ];
            }),
            'total_users' => $users->count(),
            'total_penulis' => $penulis->count()
        ]);
    }
}
