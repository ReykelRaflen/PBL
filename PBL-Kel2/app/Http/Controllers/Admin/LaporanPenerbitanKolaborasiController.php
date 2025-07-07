<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenerbitanKolaborasi;
use App\Models\BukuKolaboratif;
use App\Models\PesananKolaborasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LaporanPenerbitanKolaborasiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = LaporanPenerbitanKolaborasi::with(['bukuKolaboratif', 'admin'])
                ->latest('created_at');

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan tanggal terbit
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_terbit', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_terbit', '<=', $request->tanggal_selesai);
            }

            // Search
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            $laporans = $query->paginate(15);

            // Statistik untuk dashboard
            $statistik = [
                'total' => LaporanPenerbitanKolaborasi::count(),
                'draft' => LaporanPenerbitanKolaborasi::where('status', 'draft')->count(),
                'proses' => LaporanPenerbitanKolaborasi::where('status', 'proses')->count(),
                'pending' => LaporanPenerbitanKolaborasi::where('status', 'pending')->count(),
                'terbit' => LaporanPenerbitanKolaborasi::where('status', 'terbit')->count(),
                'dengan_isbn' => LaporanPenerbitanKolaborasi::whereNotNull('isbn')->where('isbn', '!=', '')->count(),
                'total_terjual' => LaporanPenerbitanKolaborasi::sum('jumlah_terjual'),
            ];

            return view('admin.laporanPenerbitanKolaborasi.index', compact('laporans', 'statistik'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanKolaborasiController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data laporan.');
        }
    }

    public function create()
    {
        try {
            // Ambil buku kolaboratif yang belum ada laporannya
            $bukuTersedia = BukuKolaboratif::where('status', 'aktif')
                ->whereDoesntHave('laporanPenerbitan')
                ->with('babBuku')
                ->latest()
                ->get();

            return view('admin.laporanPenerbitanKolaborasi.create', compact('bukuTersedia'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanKolaborasiController@create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman tambah laporan.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'buku_kolaboratif_id' => 'required|exists:buku_kolaboratif,id',
                'kode_buku' => 'required|unique:laporan_penerbitan_kolaborasi,kode_buku',
                'judul' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:20',
                'tanggal_terbit' => 'nullable|date',
                'status' => 'required|in:draft,proses,pending,terbit',
                'penerbit' => 'nullable|string|max:255',
                'harga_jual' => 'nullable|numeric|min:0',
                'jumlah_cetak' => 'nullable|integer|min:0',
                'jumlah_terjual' => 'nullable|integer|min:0',
                'catatan' => 'nullable|string|max:2000',
            ]);

            DB::beginTransaction();

            $validated['admin_id'] = auth()->id();
            $validated['jumlah_terjual'] = $validated['jumlah_terjual'] ?? 0;

            $laporan = LaporanPenerbitanKolaborasi::create($validated);

            DB::commit();

            Log::info('Laporan penerbitan kolaborasi berhasil ditambahkan', [
                'laporan_id' => $laporan->id,
                'kode_buku' => $validated['kode_buku'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index')
                ->with('success', 'Laporan penerbitan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating laporan penerbitan kolaborasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::with([
                'bukuKolaboratif.babBuku', 
                'admin',
                'naskahKolaborasi.user'
            ])->findOrFail($id);

            // Ambil progress penulisan
            $progressData = $laporan->updateProgress();

            return view('admin.laporanPenerbitanKolaborasi.show', compact('laporan', 'progressData'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanKolaborasiController@show', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index')
                ->with('error', 'Laporan tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function edit($id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::with(['bukuKolaboratif', 'admin'])
                ->findOrFail($id);

            return view('admin.laporanPenerbitanKolaborasi.edit', compact('laporan'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanKolaborasiController@edit', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index')
                ->with('error', 'Laporan tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::findOrFail($id);

            $validated = $request->validate([
                'kode_buku' => 'required|unique:laporan_penerbitan_kolaborasi,kode_buku,' . $id,
                'judul' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:20',
                'tanggal_terbit' => 'nullable|date',
                'status' => 'required|in:draft,proses,pending,terbit',
                'penerbit' => 'nullable|string|max:255',
                'harga_jual' => 'nullable|numeric|min:0',
                'jumlah_cetak' => 'nullable|integer|min:0',
                'jumlah_terjual' => 'nullable|integer|min:0',
                'catatan' => 'nullable|string|max:2000',
            ]);

            DB::beginTransaction();

            $laporan->update($validated);

            DB::commit();

            Log::info('Laporan penerbitan kolaborasi berhasil diupdate', [
                'laporan_id' => $id,
                'kode_buku' => $validated['kode_buku'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index', $id)
                ->with('success', 'Laporan penerbitan berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating laporan penerbitan kolaborasi', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui laporan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::findOrFail($id);
            $kodeBuku = $laporan->kode_buku;

            DB::beginTransaction();

            $laporan->delete();

            DB::commit();

            Log::info('Laporan penerbitan kolaborasi berhasil dihapus', [
                'laporan_id' => $id,
                'kode_buku' => $kodeBuku,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.laporanPenerbitanKolaborasi.index')
                ->with('success', 'Laporan penerbitan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting laporan penerbitan kolaborasi', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus laporan: ' . $e->getMessage());
        }
    }

    public function downloadNaskah($laporanId, $naskahId)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::findOrFail($laporanId);
            $naskah = PesananKolaborasi::where('buku_kolaboratif_id', $laporan->buku_kolaboratif_id)
                ->where('id', $naskahId)
                ->whereNotNull('file_naskah')
                ->firstOrFail();

            if (!Storage::disk('public')->exists($naskah->file_naskah)) {
                return redirect()->back()->with('error', 'File naskah tidak ditemukan.');
            }

            $fileName = $naskah->judul_naskah . '.' . pathinfo($naskah->file_naskah, PATHINFO_EXTENSION);
            
            Log::info('Naskah downloaded from laporan', [
                'laporan_id' => $laporanId,
                'naskah_id' => $naskahId,
                'admin_id' => auth()->id()
            ]);

            return Storage::disk('public')->download($naskah->file_naskah, $fileName);

        } catch (\Exception $e) {
            Log::error('Error downloading naskah from laporan', [
                'laporan_id' => $laporanId,
                'naskah_id' => $naskahId,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }

    public function downloadAllNaskah($id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::with('naskahKolaborasi')->findOrFail($id);
            
            if ($laporan->naskahKolaborasi->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada naskah yang tersedia untuk diunduh.');
            }

            // Create ZIP file
            $zip = new \ZipArchive();
            $zipFileName = storage_path('app/temp/') . $laporan->kode_buku . '_naskah_' . date('Y-m-d_H-i-s') . '.zip';
            
            // Ensure temp directory exists
            if (!file_exists(dirname($zipFileName))) {
                mkdir(dirname($zipFileName), 0755, true);
            }

            if ($zip->open($zipFileName, \ZipArchive::CREATE) !== TRUE) {
                return redirect()->back()->with('error', 'Tidak dapat membuat file ZIP.');
            }

            foreach ($laporan->naskahKolaborasi as $naskah) {
                if ($naskah->file_naskah && Storage::disk('public')->exists($naskah->file_naskah)) {
                    $filePath = storage_path('app/public/' . $naskah->file_naskah);
                    $fileName = ($naskah->judul_naskah ?: 'Naskah_' . $naskah->id) . '.' . pathinfo($naskah->file_naskah, PATHINFO_EXTENSION);
                                    $zip->addFile($filePath, $fileName);
                }
            }

            $zip->close();

            Log::info('All naskah downloaded from laporan', [
                'laporan_id' => $id,
                'total_files' => $laporan->naskahKolaborasi->count(),
                'admin_id' => auth()->id()
            ]);

            return response()->download($zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading all naskah from laporan', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh semua file.');
        }
    }

    /**
     * Generate kode buku otomatis berdasarkan judul
     */
    public function generateKodeBuku(Request $request)
    {
        try {
            $judul = $request->input('judul');
            if (!$judul) {
                return response()->json(['error' => 'Judul tidak boleh kosong'], 400);
            }

            $kodeBuku = LaporanPenerbitanKolaborasi::generateKodeBuku($judul);
            
            return response()->json(['kode_buku' => $kodeBuku]);

        } catch (\Exception $e) {
            Log::error('Error generating kode buku', [
                'error' => $e->getMessage(),
                'judul' => $request->input('judul')
            ]);

            return response()->json(['error' => 'Gagal generate kode buku'], 500);
        }
    }

    /**
     * Get progress data for specific laporan
     */
    public function getProgress($id)
    {
        try {
            $laporan = LaporanPenerbitanKolaborasi::with(['bukuKolaboratif.babBuku', 'naskahKolaborasi'])
                ->findOrFail($id);

            $progressData = $laporan->updateProgress();
            
            return response()->json([
                'success' => true,
                'data' => $progressData,
                'laporan' => [
                    'id' => $laporan->id,
                    'status' => $laporan->status,
                    'status_text' => $laporan->status_text,
                    'persentase_selesai' => $laporan->persentase_selesai,
                    'is_complete' => $laporan->is_complete
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting progress data', [
                'laporan_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data progress'
            ], 500);
        }
    }

    /**
     * Update status laporan
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:draft,proses,pending,terbit',
                'catatan' => 'nullable|string|max:1000'
            ]);

            $laporan = LaporanPenerbitanKolaborasi::findOrFail($id);
            
            DB::beginTransaction();

            $updateData = [
                'status' => $validated['status']
            ];

            if (isset($validated['catatan'])) {
                $updateData['catatan'] = $validated['catatan'];
            }

            // Jika status berubah ke terbit, set tanggal terbit jika belum ada
            if ($validated['status'] === 'terbit' && !$laporan->tanggal_terbit) {
                $updateData['tanggal_terbit'] = now();
            }

            $laporan->update($updateData);

            DB::commit();

            Log::info('Status laporan penerbitan updated', [
                'laporan_id' => $id,
                'old_status' => $laporan->getOriginal('status'),
                'new_status' => $validated['status'],
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'data' => [
                    'status' => $laporan->status,
                    'status_text' => $laporan->status_text,
                    'status_badge' => $laporan->status_badge
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating status laporan', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memperbarui status'
            ], 500);
        }
    }

    /**
     * Bulk update status untuk multiple laporan
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'laporan_ids' => 'required|array',
                'laporan_ids.*' => 'exists:laporan_penerbitan_kolaborasi,id',
                'status' => 'required|in:draft,proses,pending,terbit',
                'catatan' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $updateData = [
                'status' => $validated['status']
            ];

            if (isset($validated['catatan'])) {
                $updateData['catatan'] = $validated['catatan'];
            }

            // Jika status terbit, set tanggal terbit untuk yang belum ada
            if ($validated['status'] === 'terbit') {
                $updateData['tanggal_terbit'] = now();
            }

            $updated = LaporanPenerbitanKolaborasi::whereIn('id', $validated['laporan_ids'])
                ->update($updateData);

            DB::commit();

            Log::info('Bulk status update completed', [
                'laporan_ids' => $validated['laporan_ids'],
                'status' => $validated['status'],
                'updated_count' => $updated,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', "Status {$updated} laporan berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Data yang dipilih tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error in bulk status update', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Export laporan ke Excel/CSV
     */
    public function export(Request $request)
    {
        try {
            $format = $request->input('format', 'excel'); // excel atau csv
            
            $query = LaporanPenerbitanKolaborasi::with(['bukuKolaboratif', 'admin']);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_terbit', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_terbit', '<=', $request->tanggal_selesai);
            }

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            $laporans = $query->get();

            $filename = 'laporan_penerbitan_kolaborasi_' . date('Y-m-d_H-i-s');

            if ($format === 'csv') {
                return $this->exportToCsv($laporans, $filename);
            } else {
                return $this->exportToExcel($laporans, $filename);
            }

        } catch (\Exception $e) {
            Log::error('Error exporting laporan', [
                'error' => $e->getMessage(),
                'format' => $request->input('format'),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data.');
        }
    }

    private function exportToCsv($laporans, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function() use ($laporans) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Kode Buku',
                'Judul Buku',
                'ISBN',
                'Status',
                'Penerbit',
                'Tanggal Terbit',
                'Harga Jual',
                'Jumlah Cetak',
                'Jumlah Terjual',
                'Admin',
                'Dibuat Pada'
            ]);

            // Data
            foreach ($laporans as $laporan) {
                fputcsv($file, [
                    $laporan->kode_buku,
                    $laporan->judul,
                    $laporan->isbn,
                    $laporan->status_text,
                    $laporan->penerbit,
                    $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('d/m/Y') : '',
                    $laporan->harga_jual,
                    $laporan->jumlah_cetak,
                    $laporan->jumlah_terjual,
                    $laporan->admin->name ?? '',
                    $laporan->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($laporans, $filename)
    {
        // Implementasi export ke Excel menggunakan PhpSpreadsheet atau library lain
        // Untuk sementara, kita gunakan CSV dengan header Excel
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
        ];

        $callback = function() use ($laporans) {
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>Kode Buku</th>';
            echo '<th>Judul Buku</th>';
            echo '<th>ISBN</th>';
            echo '<th>Status</th>';
            echo '<th>Penerbit</th>';
            echo '<th>Tanggal Terbit</th>';
            echo '<th>Harga Jual</th>';
            echo '<th>Jumlah Cetak</th>';
            echo '<th>Jumlah Terjual</th>';
            echo '<th>Admin</th>';
            echo '<th>Dibuat Pada</th>';
            echo '</tr>';

            foreach ($laporans as $laporan) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($laporan->kode_buku) . '</td>';
                echo '<td>' . htmlspecialchars($laporan->judul) . '</td>';
                echo '<td>' . htmlspecialchars($laporan->isbn) . '</td>';
                echo '<td>' . htmlspecialchars($laporan->status_text) . '</td>';
                echo '<td>' . htmlspecialchars($laporan->penerbit) . '</td>';
                echo '<td>' . ($laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('d/m/Y') : '') . '</td>';
                echo '<td>' . ($laporan->harga_jual ? 'Rp ' . number_format($laporan->harga_jual, 0, ',', '.') : '') . '</td>';
                echo '<td>' . ($laporan->jumlah_cetak ? number_format($laporan->jumlah_cetak, 0, ',', '.') : '') . '</td>';
                echo '<td>' . number_format($laporan->jumlah_terjual, 0, ',', '.') . '</td>';
                echo '<td>' . htmlspecialchars($laporan->admin->name ?? '') . '</td>';
                echo '<td>' . $laporan->created_at->format('d/m/Y H:i') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        };

        return response()->stream($callback, 200, $headers);
    }
}
