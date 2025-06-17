<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['pesanan.user', 'pesanan.buku', 'verifiedBy']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('nama_pengirim', 'like', "%{$search}%")
                    ->orWhere('bank_pengirim', 'like', "%{$search}%")
                    ->orWhereHas('pesanan.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                        $pesananQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('tanggal_pembayaran', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('tanggal_pembayaran', '<=', $request->date_to);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->has('metode') && $request->metode != '') {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Filter berdasarkan bank
        if ($request->has('bank') && $request->bank != '') {
            $query->where('bank_pengirim', 'like', "%{$request->bank}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pembayarans = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => Pembayaran::count(),
            'menunggu' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi' => Pembayaran::where('status', 'terverifikasi')->count(),
            'ditolak' => Pembayaran::where('status', 'ditolak')->count(),
            'total_amount' => Pembayaran::where('status', 'terverifikasi')->sum('jumlah_transfer'),
            'pending_amount' => Pembayaran::where('status', 'menunggu_verifikasi')->sum('jumlah_transfer'),
        ];

        // Get unique banks and payment methods for filters
        $banks = Pembayaran::distinct()->pluck('bank_pengirim')->filter()->sort();
        $methods = Pembayaran::distinct()->pluck('metode_pembayaran')->filter()->sort();

        return view('admin.pembayaran.index', compact('pembayarans', 'stats', 'banks', 'methods'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pesanan.user', 'pesanan.buku', 'verifiedBy']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

  public function updateStatus(Request $request, Pembayaran $pembayaran)
{
    // Pastikan hanya POST request
    if (!$request->isMethod('POST')) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Method not allowed'], 405);
        }
        return redirect()->route('admin.pembayaran.show', $pembayaran);
    }

    try {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,ditolak',
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        
        // Update status pembayaran
        $pembayaran->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id()
        ]);

        // Update status pesanan berdasarkan status pembayaran
        if ($request->status === 'terverifikasi') {
            $pembayaran->pesanan->update([
                'status' => $pembayaran->pesanan->tipe_buku === 'ebook' ? 'selesai' : 'diproses'
            ]);
        } elseif ($request->status === 'ditolak') {
            $pembayaran->pesanan->update([
                'status' => 'menunggu_pembayaran'
            ]);
        }

        DB::commit();
        
        // Return JSON untuk AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui'
            ]);
        }
        
        // Redirect untuk form submission biasa
        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui');
        
    } catch (\Exception $e) {
        DB::rollback();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function quickApprove(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== 'menunggu_verifikasi') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak dapat disetujui'
            ]);
        }

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status' => 'terverifikasi',
                'catatan_admin' => 'Disetujui melalui quick action',
                'tanggal_verifikasi' => now(),
                'verified_by' => Auth::id()
            ]);

            $pembayaran->pesanan->update([
                'status' => $pembayaran->pesanan->tipe_buku === 'ebook' ? 'selesai' : 'diproses'
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function quickReject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ]);

        if ($pembayaran->status !== 'menunggu_verifikasi') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak dapat ditolak'
            ]);
        }

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan_admin,
                'tanggal_verifikasi' => now(),
                'verified_by' => Auth::id()
            ]);

            $pembayaran->pesanan->update([
                'status' => 'menunggu_pembayaran'
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:terverifikasi,ditolak',
            'pembayaran_ids' => 'required|array|min:1',
            'pembayaran_ids.*' => 'exists:pembayaran,id',
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        $pembayarans = Pembayaran::whereIn('id', $request->pembayaran_ids)
            ->where('status', 'menunggu_verifikasi')
            ->get();

        if ($pembayarans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pembayaran yang dapat diproses'
            ]);
        }

        DB::beginTransaction();
        try {
            $successCount = 0;
            foreach ($pembayarans as $pembayaran) {
                $pembayaran->update([
                    'status' => $request->action,
                    'catatan_admin' => $request->catatan_admin ?: 'Diproses melalui bulk action',
                    'tanggal_verifikasi' => now(),
                    'verified_by' => Auth::id()
                ]);

                // Update pesanan status
                if ($request->action === 'terverifikasi') {
                    $pembayaran->pesanan->update([
                        'status' => $pembayaran->pesanan->tipe_buku === 'ebook' ? 'selesai' : 'diproses'
                    ]);
                } else {
                    $pembayaran->pesanan->update([
                        'status' => 'menunggu_pembayaran'
                    ]);
                }

                $successCount++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "{$successCount} pembayaran berhasil diproses"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

  public function downloadBukti(Pembayaran $pembayaran)
{
    if (!$pembayaran->bukti_pembayaran || !Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
        return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan');
    }

    $fileName = 'Bukti_Pembayaran_' . $pembayaran->invoice_number . '.' . pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION);
    
    return Storage::disk('public')->download($pembayaran->bukti_pembayaran, $fileName);
}



    public function generateInvoice(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pesanan.user', 'pesanan.buku', 'verifiedBy']);
        return view('admin.pembayaran.invoice', compact('pembayaran'));
    }

    public function getStats()
    {
        $stats = [
            'total' => Pembayaran::count(),
            'menunggu' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi' => Pembayaran::where('status', 'terverifikasi')->count(),
            'ditolak' => Pembayaran::where('status', 'ditolak')->count(),
            'total_amount' => Pembayaran::where('status', 'terverifikasi')->sum('jumlah_transfer'),
            'pending_amount' => Pembayaran::where('status', 'menunggu_verifikasi')->sum('jumlah_transfer'),
        ];

        return response()->json($stats);
    }

   public function export(Request $request)
{
    try {
        $query = Pembayaran::with(['pesanan.user', 'pesanan.buku', 'verifiedBy']);

        // Apply same filters as index
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('nama_pengirim', 'like', "%{$search}%")
                    ->orWhere('bank_pengirim', 'like', "%{$search}%")
                    ->orWhereHas('pesanan.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                        $pesananQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('tanggal_pembayaran', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('tanggal_pembayaran', '<=', $request->date_to);
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->get();

        // Check if data exists
        if ($pembayarans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diekspor');
        }

        $filename = 'pembayaran_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function () use ($pembayarans) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, [
                'Invoice Number',
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Book Title',
                'Book Type',
                'Quantity',
                'Amount',
                'Payment Method',
                'Bank',
                'Sender Name',
                'Payment Date',
                'Verification Date',
                'Status',
                'Verified By',
                'Admin Notes'
            ]);

            // Data
            foreach ($pembayarans as $pembayaran) {
                try {
                    fputcsv($file, [
                        $pembayaran->invoice_number ?? '',
                        $pembayaran->pesanan->order_number ?? '',
                        $pembayaran->pesanan->user->name ?? '',
                        $pembayaran->pesanan->user->email ?? '',
                        $pembayaran->pesanan->buku->judul_buku ?? '',
                        $pembayaran->pesanan ? ucfirst($pembayaran->pesanan->tipe_buku) : '',
                        $pembayaran->pesanan->quantity ?? 0,
                        $pembayaran->jumlah_transfer ?? 0,
                        $pembayaran->metode_pembayaran ?? '',
                        $pembayaran->bank_pengirim ?? '',
                        $pembayaran->nama_pengirim ?? '',
                        $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->format('Y-m-d H:i:s') : '',
                        $pembayaran->tanggal_verifikasi ? $pembayaran->tanggal_verifikasi->format('Y-m-d H:i:s') : '',
                        $pembayaran->status ? ucfirst(str_replace('_', ' ', $pembayaran->status)) : '',
                        $pembayaran->verifiedBy ? $pembayaran->verifiedBy->name : '',
                        $pembayaran->catatan_admin ?? ''
                    ]);
                } catch (Exception $e) {
                    // Skip problematic row and continue
                    continue;
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
    }
}


    public function dashboard()
    {
        // Statistics for dashboard
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'today' => [
                'total' => Pembayaran::whereDate('created_at', $today)->count(),
                'verified' => Pembayaran::whereDate('tanggal_verifikasi', $today)->where('status', 'terverifikasi')->count(),
                'pending' => Pembayaran::whereDate('created_at', $today)->where('status', 'menunggu_verifikasi')->count(),
                'amount' => Pembayaran::whereDate('tanggal_verifikasi', $today)->where('status', 'terverifikasi')->sum('jumlah_transfer'),
            ],
            'week' => [
                'total' => Pembayaran::where('created_at', '>=', $thisWeek)->count(),
                'verified' => Pembayaran::where('tanggal_verifikasi', '>=', $thisWeek)->where('status', 'terverifikasi')->count(),
                'pending' => Pembayaran::where('created_at', '>=', $thisWeek)->where('status', 'menunggu_verifikasi')->count(),
                'amount' => Pembayaran::where('tanggal_verifikasi', '>=', $thisWeek)->where('status', 'terverifikasi')->sum('jumlah_transfer'),
            ],
            'month' => [
                'total' => Pembayaran::where('created_at', '>=', $thisMonth)->count(),
                'verified' => Pembayaran::where('tanggal_verifikasi', '>=', $thisMonth)->where('status', 'terverifikasi')->count(),
                'pending' => Pembayaran::where('created_at', '>=', $thisMonth)->where('status', 'menunggu_verifikasi')->count(),
                'amount' => Pembayaran::where('tanggal_verifikasi', '>=', $thisMonth)->where('status', 'terverifikasi')->sum('jumlah_transfer'),
            ],
            'all_time' => [
                'total' => Pembayaran::count(),
                'verified' => Pembayaran::where('status', 'terverifikasi')->count(),
                'pending' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
                'rejected' => Pembayaran::where('status', 'ditolak')->count(),
                'amount' => Pembayaran::where('status', 'terverifikasi')->sum('jumlah_transfer'),
            ]
        ];

        // Recent payments
        $recentPayments = Pembayaran::with(['pesanan.user', 'pesanan.buku'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pending payments that need attention
        $pendingPayments = Pembayaran::with(['pesanan.user', 'pesanan.buku'])
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        // Chart data for last 30 days
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'verified' => Pembayaran::whereDate('tanggal_verifikasi', $date)->where('status', 'terverifikasi')->count(),
                'pending' => Pembayaran::whereDate('created_at', $date)->where('status', 'menunggu_verifikasi')->count(),
                'rejected' => Pembayaran::whereDate('tanggal_verifikasi', $date)->where('status', 'ditolak')->count(),
                'amount' => Pembayaran::whereDate('tanggal_verifikasi', $date)->where('status', 'terverifikasi')->sum('jumlah_transfer'),
            ];
        }

        return response()->json([
            'stats' => $stats,
            'recent_payments' => $recentPayments,
            'pending_payments' => $pendingPayments,
            'chart_data' => $chartData
        ]);
    }

    public function markAsProcessed(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== 'terverifikasi') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum terverifikasi'
            ]);
        }

        if ($pembayaran->pesanan->tipe_buku === 'fisik') {
            $pembayaran->pesanan->update(['status' => 'dikirim']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan ditandai sebagai dikirim'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid untuk tipe pesanan ini'
        ]);
    }

    public function getPaymentHistory(Request $request, $userId)
    {
        $payments = Pembayaran::with(['pesanan.buku'])
            ->whereHas('pesanan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($payments);
    }

    public function validatePayment(Request $request, Pembayaran $pembayaran)
    {
        // Advanced validation logic
        $validationResults = [];

        // Check if amount matches order total
        if ($pembayaran->jumlah_transfer != $pembayaran->pesanan->total) {
            $validationResults[] = [
                'type' => 'warning',
                'message' => 'Jumlah transfer tidak sesuai dengan total pesanan',
                'expected' => $pembayaran->pesanan->total,
                'actual' => $pembayaran->jumlah_transfer,
                'difference' => abs($pembayaran->jumlah_transfer - $pembayaran->pesanan->total)
            ];
        }

        // Check payment date
        $orderDate = $pembayaran->pesanan->tanggal_pesanan;
        $paymentDate = $pembayaran->tanggal_pembayaran;
        $daysDiff = $orderDate->diffInDays($paymentDate);

        if ($daysDiff > 7) {
            $validationResults[] = [
                'type' => 'info',
                'message' => 'Pembayaran dilakukan ' . $daysDiff . ' hari setelah pesanan dibuat'
            ];
        }

        // Check for duplicate payments
        $duplicatePayments = Pembayaran::where('nama_pengirim', $pembayaran->nama_pengirim)
            ->where('jumlah_transfer', $pembayaran->jumlah_transfer)
            ->where('bank_pengirim', $pembayaran->bank_pengirim)
            ->where('id', '!=', $pembayaran->id)
            ->whereDate('tanggal_pembayaran', $pembayaran->tanggal_pembayaran->toDateString())
            ->exists();

        if ($duplicatePayments) {
            $validationResults[] = [
                'type' => 'error',
                'message' => 'Ditemukan pembayaran serupa pada tanggal yang sama'
            ];
        }

        return response()->json([
            'validation_results' => $validationResults,
            'recommendation' => $this->getRecommendation($validationResults)
        ]);
    }

    private function getRecommendation($validationResults)
    {
        $hasError = collect($validationResults)->contains('type', 'error');
        $hasWarning = collect($validationResults)->contains('type', 'warning');

        if ($hasError) {
            return [
                'action' => 'reject',
                'message' => 'Disarankan untuk menolak pembayaran ini karena ada masalah serius'
            ];
        } elseif ($hasWarning) {
            return [
                'action' => 'review',
                'message' => 'Perlu review lebih lanjut sebelum memverifikasi'
            ];
        } else {
            return [
                'action' => 'approve',
                'message' => 'Pembayaran dapat diverifikasi'
            ];
        }
    }
}

