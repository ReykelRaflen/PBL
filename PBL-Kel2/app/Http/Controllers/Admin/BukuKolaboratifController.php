<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKolaboratif;
use App\Models\BabBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BukuKolaboratifController extends Controller
{
    public function index()
    {
        $bukuKolaboratif = BukuKolaboratif::with(['kategoriBuku'])
            ->withCount('babBuku')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.buku-kolaboratif.index', compact('bukuKolaboratif'));
    }

    public function create()
    {
        $kategoriBuku = KategoriBuku::where('status', true)->orderBy('nama')->get();
        return view('admin.buku-kolaboratif.create', compact('kategoriBuku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_buku_id' => 'required|exists:kategori_buku,id',
            'harga_per_bab' => 'required|numeric|min:0',
            'total_bab' => 'required|integer|min:1|max:50',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:aktif,nonaktif',
            'bab_data' => 'required|array|min:1',
            'bab_data.*.judul_bab' => 'required|string|max:255',
            'bab_data.*.deskripsi' => 'nullable|string',
            'bab_data.*.harga' => 'nullable|numeric|min:0',
            'bab_data.*.tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
        ], [
            'judul.required' => 'Judul buku harus diisi',
            'deskripsi.required' => 'Deskripsi buku harus diisi',
            'kategori_buku_id.required' => 'Kategori buku harus dipilih',
            'kategori_buku_id.exists' => 'Kategori buku tidak valid',
            'harga_per_bab.required' => 'Harga per bab harus diisi',
            'harga_per_bab.numeric' => 'Harga per bab harus berupa angka',
            'harga_per_bab.min' => 'Harga per bab minimal 0',
            'total_bab.required' => 'Total bab harus diisi',
            'total_bab.integer' => 'Total bab harus berupa angka',
            'total_bab.min' => 'Total bab minimal 1',
            'total_bab.max' => 'Total bab maksimal 50',
            'gambar_sampul.image' => 'File harus berupa gambar',
            'gambar_sampul.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar_sampul.max' => 'Ukuran gambar maksimal 2MB',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'bab_data.required' => 'Data bab harus diisi',
            'bab_data.array' => 'Data bab tidak valid',
            'bab_data.min' => 'Minimal harus ada 1 bab',
            'bab_data.*.judul_bab.required' => 'Judul bab harus diisi',
            'bab_data.*.judul_bab.max' => 'Judul bab maksimal 255 karakter',
            'bab_data.*.harga.numeric' => 'Harga bab harus berupa angka',
            'bab_data.*.harga.min' => 'Harga bab minimal 0',
            'bab_data.*.tingkat_kesulitan.required' => 'Tingkat kesulitan harus dipilih',
            'bab_data.*.tingkat_kesulitan.in' => 'Tingkat kesulitan tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Upload gambar sampul jika ada
            $gambarSampul = null;
            if ($request->hasFile('gambar_sampul')) {
                $file = $request->file('gambar_sampul');
                $filename = time() . '_' . str_replace(' ', '_', $validated['judul']) . '.' . $file->getClientOriginalExtension();
                $gambarSampul = $file->storeAs('sampul-buku', $filename, 'public');
            }

            // Buat buku kolaboratif
            $bukuKolaboratif = BukuKolaboratif::create([
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'kategori_buku_id' => $validated['kategori_buku_id'],
                'harga_per_bab' => $validated['harga_per_bab'],
                'total_bab' => $validated['total_bab'],
                'gambar_sampul' => $gambarSampul,
                'status' => $validated['status'],
            ]);

            // Buat bab-bab buku
            foreach ($validated['bab_data'] as $index => $babData) {
                BabBuku::create([
                    'buku_kolaboratif_id' => $bukuKolaboratif->id,
                    'nomor_bab' => $index + 1,
                    'judul_bab' => $babData['judul_bab'],
                    'deskripsi' => $babData['deskripsi'] ?? null,
                    'harga' => $babData['harga'] ?? $validated['harga_per_bab'],
                    'tingkat_kesulitan' => $babData['tingkat_kesulitan'],
                    'status' => 'tersedia',
                ]);
            }

            DB::commit();

            Log::info('Buku kolaboratif created by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuKolaboratif->id,
                'judul' => $bukuKolaboratif->judul,
                'total_bab' => $bukuKolaboratif->total_bab
            ]);

            return redirect()->route('admin.buku-kolaboratif.index')
                ->with('success', 'Buku kolaboratif berhasil dibuat dengan ' . count($validated['bab_data']) . ' bab.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if ($gambarSampul && Storage::disk('public')->exists($gambarSampul)) {
                Storage::disk('public')->delete($gambarSampul);
            }

            Log::error('Error creating buku kolaboratif', [
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat buku kolaboratif: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $bukuKolaboratif = BukuKolaboratif::with([
            'kategoriBuku',
            'babBuku' => function ($query) {
                $query->orderBy('nomor_bab');
            }
        ])->findOrFail($id);

        // Statistik bab
        $statistikBab = $bukuKolaboratif->getBabStatistics();

        // Statistik tingkat kesulitan
        $statistikKesulitan = $bukuKolaboratif->getKesulitanStatistics();

        return view('admin.buku-kolaboratif.show', compact('bukuKolaboratif', 'statistikBab', 'statistikKesulitan'));
    }

    public function edit($id)
    {
        $bukuKolaboratif = BukuKolaboratif::with([
            'babBuku' => function ($query) {
                $query->orderBy('nomor_bab');
            }
        ])->findOrFail($id);

        $kategoriBuku = KategoriBuku::where('status', true)->orderBy('nama')->get();

        return view('admin.buku-kolaboratif.edit', compact('bukuKolaboratif', 'kategoriBuku'));
    }

    public function update(Request $request, $id)
    {
        $bukuKolaboratif = BukuKolaboratif::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_buku_id' => 'required|exists:kategori_buku,id',
            'harga_per_bab' => 'required|numeric|min:0',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:aktif,nonaktif,selesai',
        ], [
            'judul.required' => 'Judul buku harus diisi',
            'deskripsi.required' => 'Deskripsi buku harus diisi',
            'kategori_buku_id.required' => 'Kategori buku harus dipilih',
            'kategori_buku_id.exists' => 'Kategori buku tidak valid',
            'harga_per_bab.required' => 'Harga per bab harus diisi',
            'harga_per_bab.numeric' => 'Harga per bab harus berupa angka',
            'harga_per_bab.min' => 'Harga per bab minimal 0',
            'gambar_sampul.image' => 'File harus berupa gambar',
            'gambar_sampul.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar_sampul.max' => 'Ukuran gambar maksimal 2MB',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Upload gambar sampul baru jika ada
            $gambarSampul = $bukuKolaboratif->gambar_sampul;
            if ($request->hasFile('gambar_sampul')) {
                // Hapus gambar lama
                if ($gambarSampul && Storage::disk('public')->exists($gambarSampul)) {
                    Storage::disk('public')->delete($gambarSampul);
                }

                // Upload gambar baru
                $file = $request->file('gambar_sampul');
                $filename = time() . '_' . str_replace(' ', '_', $validated['judul']) . '.' . $file->getClientOriginalExtension();
                $gambarSampul = $file->storeAs('sampul-buku', $filename, 'public');
            }

            // Update buku kolaboratif
            $bukuKolaboratif->update([
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'kategori_buku_id' => $validated['kategori_buku_id'],
                'harga_per_bab' => $validated['harga_per_bab'],
                'gambar_sampul' => $gambarSampul,
                'status' => $validated['status'],
            ]);

            DB::commit();

            Log::info('Buku kolaboratif updated by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuKolaboratif->id,
                'judul' => $bukuKolaboratif->judul
            ]);

            return redirect()->route('admin.buku-kolaboratif.index')
                ->with('success', 'Buku kolaboratif berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating buku kolaboratif', [
                'admin_id' => auth()->id(),
                'buku_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui buku kolaboratif: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $bukuKolaboratif = BukuKolaboratif::with('babBuku')->findOrFail($id);

            // Cek apakah buku bisa dihapus
            if (!$bukuKolaboratif->canBeDeleted()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus buku karena ada pesanan aktif atau bab yang sudah dikerjakan.');
            }

            DB::beginTransaction();

            // Hapus gambar sampul jika ada
            if ($bukuKolaboratif->gambar_sampul && Storage::disk('public')->exists($bukuKolaboratif->gambar_sampul)) {
                Storage::disk('public')->delete($bukuKolaboratif->gambar_sampul);
            }

            // Hapus buku (bab akan terhapus otomatis karena cascade)
            $bukuKolaboratif->delete();

            DB::commit();

            Log::info('Buku kolaboratif deleted by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $id,
                'judul' => $bukuKolaboratif->judul
            ]);

            return redirect()->route('admin.buku-kolaboratif.index')
                ->with('success', 'Buku kolaboratif berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting buku kolaboratif', [
                'admin_id' => auth()->id(),
                'buku_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                               ->with('error', 'Terjadi kesalahan saat menghapus buku kolaboratif: ' . $e->getMessage());
        }
    }

    // Kelola bab individual
    public function editBab($bukuId, $babId)
    {
        $bukuKolaboratif = BukuKolaboratif::findOrFail($bukuId);
        $babBuku = BabBuku::where('buku_kolaboratif_id', $bukuId)->findOrFail($babId);

        return view('admin.buku-kolaboratif.edit-bab', compact('bukuKolaboratif', 'babBuku'));
    }

    public function updateBab(Request $request, $bukuId, $babId)
    {
        $validated = $request->validate([
            'judul_bab' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            'status' => 'required|in:tersedia,dipesan,selesai',
        ], [
            'judul_bab.required' => 'Judul bab harus diisi',
            'judul_bab.max' => 'Judul bab maksimal 255 karakter',
            'harga.required' => 'Harga bab harus diisi',
            'harga.numeric' => 'Harga bab harus berupa angka',
            'harga.min' => 'Harga bab minimal 0',
            'tingkat_kesulitan.required' => 'Tingkat kesulitan harus dipilih',
            'tingkat_kesulitan.in' => 'Tingkat kesulitan tidak valid',
            'status.required' => 'Status bab harus dipilih',
            'status.in' => 'Status bab tidak valid',
        ]);

        try {
            $babBuku = BabBuku::where('buku_kolaboratif_id', $bukuId)->findOrFail($babId);

            $babBuku->update($validated);

            Log::info('Bab buku updated by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'bab_id' => $babId,
                'judul_bab' => $babBuku->judul_bab
            ]);

            return redirect()->route('admin.buku-kolaboratif.show', $bukuId)
                ->with('success', 'Bab berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating bab buku', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'bab_id' => $babId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui bab: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function tambahBab($bukuId)
    {
        $bukuKolaboratif = BukuKolaboratif::with('babBuku')->findOrFail($bukuId);
        
        // Cek apakah masih bisa menambah bab
        if ($bukuKolaboratif->babBuku->count() >= $bukuKolaboratif->total_bab) {
            return redirect()->back()
                ->with('error', 'Jumlah bab sudah mencapai maksimal (' . $bukuKolaboratif->total_bab . ' bab).');
        }

        return view('admin.buku-kolaboratif.tambah-bab', compact('bukuKolaboratif'));
    }

    public function storeBab(Request $request, $bukuId)
    {
        $bukuKolaboratif = BukuKolaboratif::with('babBuku')->findOrFail($bukuId);

        // Cek apakah masih bisa menambah bab
        if ($bukuKolaboratif->babBuku->count() >= $bukuKolaboratif->total_bab) {
            return redirect()->back()
                ->with('error', 'Jumlah bab sudah mencapai maksimal (' . $bukuKolaboratif->total_bab . ' bab).');
        }

        $validated = $request->validate([
            'judul_bab' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
        ], [
            'judul_bab.required' => 'Judul bab harus diisi',
            'judul_bab.max' => 'Judul bab maksimal 255 karakter',
            'harga.numeric' => 'Harga bab harus berupa angka',
            'harga.min' => 'Harga bab minimal 0',
            'tingkat_kesulitan.required' => 'Tingkat kesulitan harus dipilih',
            'tingkat_kesulitan.in' => 'Tingkat kesulitan tidak valid',
        ]);

        try {
            // Tentukan nomor bab berikutnya
            $nomorBabTerakhir = $bukuKolaboratif->babBuku->max('nomor_bab') ?? 0;
            $nomorBabBaru = $nomorBabTerakhir + 1;

            BabBuku::create([
                'buku_kolaboratif_id' => $bukuId,
                'nomor_bab' => $nomorBabBaru,
                'judul_bab' => $validated['judul_bab'],
                'deskripsi' => $validated['deskripsi'],
                'harga' => $validated['harga'] ?? $bukuKolaboratif->harga_per_bab,
                'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
                'status' => 'tersedia',
            ]);

            Log::info('Bab buku added by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'nomor_bab' => $nomorBabBaru,
                'judul_bab' => $validated['judul_bab']
            ]);

            return redirect()->route('admin.buku-kolaboratif.show', $bukuId)
                ->with('success', 'Bab baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error adding bab buku', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah bab: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function hapusBab($bukuId, $babId)
    {
        try {
            $babBuku = BabBuku::where('buku_kolaboratif_id', $bukuId)->findOrFail($babId);

            // Cek apakah bab sudah dipesan atau selesai
            if (in_array($babBuku->status, ['dipesan', 'selesai'])) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus bab yang sudah dipesan atau selesai.');
            }

            $babBuku->delete();

            Log::info('Bab buku deleted by admin', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'bab_id' => $babId,
                'judul_bab' => $babBuku->judul_bab
            ]);

            return redirect()->route('admin.buku-kolaboratif.show', $bukuId)
                ->with('success', 'Bab berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting bab buku', [
                'admin_id' => auth()->id(),
                'buku_id' => $bukuId,
                'bab_id' => $babId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus bab: ' . $e->getMessage());
        }
    }
}
