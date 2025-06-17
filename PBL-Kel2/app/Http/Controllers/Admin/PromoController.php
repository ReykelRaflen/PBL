<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_promo', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('tanggal_mulai', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('tanggal_selesai', '<=', $request->date_to);
        }

        $promos = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik
        $stats = [
            'total' => Promo::count(),
            'aktif' => Promo::where('status', 'Aktif')->count(),
            'nonaktif' => Promo::where('status', 'Tidak Aktif')->count(),
            'expired' => Promo::where('tanggal_selesai', '<', now())->count()
        ];

        return view('admin.promos.index', compact('promos', 'stats'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'kode_promo' => 'required|string|max:50|unique:promos,kode_promo',
        'keterangan' => 'required|string',
        'tipe' => 'required|in:Persentase,Nominal',
        'besaran' => 'required|numeric|min:0',
        'kuota' => 'nullable|integer|min:1',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'status' => 'required|in:Aktif,Tidak Aktif'
    ]);

    try {
        Promo::create([
            'kode_promo' => strtoupper($request->kode_promo),
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'besaran' => $request->besaran,
            'kuota' => $request->kuota,
            'kuota_terpakai' => 0,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status
        ]);

        // Gunakan route name yang benar sesuai dengan routes/web.php
        return redirect()->route('promos.index')
                       ->with('success', 'Promo berhasil ditambahkan');
                       
    } catch (\Exception $e) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function show(Promo $promo)
    {
        return view('admin.promos.show', compact('promo'));
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promos,kode_promo,' . $promo->id,
            'keterangan' => 'required|string',
            'tipe' => 'required|in:Persentase,Nominal',
            'besaran' => 'required|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:Aktif,Tidak Aktif'
        ]);

        try {
            $promo->update([
                'kode_promo' => strtoupper($request->kode_promo),
                'keterangan' => $request->keterangan,
                'tipe' => $request->tipe,
                'besaran' => $request->besaran,
                'kuota' => $request->kuota,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status
            ]);

            return redirect()->route('promos.index')
                           ->with('success', 'Promo berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Promo $promo)
    {
        try {
            // Cek apakah promo sedang digunakan
            if ($promo->kuota_terpakai > 0) {
                return redirect()->route('promos.index')
                               ->with('error', 'Promo tidak dapat dihapus karena sudah digunakan');
            }

            $promo->delete();

            return redirect()->route('promos.index')
                           ->with('success', 'Promo berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('promos.index')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Promo $promo)
    {
        try {
            $newStatus = $promo->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif';
            $promo->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Status promo berhasil diubah',
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:Aktif,Tidak Aktif,delete',
            'promo_ids' => 'required|array',
            'promo_ids.*' => 'exists:promos,id'
        ]);

        try {
            DB::beginTransaction();

            $promos = Promo::whereIn('id', $request->promo_ids);

            switch ($request->action) {
                case 'Aktif':
                    $promos->update(['status' => 'Aktif']);
                    $message = 'Promo berhasil diaktifkan';
                    break;
                case 'Tidak Aktif':
                    $promos->update(['status' => 'Tidak Aktif']);
                    $message = 'Promo berhasil dinonaktifkan';
                    break;
                case 'delete':
                    // Cek apakah ada promo yang sudah digunakan
                    $usedPromos = $promos->where('kuota_terpakai', '>', 0)->count();
                    if ($usedPromos > 0) {
                        throw new \Exception('Beberapa promo tidak dapat dihapus karena sudah digunakan');
                    }
                    $promos->delete();
                    $message = 'Promo berhasil dihapus';
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Promo::query();

            // Apply same filters as index
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('kode_promo', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }

            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('tanggal_mulai', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('tanggal_selesai', '<=', $request->date_to);
            }

            $promos = $query->orderBy('created_at', 'desc')->get();

            // Check if data exists
            if ($promos->isEmpty()) {
                return redirect()->route('admin.promos.index')
                               ->with('error', 'Tidak ada data untuk diekspor');
            }

            $filename = 'promo_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $callback = function () use ($promos) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Header
                fputcsv($file, [
                    'Kode Promo',
                    'Keterangan',
                    'Tipe',
                    'Besaran',
                    'Kuota',
                    'Kuota Terpakai',
                    'Kuota Tersisa',
                    'Tanggal Mulai',
                    'Tanggal Selesai',
                    'Status',
                    'Dibuat Pada'
                ]);

                // Data
                foreach ($promos as $promo) {
                    try {
                        $kuotaTersisa = $promo->kuota ? ($promo->kuota - $promo->kuota_terpakai) : 'Unlimited';
                        $besaranFormatted = $promo->tipe === 'Persentase' 
                            ? $promo->besaran . '%' 
                            : 'Rp ' . number_format($promo->besaran, 0, ',', '.');

                        fputcsv($file, [
                            $promo->kode_promo ?? '',
                            $promo->keterangan ?? '',
                            $promo->tipe ?? '',
                            $besaranFormatted,
                            $promo->kuota ?? 'Unlimited',
                            $promo->kuota_terpakai ?? 0,
                            $kuotaTersisa,
                            $promo->tanggal_mulai ? $promo->tanggal_mulai->format('Y-m-d') : '',
                            $promo->tanggal_selesai ? $promo->tanggal_selesai->format('Y-m-d') : '',
                            $promo->status ?? '',
                            $promo->created_at ? $promo->created_at->format('Y-m-d H:i:s') : ''
                        ]);
                    } catch (\Exception $e) {
                        // Skip problematic row and continue
                        continue;
                    }
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('admin.promos.index')
                           ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total' => Promo::count(),
                'aktif' => Promo::where('status', 'Aktif')->count(),
                'nonaktif' => Promo::where('status', 'Tidak Aktif')->count(),
                'expired' => Promo::where('tanggal_selesai', '<', now())->count(),
                'akan_expired' => Promo::where('tanggal_selesai', '>=', now())
                                      ->where('tanggal_selesai', '<=', now()->addDays(7))
                                      ->where('status', 'Aktif')
                                      ->count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    private function calculateDiscount($promo, $subtotal)
    {
        $diskon = 0;
        
        if ($promo->tipe === 'Persentase') {
            $diskon = ($subtotal * $promo->besaran) / 100;
        } else {
            $diskon = $promo->besaran;
        }

        return min($diskon, $subtotal);
    }
}
