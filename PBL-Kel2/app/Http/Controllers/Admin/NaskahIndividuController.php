<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenerbitanIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\LaporanBukuIndividu;

class NaskahIndividuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = PenerbitanIndividu::with(['user', 'admin'])
                ->whereIn('status_pembayaran', ['lunas'])
                // Updated to use existing ENUM values
                ->whereIn('status_penerbitan', ['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                ->latest('tanggal_upload_naskah');

            // Filter berdasarkan status penerbitan
            if ($request->filled('status_penerbitan')) {
                $query->where('status_penerbitan', $request->status_penerbitan);
            }

            // Filter berdasarkan paket
            if ($request->filled('paket')) {
                $query->where('paket', $request->paket);
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
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_pesanan', 'like', "%{$search}%")
                        ->orWhere('judul_buku', 'like', "%{$search}%")
                        ->orWhere('nama_penulis', 'like', "%{$search}%")
                        ->orWhereHas('user', function($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            $naskahList = $query->paginate(15);

            // Statistik - Updated to use existing ENUM values
            $statistik = [
                'total' => PenerbitanIndividu::whereIn('status_pembayaran', ['lunas'])
                    ->whereIn('status_penerbitan', ['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                    ->count(),
                'sudah_kirim' => PenerbitanIndividu::where('status_penerbitan', 'sudah_kirim')->count(),
                'revisi' => PenerbitanIndividu::where('status_penerbitan', 'revisi')->count(),
                'disetujui' => PenerbitanIndividu::where('status_penerbitan', 'disetujui')->count(),
                'ditolak' => PenerbitanIndividu::where('status_penerbitan', 'ditolak')->count(),
                'silver' => PenerbitanIndividu::where('paket', 'silver')
                    ->whereIn('status_penerbitan', ['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                    ->count(),
                'gold' => PenerbitanIndividu::where('paket', 'gold')
                    ->whereIn('status_penerbitan', ['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                    ->count(),
                'diamond' => PenerbitanIndividu::where('paket', 'diamond')
                    ->whereIn('status_penerbitan', ['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                    ->count(),
            ];

            return view('admin.naskah-individu.index', compact('naskahList', 'statistik'));

        } catch (\Exception $e) {
            Log::error('Error in NaskahIndividuController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data naskah.');
        }
    }

    public function show($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::with(['user', 'admin', 'laporanPenjualan'])
                ->findOrFail($id);

            // Pastikan naskah sudah diupload
            if (!$penerbitan->file_naskah) {
                return redirect()->route('admin.naskah-individu.index')
                    ->with('error', 'Naskah belum diupload.');
            }

            return view('admin.naskah-individu.show', compact('penerbitan'));

        } catch (\Exception $e) {
            Log::error('Error in NaskahIndividuController@show', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.naskah-individu.index')
                ->with('error', 'Naskah tidak ditemukan atau terjadi kesalahan.');
        }
    }


// Update method updateStatus
public function updateStatus(Request $request, $id)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'status_penerbitan' => [
                'required',
                Rule::in(['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
            ],
            'catatan_review' => 'nullable|string|max:2000'
        ]);

        DB::beginTransaction();

        // Cari penerbitan
        $penerbitan = PenerbitanIndividu::findOrFail($id);

        // Pastikan admin sudah login
        if (!auth()->check()) {
            throw new \Exception('Admin tidak terautentikasi');
        }

        // Update status dan catatan
        $updateData = [
            'status_penerbitan' => $validated['status_penerbitan'],
            'catatan_review' => $validated['catatan_review'],
            'admin_id' => auth()->id(),
            'tanggal_review' => now()
        ];

        $penerbitan->update($updateData);

        // Jika status disetujui, buat laporan penerbitan otomatis
        if ($validated['status_penerbitan'] === 'disetujui') {
            $this->createLaporanPenerbitan($penerbitan);
        }

        DB::commit();

        Log::info('Status naskah individu berhasil diupdate', [
            'penerbitan_id' => $id,
            'status_baru' => $validated['status_penerbitan'],
            'admin_id' => auth()->id(),
            'nomor_pesanan' => $penerbitan->nomor_pesanan
        ]);

        $statusText = match($validated['status_penerbitan']) {
            'sudah_kirim' => 'sudah dikirim',
            'revisi' => 'perlu revisi',
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            default => $validated['status_penerbitan']
        };

        return redirect()->route('admin.naskah-individu.show', $id)
            ->with('success', "Status naskah berhasil diubah menjadi {$statusText}.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            
            Log::error('Naskah tidak ditemukan', [
                'penerbitan_id' => $id,
                'admin_id' => auth()->id() ?? 'not_authenticated'
            ]);

            return redirect()->route('admin.naskah-individu.index')
                ->with('error', 'Naskah tidak ditemukan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error update status naskah individu', [
                'penerbitan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id() ?? 'not_authenticated',
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate status naskah: ' . $e->getMessage());
        }
    }

    public function downloadNaskah($id)
    {
        try {
            $penerbitan = PenerbitanIndividu::findOrFail($id);

            if (!$penerbitan->file_naskah) {
                return redirect()->back()
                    ->with('error', 'File naskah tidak tersedia.');
            }

            $filePath = $penerbitan->file_naskah;
            
            // Cek apakah file ada di storage
            if (!Storage::disk('public')->exists($filePath)) {
                Log::error('File naskah tidak ditemukan di storage', [
                    'penerbitan_id' => $id,
                    'file_path' => $filePath,
                    'admin_id' => auth()->id()
                ]);

                return redirect()->back()
                    ->with('error', 'File naskah tidak ditemukan di server.');
            }

            Log::info('Naskah individu downloaded by admin', [
                'penerbitan_id' => $id,
                'admin_id' => auth()->id(),
                'filename' => $filePath,
                'nomor_pesanan' => $penerbitan->nomor_pesanan
            ]);

            $downloadName = $penerbitan->judul_buku
                ? $penerbitan->judul_buku . '.' . pathinfo($filePath, PATHINFO_EXTENSION)
                : basename($filePath);

            return Storage::disk('public')->download($filePath, $downloadName);

        } catch (\Exception $e) {
            Log::error('Error downloading naskah individu by admin', [
                'penerbitan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id() ?? 'not_authenticated'
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            // Updated validation to use existing ENUM values
            $validated = $request->validate([
                'action' => [
                    'required',
                    Rule::in(['sudah_kirim', 'revisi', 'disetujui', 'ditolak'])
                ],
                'selected_items' => 'required|array|min:1',
                'selected_items.*' => 'exists:penerbitan_individu,id',
                'bulk_catatan' => 'nullable|string|max:2000'
            ]);

            DB::beginTransaction();

            $updated = PenerbitanIndividu::whereIn('id', $validated['selected_items'])
                ->update([
                    'status_penerbitan' => $validated['action'],
                    'catatan_review' => $validated['bulk_catatan'],
                    'admin_id' => auth()->id(),
                    'tanggal_review' => now()
                ]);

            DB::commit();

            Log::info('Bulk action naskah individu berhasil', [
                'action' => $validated['action'],
                'count' => $updated,
                'admin_id' => auth()->id(),
                'items' => $validated['selected_items']
            ]);

            // Updated status text mapping
            $statusText = match($validated['action']) {
                'sudah_kirim' => 'sudah dikirim',
                'revisi' => 'perlu revisi',
                'disetujui' => 'disetujui',
                'ditolak' => 'ditolak',
                default => $validated['action']
            };

            return redirect()->route('admin.naskah-individu.index')
                ->with('success', "{$updated} naskah berhasil diubah statusnya menjadi {$statusText}.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error bulk action naskah individu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id() ?? 'not_authenticated',
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat melakukan bulk action: ' . $e->getMessage());
        }
    }

    // Tambahkan method baru untuk membuat laporan penerbitan
private function createLaporanPenerbitan($penerbitan)
{
    // Cek apakah sudah ada laporan untuk penerbitan ini
    $existingLaporan = LaporanBukuIndividu::where('penerbitan_individu_id', $penerbitan->id)->first();
    
    if (!$existingLaporan) {
        LaporanBukuIndividu::create([
            'kode_buku' => LaporanBukuIndividu::generateKodeBuku(),
            'judul' => $penerbitan->judul_buku,
            'penulis' => $penerbitan->nama_penulis,
            'tanggal_terbit' => now(),
            'jumlah_terjual' => 0, // Default 0
            'status' => 'terbit',
            'penerbitan_individu_id' => $penerbitan->id
        ]);

        Log::info('Laporan penerbitan dibuat otomatis', [
            'penerbitan_id' => $penerbitan->id,
            'nomor_pesanan' => $penerbitan->nomor_pesanan
        ]);
    }
}
}
