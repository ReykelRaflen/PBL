<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Naskah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NaskahController extends Controller
{
    public function index(Request $request)
    {
        $query = Naskah::with(['pengirim', 'reviewer']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pengirim', function ($subQ) use ($request) {
                        $subQ->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $naskah = $query->orderBy('batas_waktu', 'asc')->paginate(15);

        // Statistik untuk dashboard
        $stats = [
            'total' => Naskah::count(),
            'pending' => Naskah::where('status', 'pending')->count(),
            'sedang_direview' => Naskah::where('status', 'sedang_direview')->count(),
            'disetujui' => Naskah::where('status', 'disetujui')->count(),
            'ditolak' => Naskah::where('status', 'ditolak')->count(),
            'segera_berakhir' => Naskah::whereDate('batas_waktu', '<=', now()->addDays(3))->count()
        ];

        return view('admin.naskah.index', compact('naskah', 'stats'));
    }

    public function create()
    {
        // Ambil semua user untuk dropdown pengirim
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.naskah.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,txt,zip,rar|max:10240', // 10MB
            'batas_waktu' => 'required|date|after:today',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,sedang_direview,disetujui,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ], [
            'judul.required' => 'Judul naskah harus diisi',
            'file.required' => 'File naskah harus diupload',
            'file.mimes' => 'File harus berformat: pdf, doc, docx, txt, zip, atau rar',
            'file.max' => 'Ukuran file maksimal 10MB',
            'batas_waktu.required' => 'Batas waktu harus diisi',
            'batas_waktu.after' => 'Batas waktu harus setelah hari ini',
            'user_id.required' => 'Pengirim harus dipilih',
            'user_id.exists' => 'Pengirim tidak valid'
        ]);

        try {
            // Upload file
            $file = $request->file('file');
            $filename = Naskah::generateUniqueFilename($file->getClientOriginalName());
            $path = Naskah::getStoragePath();
            $filePath = $file->storeAs($path, $filename, 'public');

            // Buat naskah baru
            $naskah = Naskah::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath,
                'nama_file_asli' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'ukuran_file' => $file->getSize(),
                'status' => $request->status,
                'batas_waktu' => $request->batas_waktu,
                'catatan' => $request->catatan,
                'user_id' => $request->user_id,
                'reviewer_id' => in_array($request->status, ['disetujui', 'ditolak']) ? auth()->id() : null,
                'direview_pada' => in_array($request->status, ['disetujui', 'ditolak']) ? now() : null,
            ]);

            // Kirim notifikasi ke pengirim jika class notification ada
            $pengirim = User::find($request->user_id);
            if (class_exists('\App\Notifications\NaskahDisetujui') && $request->status === 'disetujui') {
                $pengirim->notify(new \App\Notifications\NaskahDisetujui($naskah));
            } elseif (class_exists('\App\Notifications\NaskahDitolak') && $request->status === 'ditolak') {
                $pengirim->notify(new \App\Notifications\NaskahDitolak($naskah));
            }

            return redirect()->route('admin.naskah.index')
                ->with('success', 'Naskah "' . $naskah->judul . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan naskah: ' . $e->getMessage());
        }
    }

    public function show(Naskah $naskah)
    {
        $naskah->load(['pengirim', 'reviewer', 'statusHistories.user']);

        // Tambahkan naskah terkait (naskah lain dari pengirim yang sama)
        $relatedNaskah = Naskah::with(['pengirim', 'reviewer'])
            ->where('user_id', $naskah->user_id)
            ->where('id', '!=', $naskah->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.naskah.show', compact('naskah', 'relatedNaskah'));
    }


    public function edit(Naskah $naskah)
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.naskah.edit', compact('naskah', 'users'));
    }

    public function update(Request $request, Naskah $naskah)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar|max:10240', // 10MB
            'batas_waktu' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,sedang_direview,disetujui,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ], [
            'judul.required' => 'Judul naskah harus diisi',
            'file.mimes' => 'File harus berformat: pdf, doc, docx, txt, zip, atau rar',
            'file.max' => 'Ukuran file maksimal 10MB',
            'batas_waktu.required' => 'Batas waktu harus diisi',
            'user_id.required' => 'Pengirim harus dipilih',
            'user_id.exists' => 'Pengirim tidak valid'
        ]);

        try {
            $updateData = [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'batas_waktu' => $request->batas_waktu,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ];

            // Jika ada file baru
            if ($request->hasFile('file')) {
                // Hapus file lama
                if (Storage::disk('public')->exists($naskah->file_path)) {
                    Storage::disk('public')->delete($naskah->file_path);
                }

                // Upload file baru
                $file = $request->file('file');
                $filename = Naskah::generateUniqueFilename($file->getClientOriginalName());
                $path = Naskah::getStoragePath();
                $filePath = $file->storeAs($path, $filename, 'public');

                $updateData['file_path'] = $filePath;
                $updateData['nama_file_asli'] = $file->getClientOriginalName();
                $updateData['mime_type'] = $file->getMimeType();
                $updateData['ukuran_file'] = $file->getSize();
            }

            // Set reviewer dan tanggal review jika status diubah ke disetujui/ditolak
            if (in_array($request->status, ['disetujui', 'ditolak']) && $naskah->status !== $request->status) {
                $updateData['reviewer_id'] = auth()->id();
                $updateData['direview_pada'] = now();
            }

            $naskah->update($updateData);

            return redirect()->route('admin.naskah.index')
                ->with('success', 'Naskah "' . $naskah->judul . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui naskah: ' . $e->getMessage());
        }
    }

    public function destroy(Naskah $naskah)
    {
        try {
            // Hapus file
            if (Storage::disk('public')->exists($naskah->file_path)) {
                Storage::disk('public')->delete($naskah->file_path);
            }

            $judul = $naskah->judul;
            $naskah->delete();

            return redirect()->route('admin.naskah.index')
                ->with('success', 'Naskah "' . $judul . '" berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus naskah: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $stats = [
            'total_naskah' => Naskah::count(),
            'naskah_pending' => Naskah::where('status', 'pending')->count(),
            'naskah_sedang_review' => Naskah::where('status', 'sedang_direview')->count(),
            'naskah_disetujui' => Naskah::where('status', 'disetujui')->count(),
            'naskah_ditolak' => Naskah::where('status', 'ditolak')->count(),
            'naskah_segera' => Naskah::whereDate('batas_waktu', '<=', now()->addDays(3))
                ->whereIn('status', ['pending', 'sedang_direview'])
                ->count(),
            'naskah_terlambat' => Naskah::where('batas_waktu', '<', now())
                ->whereIn('status', ['pending', 'sedang_direview'])
                ->count(),
        ];

        // Naskah terbaru
        $naskahTerbaru = Naskah::with(['pengirim'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Naskah yang segera berakhir
        $naskahSegera = Naskah::with(['pengirim'])
            ->whereDate('batas_waktu', '<=', now()->addDays(3))
            ->whereIn('status', ['pending', 'sedang_direview'])
            ->orderBy('batas_waktu', 'asc')
            ->limit(5)
            ->get();

        // Naskah yang terlambat
        $naskahTerlambat = Naskah::with(['pengirim'])
            ->where('batas_waktu', '<', now())
            ->whereIn('status', ['pending', 'sedang_direview'])
            ->orderBy('batas_waktu', 'asc')
            ->limit(5)
            ->get();

        // Statistik bulanan (12 bulan terakhir)
        $statistikBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $statistikBulanan[] = [
                'bulan' => $date->format('M Y'),
                'total' => Naskah::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'disetujui' => Naskah::whereMonth('direview_pada', $date->month)
                    ->whereYear('direview_pada', $date->year)
                    ->where('status', 'disetujui')
                    ->count(),
                'ditolak' => Naskah::whereMonth('direview_pada', $date->month)
                    ->whereYear('direview_pada', $date->year)
                    ->where('status', 'ditolak')
                    ->count(),
            ];
        }

        // Statistik per status (untuk chart pie)
        $statusStats = [
            ['label' => 'Pending', 'value' => $stats['naskah_pending'], 'color' => '#ffc107'],
            ['label' => 'Sedang Review', 'value' => $stats['naskah_sedang_review'], 'color' => '#17a2b8'],
            ['label' => 'Disetujui', 'value' => $stats['naskah_disetujui'], 'color' => '#28a745'],
            ['label' => 'Ditolak', 'value' => $stats['naskah_ditolak'], 'color' => '#dc3545'],
        ];

        // Top pengirim (user dengan naskah terbanyak)
        $topPengirim = User::withCount(['naskah'])
            ->where('role', 'user')
            ->having('naskah_count', '>', 0)
            ->orderBy('naskah_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.naskah.dashboard', compact(
            'stats',
            'naskahTerbaru',
            'naskahSegera',
            'naskahTerlambat',
            'statistikBulanan',
            'statusStats',
            'topPengirim'
        ));
    }

    public function setujui(Request $request, Naskah $naskah)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $naskah->update([
                'status' => 'disetujui',
                'catatan' => $request->catatan,
                'direview_pada' => now(),
                'reviewer_id' => auth()->id()
            ]);

            // Kirim notifikasi jika ada
            if ($naskah->pengirim) {
                // $naskah->pengirim->notify(new NaskahDisetujui($naskah));
            }

            return redirect()->route('admin.naskah.index')
                ->with('success', 'Naskah "' . $naskah->judul . '" berhasil disetujui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui naskah: ' . $e->getMessage());
        }
    }


    public function tolak(Request $request, Naskah $naskah)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000'
        ], [
            'catatan.required' => 'Alasan penolakan harus diisi'
        ]);

        try {
            $naskah->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'direview_pada' => now(),
                'reviewer_id' => auth()->id()
            ]);

            // Kirim notifikasi jika ada
            if ($naskah->pengirim) {
                // $naskah->pengirim->notify(new NaskahDitolak($naskah));
            }

            return redirect()->route('admin.naskah.index')
                ->with('success', 'Naskah "' . $naskah->judul . '" berhasil ditolak!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak naskah: ' . $e->getMessage());
        }
    }


    public function updateStatus(Request $request, Naskah $naskah)
    {
        $request->validate([
            'status' => 'required|in:pending,sedang_direview,disetujui,ditolak'
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        // Set reviewer dan tanggal review jika status diubah ke disetujui/ditolak
        if (in_array($request->status, ['disetujui', 'ditolak'])) {
            $updateData['direview_pada'] = now();
            $updateData['reviewer_id'] = auth()->id();
        }

        $naskah->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status naskah berhasil diupdate',
            'status' => $naskah->status_label
        ]);
    }

    public function download(Naskah $naskah)
    {
        if (!Storage::disk('public')->exists($naskah->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($naskah->file_path, $naskah->nama_file_asli);
    }

    // Perbaiki method preview di NaskahController
    public function preview(Naskah $naskah)
    {
        try {
            if (!Storage::disk('public')->exists($naskah->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            $extension = strtolower($naskah->getFileExtension());
            $url = Storage::disk('public')->url($naskah->file_path);

            // Pastikan URL dapat diakses
            $fullUrl = url($url);

            if ($extension === 'pdf') {
                return response()->json([
                    'success' => true,
                    'type' => 'pdf',
                    'url' => $fullUrl,
                    'filename' => $naskah->nama_file_asli
                ]);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return response()->json([
                    'success' => true,
                    'type' => 'image',
                    'url' => $fullUrl,
                    'filename' => $naskah->nama_file_asli
                ]);
            } elseif (in_array($extension, ['doc', 'docx'])) {
                return response()->json([
                    'success' => true,
                    'type' => 'document',
                    'url' => $fullUrl,
                    'filename' => $naskah->nama_file_asli,
                    'message' => 'File dokumen Word tidak dapat di-preview. Silakan download untuk melihat isi file.'
                ]);
            } elseif ($extension === 'txt') {
                // Untuk file txt, kita bisa membaca isinya
                $content = Storage::disk('public')->get($naskah->file_path);
                return response()->json([
                    'success' => true,
                    'type' => 'text',
                    'content' => $content,
                    'filename' => $naskah->nama_file_asli
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'type' => 'unsupported',
                    'url' => $fullUrl,
                    'filename' => $naskah->nama_file_asli,
                    'message' => 'Preview tidak tersedia untuk tipe file ini. Silakan download file.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statusCheck(Naskah $naskah)
    {
        return response()->json([
            'status' => $naskah->status,
            'status_text' => $naskah->getStatusText(),
            'updated_at' => $naskah->updated_at->toISOString()
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:setujui,tolak,hapus,update_status',
            'naskah_ids' => 'required|array',
            'naskah_ids.*' => 'exists:naskah,id',
            'catatan' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,sedang_direview,disetujui,ditolak'
        ]);

        $naskahIds = $request->naskah_ids;
        $action = $request->action;
        $catatan = $request->catatan;

        try {
            switch ($action) {
                case 'setujui':
                    Naskah::whereIn('id', $naskahIds)->update([
                        'status' => 'disetujui',
                        'catatan' => $catatan,
                        'direview_pada' => now(),
                        'reviewer_id' => auth()->id()
                    ]);
                    $message = count($naskahIds) . ' naskah berhasil disetujui!';
                    break;

                case 'tolak':
                    if (empty($catatan)) {
                        return redirect()->back()->with('error', 'Alasan penolakan harus diisi untuk menolak naskah!');
                    }
                    Naskah::whereIn('id', $naskahIds)->update([
                        'status' => 'ditolak',
                        'catatan' => $catatan,
                        'direview_pada' => now(),
                        'reviewer_id' => auth()->id()
                    ]);
                    $message = count($naskahIds) . ' naskah berhasil ditolak!';
                    break;

                case 'update_status':
                    if (empty($request->status)) {
                        return redirect()->back()->with('error', 'Status harus dipilih!');
                    }

                    $updateData = ['status' => $request->status];

                    if (in_array($request->status, ['disetujui', 'ditolak'])) {
                        $updateData['direview_pada'] = now();
                        $updateData['reviewer_id'] = auth()->id();
                        if ($catatan) {
                            $updateData['catatan'] = $catatan;
                        }
                    }

                    Naskah::whereIn('id', $naskahIds)->update($updateData);
                    $message = count($naskahIds) . ' naskah berhasil diupdate ke status ' . $request->status . '!';
                    break;

                case 'hapus':
                    $naskahList = Naskah::whereIn('id', $naskahIds)->get();
                    foreach ($naskahList as $naskah) {
                        if (Storage::disk('public')->exists($naskah->file_path)) {
                            Storage::disk('public')->delete($naskah->file_path);
                        }
                    }
                    Naskah::whereIn('id', $naskahIds)->delete();
                    $message = count($naskahIds) . ' naskah berhasil dihapus!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }

            return redirect()->route('admin.naskah.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }

    public function assignReviewer(Request $request, Naskah $naskah)
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

        $naskah->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'sedang_direview'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reviewer berhasil ditugaskan',
            'reviewer_name' => $reviewer->name
        ]);
    }

    public function bulkAssignReviewer(Request $request)
    {
        $request->validate([
            'naskah_ids' => 'required|array',
            'naskah_ids.*' => 'exists:naskah,id',
            'reviewer_id' => 'required|exists:users,id'
        ]);

        $reviewer = User::find($request->reviewer_id);

        if ($reviewer->role !== 'admin') {
            return redirect()->back()->with('error', 'Reviewer harus memiliki role admin');
        }

        $updated = Naskah::whereIn('id', $request->naskah_ids)
            ->update([
                'reviewer_id' => $request->reviewer_id,
                'status' => 'sedang_direview'
            ]);

        return redirect()->route('admin.naskah.index')
            ->with('success', $updated . ' naskah berhasil ditugaskan ke ' . $reviewer->name);
    }

    public function export(Request $request)
    {
        $query = Naskah::with(['pengirim', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $naskah = $query->orderBy('created_at', 'desc')->get();

        $filename = 'naskah_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($naskah) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID',
                'Judul',
                'Pengirim',
                'Email Pengirim',
                'Status',
                'Batas Waktu',
                'Tanggal Kirim',
                'Tanggal Review',
                'Reviewer',
                'Ukuran File',
                'Catatan'
            ]);

            // Data
            foreach ($naskah as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->judul,
                    $item->pengirim->name,
                    $item->pengirim->email,
                    $item->status_label,
                    $item->batas_waktu->format('d/m/Y'),
                    $item->created_at->format('d/m/Y H:i'),
                    $item->direview_pada ? $item->direview_pada->format('d/m/Y H:i') : '',
                    $item->reviewer ? $item->reviewer->name : '',
                    $item->ukuran_file_formatted,
                    $item->catatan ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Naskah::with(['pengirim', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $naskah = $query->orderBy('created_at', 'desc')->get();
        $stats = Naskah::getStatistik();

        return view('admin.naskah.print', compact('naskah', 'stats'));
    }

    public function getData(Request $request)
    {
        $query = Naskah::with(['pengirim', 'reviewer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
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
        $naskah = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $naskah->items(),
            'pagination' => [
                'current_page' => $naskah->currentPage(),
                'last_page' => $naskah->lastPage(),
                'per_page' => $naskah->perPage(),
                'total' => $naskah->total(),
                'from' => $naskah->firstItem(),
                'to' => $naskah->lastItem(),
            ]
        ]);
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', '7days');
        $data = Naskah::getChartData($period);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getNotificationsCount()
    {
        $count = [
            'pending' => Naskah::where('status', 'pending')->count(),
            'segera' => Naskah::segera()->count(),
            'terlambat' => Naskah::terlambat()->count(),
        ];

        return response()->json($count);
    }
}
