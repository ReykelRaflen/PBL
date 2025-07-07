<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanBukuIndividu;
use App\Models\PenerbitanIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanPenerbitanIndividuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = LaporanBukuIndividu::with(['penerbitanIndividu.user'])
                ->latest('created_at');

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('kode_buku', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                });
            }

            $laporans = $query->paginate(15);

            // Statistik
            $statistik = [
                'total' => LaporanBukuIndividu::count(),
                'terbit' => LaporanBukuIndividu::where('status', 'terbit')->count(),
                'proses' => LaporanBukuIndividu::where('status', 'proses')->count(),
                'pending' => LaporanBukuIndividu::where('status', 'pending')->count(),
                'dengan_isbn' => LaporanBukuIndividu::whereNotNull('isbn')->where('isbn', '!=', '')->count(),
            ];

            return view('admin.penerbitanIndividu.index', compact('laporans', 'statistik'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanIndividuController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data laporan.');
        }
    }

    public function create()
    {
        try {
            // Ambil naskah yang sudah disetujui dan belum ada laporannya
            $penerbitanTersedia = PenerbitanIndividu::with('user')
                ->where('status_penerbitan', 'disetujui')
                ->whereDoesntHave('laporanPenjualan')
                ->latest('tanggal_review')
                ->get();

            return view('admin.penerbitanIndividu.create', compact('penerbitanTersedia'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanIndividuController@create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman tambah laporan.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_buku' => 'required|unique:laporan_penerbitan_individu',
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'tanggal_terbit' => 'nullable|date',
                'isbn' => 'nullable|string|max:50',
                'status' => 'required|in:proses,terbit,pending',
                'penerbitan_individu_id' => 'nullable|exists:penerbitan_individu,id',
            ]);

            DB::beginTransaction();

            LaporanBukuIndividu::create($validated);

            DB::commit();

            Log::info('Laporan penerbitan individu berhasil ditambahkan', [
                'kode_buku' => $validated['kode_buku'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index')
                ->with('success', 'Laporan penerbitan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating laporan penerbitan individu', [
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
            $laporan = LaporanBukuIndividu::with(['penerbitanIndividu.user'])
                ->findOrFail($id);

            return view('admin.penerbitanIndividu.show', compact('laporan'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanIndividuController@show', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index')
                ->with('error', 'Laporan tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function edit($id)
    {
        try {
            $laporan = LaporanBukuIndividu::with(['penerbitanIndividu.user'])
                ->findOrFail($id);

            return view('admin.penerbitanIndividu.edit', compact('laporan'));

        } catch (\Exception $e) {
            Log::error('Error in LaporanPenerbitanIndividuController@edit', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index')
                ->with('error', 'Laporan tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $laporan = LaporanBukuIndividu::findOrFail($id);

            $validated = $request->validate([
                'kode_buku' => 'required|unique:laporan_penerbitan_individu,kode_buku,' . $id,
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'tanggal_terbit' => 'nullable|date',
                'isbn' => 'nullable|string|max:50',
                'status' => 'required|in:proses,terbit,pending',
            ]);

            DB::beginTransaction();

            $laporan->update($validated);

            DB::commit();

            Log::info('Laporan penerbitan individu berhasil diupdate', [
                'laporan_id' => $id,
                'kode_buku' => $validated['kode_buku'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index', $id)
                ->with('success', 'Laporan penerbitan berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating laporan penerbitan individu', [
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
            $laporan = LaporanBukuIndividu::findOrFail($id);
            $kodeBuku = $laporan->kode_buku;

            DB::beginTransaction();

            $laporan->delete();

            DB::commit();

            Log::info('Laporan penerbitan individu berhasil dihapus', [
                'laporan_id' => $id,
                'kode_buku' => $kodeBuku,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.penerbitanIndividu.index')
                ->with('success', 'Laporan penerbitan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting laporan penerbitan individu', [
                'laporan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus laporan: ' . $e->getMessage());
        }
    }
}
