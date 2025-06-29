<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index(Request $request)
    {
        $query = Design::with('pembuat');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pembuat
        if ($request->filled('pembuat')) {
            $query->where('pembuat_id', $request->pembuat);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $designs = $query->latest()->paginate(10);
        $users = User::all();
        $statusOptions = Design::getStatusOptions();

        // Tambahkan statistik
        $stats = Design::getStatistik();

        return view('admin.designs.index', compact('designs', 'users', 'statusOptions', 'stats'));
    }

    public function create()
    {
        $users = User::all();
        $statusOptions = Design::getStatusOptions();

        return view('admin.designs.create', compact('users', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pembuat_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,review,approved,rejected,completed',
            'due_date' => 'nullable|date',
            'catatan' => 'nullable|string'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('designs/covers', 'public');
        }

        Design::create($data);

        return redirect()->route('admin.designs.index')
            ->with('success', 'Desain berhasil ditambahkan!');
    }

    public function show(Design $design)
    {
        $design->load('pembuat', 'reviewer');

        // Load status histories jika ada
        if (method_exists($design, 'statusHistories')) {
            $design->load('statusHistories.user');
        }

        // Ambil desain terkait dari pembuat yang sama
        $relatedDesigns = Design::with(['pembuat'])
            ->where('pembuat_id', $design->pembuat_id)
            ->where('id', '!=', $design->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.designs.show', compact('design', 'relatedDesigns'));
    }

    public function edit(Design $design)
    {
        $users = User::all();
        $statusOptions = Design::getStatusOptions();

        return view('admin.designs.edit', compact('design', 'users', 'statusOptions'));
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pembuat_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,review,approved,rejected,completed',
            'due_date' => 'nullable|date',
            'catatan' => 'nullable|string'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($design->cover) {
                Storage::disk('public')->delete($design->cover);
            }
            $data['cover'] = $request->file('cover')->store('designs/covers', 'public');
        }

        // Handle remove cover
        if ($request->remove_cover == '1' && $design->cover) {
            Storage::disk('public')->delete($design->cover);
            $data['cover'] = null;
        }

        // Set reviewer dan tanggal review jika status diubah ke approved/rejected
        if (in_array($request->status, ['approved', 'rejected']) && $design->status !== $request->status) {
            $data['reviewer_id'] = auth()->id();
            $data['direview_pada'] = now();
        }

        $design->update($data);

        return redirect()->route('admin.designs.index')
            ->with('success', 'Desain berhasil diperbarui!');
    }

    public function destroy(Design $design)
    {
        try {
            if ($design->cover) {
                Storage::disk('public')->delete($design->cover);
            }

            $judul = $design->judul;
            $design->delete();

            return redirect()->route('admin.designs.index')
                ->with('success', 'Desain "' . $judul . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus desain: ' . $e->getMessage());
        }
    }

    public function setujui(Request $request, Design $design)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $design->update([
                'status' => 'approved',
                'catatan' => $request->catatan,
                'direview_pada' => now(),
                'reviewer_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Desain "' . $design->judul . '" berhasil disetujui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui desain: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolak(Request $request, Design $design)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000'
        ], [
            'catatan.required' => 'Alasan penolakan harus diisi'
        ]);

        try {
            $design->update([
                'status' => 'rejected',
                'catatan' => $request->catatan,
                'direview_pada' => now(),
                'reviewer_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Desain "' . $design->judul . '" berhasil ditolak!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak desain: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Design $design)
    {
        $request->validate([
            'status' => 'required|in:draft,review,approved,rejected,completed',
            'catatan' => 'nullable|string'
        ]);

        $updateData = [
            'status' => $request->status,
            'catatan' => $request->catatan
        ];

        // Set reviewer dan tanggal review jika status diubah ke approved/rejected
        if (in_array($request->status, ['approved', 'rejected'])) {
            $updateData['reviewer_id'] = auth()->id();
            $updateData['direview_pada'] = now();
        }

        $design->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status desain berhasil diperbarui!'
        ]);
    }

    public function assignReviewer(Request $request, Design $design)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id'
        ]);

        $reviewer = User::find($request->reviewer_id);

        if ($reviewer->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Reviewer harus memiliki role admin'
            ], 400);
        }

        $design->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'review'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reviewer berhasil ditugaskan',
            'reviewer_name' => $reviewer->name
        ]);
    }

    public function preview(Design $design)
    {
        try {
            if (!$design->cover || !Storage::disk('public')->exists($design->cover)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cover tidak ditemukan'
                ], 404);
            }

            $url = Storage::disk('public')->url($design->cover);
            $fullUrl = url($url);

            return response()->json([
                'success' => true,
                'type' => 'image',
                'url' => $fullUrl,
                'filename' => $design->judul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statusCheck(Design $design)
    {
        return response()->json([
            'status' => $design->status,
            'status_text' => $design->status_label,
            'updated_at' => $design->updated_at->toISOString()
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:setujui,tolak,hapus,update_status',
            'design_ids' => 'required|array',
            'design_ids.*' => 'exists:designs,id',
            'catatan' => 'nullable|string|max:1000',
            'status' => 'nullable|in:draft,review,approved,rejected,completed'
        ]);

        $designIds = $request->design_ids;
        $action = $request->action;
        $catatan = $request->catatan;

        try {
            switch ($action) {
                case 'setujui':
                    Design::whereIn('id', $designIds)->update([
                        'status' => 'approved',
                        'catatan' => $catatan,
                        'direview_pada' => now(),
                        'reviewer_id' => auth()->id()
                    ]);
                    $message = count($designIds) . ' desain berhasil disetujui!';
                    break;

                case 'tolak':
                    if (empty($catatan)) {
                        return redirect()->back()->with('error', 'Alasan penolakan harus diisi untuk menolak desain!');
                    }
                    Design::whereIn('id', $designIds)->update([
                        'status' => 'rejected',
                        'catatan' => $catatan,
                        'direview_pada' => now(),
                        'reviewer_id' => auth()->id()
                    ]);
                    $message = count($designIds) . ' desain berhasil ditolak!';
                    break;

                case 'update_status':
                    if (empty($request->status)) {
                        return redirect()->back()->with('error', 'Status harus dipilih!');
                    }

                    $updateData = ['status' => $request->status];

                    if (in_array($request->status, ['approved', 'rejected'])) {
                        $updateData['direview_pada'] = now();
                        $updateData['reviewer_id'] = auth()->id();
                        if ($catatan) {
                            $updateData['catatan'] = $catatan;
                        }
                    }

                    Design::whereIn('id', $designIds)->update($updateData);
                    $message = count($designIds) . ' desain berhasil diupdate ke status ' . $request->status . '!';
                    break;

                case 'hapus':
                    $designList = Design::whereIn('id', $designIds)->get();
                    foreach ($designList as $design) {
                        if ($design->cover && Storage::disk('public')->exists($design->cover)) {
                            Storage::disk('public')->delete($design->cover);
                        }
                    }
                    Design::whereIn('id', $designIds)->delete();
                    $message = count($designIds) . ' desain berhasil dihapus!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }

            return redirect()->route('admin.designs.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }

    public function bulkAssignReviewer(Request $request)
    {
        $request->validate([
            'design_ids' => 'required|array',
            'design_ids.*' => 'exists:designs,id',
            'reviewer_id' => 'required|exists:users,id'
        ]);

        $reviewer = User::find($request->reviewer_id);

        if ($reviewer->role !== 'admin') {
            return redirect()->back()->with('error', 'Reviewer harus memiliki role admin');
        }

        $updated = Design::whereIn('id', $request->design_ids)
            ->update([
                'reviewer_id' => $request->reviewer_id,
                'status' => 'review'
            ]);

        return redirect()->route('admin.designs.index')
            ->with('success', $updated . ' desain berhasil ditugaskan ke ' . $reviewer->name);
    }

    public function export(Request $request)
    {
        $query = Design::with(['pembuat', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pembuat')) {
            $query->where('pembuat_id', $request->pembuat);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $designs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'designs_export_' . date('Y-m-d_H-i-s') . '.csv';

               $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($designs) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID',
                'Judul',
                'Pembuat',
                'Email Pembuat',
                'Status',
                'Due Date',
                'Tanggal Dibuat',
                'Tanggal Review',
                'Reviewer',
                'Catatan'
            ]);

            // Data
            foreach ($designs as $design) {
                fputcsv($file, [
                    $design->id,
                    $design->judul,
                    $design->pembuat->name,
                    $design->pembuat->email,
                    $design->status_label,
                    $design->due_date ? $design->due_date->format('d/m/Y') : '',
                    $design->created_at->format('d/m/Y H:i'),
                    $design->direview_pada ? $design->direview_pada->format('d/m/Y H:i') : '',
                    $design->reviewer ? $design->reviewer->name : '',
                    $design->catatan ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Design::with(['pembuat', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pembuat')) {
            $query->where('pembuat_id', $request->pembuat);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $designs = $query->orderBy('created_at', 'desc')->get();

        // Generate stats
        $stats = [
            'total' => $designs->count(),
            'draft' => $designs->where('status', 'draft')->count(),
            'review' => $designs->where('status', 'review')->count(),
            'approved' => $designs->where('status', 'approved')->count(),
            'rejected' => $designs->where('status', 'rejected')->count(),
            'completed' => $designs->where('status', 'completed')->count(),
        ];

        return view('admin.designs.print', compact('designs', 'stats'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query pencarian tidak boleh kosong'
            ]);
        }

        $designs = Design::with(['pembuat'])
            ->where(function ($q) use ($query) {
                $q->where('judul', 'like', '%' . $query . '%')
                    ->orWhere('deskripsi', 'like', '%' . $query . '%')
                    ->orWhereHas('pembuat', function ($subQ) use ($query) {
                        $subQ->where('name', 'like', '%' . $query . '%');
                    });
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $designs->map(function ($design) {
                return [
                    'id' => $design->id,
                    'judul' => $design->judul,
                    'pembuat' => $design->pembuat->name,
                    'status' => $design->status_label,
                    'url' => route('admin.designs.show', $design)
                ];
            })
        ]);
    }

    public function getData(Request $request)
    {
        $query = Design::with(['pembuat', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pembuat')) {
            $query->where('pembuat_id', $request->pembuat);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $designs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $designs->items(),
            'pagination' => [
                'current_page' => $designs->currentPage(),
                'last_page' => $designs->lastPage(),
                'per_page' => $designs->perPage(),
                'total' => $designs->total(),
                'from' => $designs->firstItem(),
                'to' => $designs->lastItem(),
            ]
        ]);
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', '7days');
        $data = [];

        if ($period === '7days') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    'total' => Design::whereDate('created_at', $date)->count(),
                    'approved' => Design::whereDate('direview_pada', $date)->where('status', 'approved')->count(),
                    'rejected' => Design::whereDate('direview_pada', $date)->where('status', 'rejected')->count(),
                    'completed' => Design::whereDate('updated_at', $date)->where('status', 'completed')->count(),
                ];
            }
        } elseif ($period === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    'total' => Design::whereDate('created_at', $date)->count(),
                    'approved' => Design::whereDate('direview_pada', $date)->where('status', 'approved')->count(),
                    'rejected' => Design::whereDate('direview_pada', $date)->where('status', 'rejected')->count(),
                    'completed' => Design::whereDate('updated_at', $date)->where('status', 'completed')->count(),
                ];
            }
        } elseif ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $data[] = [
                    'date' => $date->format('Y-m'),
                    'label' => $date->format('M Y'),
                    'total' => Design::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                    'approved' => Design::whereMonth('direview_pada', $date->month)
                        ->whereYear('direview_pada', $date->year)
                        ->where('status', 'approved')
                        ->count(),
                    'rejected' => Design::whereMonth('direview_pada', $date->month)
                        ->whereYear('direview_pada', $date->year)
                        ->where('status', 'rejected')
                        ->count(),
                    'completed' => Design::whereMonth('updated_at', $date->month)
                        ->whereYear('updated_at', $date->year)
                        ->where('status', 'completed')
                        ->count(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getNotificationsCount()
    {
        $count = [
            'review' => Design::where('status', 'review')->count(),
            'urgent' => Design::where('due_date', '<=', now()->addDays(3))
                ->whereIn('status', ['draft', 'review'])
                ->count(),
            'overdue' => Design::where('due_date', '<', now())
                ->whereIn('status', ['draft', 'review'])
                ->count(),
        ];

        return response()->json($count);
    }

    public function dashboard()
    {
        $stats = [
            'total_designs' => Design::count(),
            'design_draft' => Design::where('status', 'draft')->count(),
            'design_review' => Design::where('status', 'review')->count(),
            'design_approved' => Design::where('status', 'approved')->count(),
            'design_rejected' => Design::where('status', 'rejected')->count(),
            'design_completed' => Design::where('status', 'completed')->count(),
            'design_urgent' => Design::whereDate('due_date', '<=', now()->addDays(3))
                ->whereIn('status', ['draft', 'review'])
                ->count(),
            'design_overdue' => Design::where('due_date', '<', now())
                ->whereIn('status', ['draft', 'review'])
                ->count(),
        ];

        // Desain terbaru
        $designTerbaru = Design::with(['pembuat'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Desain yang urgent
        $designUrgent = Design::with(['pembuat'])
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->whereIn('status', ['draft', 'review'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Desain yang overdue
        $designOverdue = Design::with(['pembuat'])
            ->where('due_date', '<', now())
            ->whereIn('status', ['draft', 'review'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Statistik bulanan (12 bulan terakhir)
        $statistikBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $statistikBulanan[] = [
                'bulan' => $date->format('M Y'),
                'total' => Design::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'approved' => Design::whereMonth('direview_pada', $date->month)
                    ->whereYear('direview_pada', $date->year)
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => Design::whereMonth('direview_pada', $date->month)
                    ->whereYear('direview_pada', $date->year)
                    ->where('status', 'rejected')
                    ->count(),
                'completed' => Design::whereMonth('updated_at', $date->month)
                    ->whereYear('updated_at', $date->year)
                    ->where('status', 'completed')
                    ->count(),
            ];
        }

        // Statistik per status (untuk chart pie)
        $statusStats = [
            ['label' => 'Draft', 'value' => $stats['design_draft'], 'color' => '#6c757d'],
            ['label' => 'Review', 'value' => $stats['design_review'], 'color' => '#ffc107'],
            ['label' => 'Approved', 'value' => $stats['design_approved'], 'color' => '#28a745'],
            ['label' => 'Rejected', 'value' => $stats['design_rejected'], 'color' => '#dc3545'],
            ['label' => 'Completed', 'value' => $stats['design_completed'], 'color' => '#17a2b8'],
        ];

        // Top pembuat (user dengan desain terbanyak)
        $topPembuat = User::withCount(['designs'])
            ->having('designs_count', '>', 0)
            ->orderBy('designs_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.designs.dashboard', compact(
            'stats',
            'designTerbaru',
            'designUrgent',
            'designOverdue',
            'statistikBulanan',
            'statusStats',
            'topPembuat'
        ));
    }
}
