<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Buku;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $pesanan = Pesanan::with(['buku', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.pesanan.index', compact('pesanan'));
    }

    public function create(Request $request)
    {
        // Validasi parameter yang diterima dari URL
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'tipe_buku' => 'required|in:fisik,ebook',
            'quantity' => 'required|integer|min:1'
        ]);

        $buku = Book::with(['kategori', 'promo'])->findOrFail($request->buku_id);

        // Validasi stok untuk buku fisik
        if ($request->tipe_buku === 'fisik' && $buku->stok < $request->quantity) {
            return redirect()->route('books.show', $buku->id)
                ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $buku->stok);
        }

        return view('user.pesanan.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'quantity' => 'required|integer|min:1',
            'tipe_buku' => 'required|in:fisik,ebook',
            'kode_promo' => 'nullable|string',
            'alamat_pengiriman' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            // Ambil data buku
            $buku = Book::findOrFail($request->buku_id);

            // Tentukan harga berdasarkan tipe buku
            if ($request->tipe_buku === 'ebook') {
                $hargaSatuan = $buku->harga_ebook ?? $buku->harga;
            } else {
                $hargaSatuan = $buku->harga;
            }

            $subtotal = $hargaSatuan * $request->quantity;

            $diskon = 0;
            $kodePromo = null;

            // PENTING: Proses promo jika ada dan tidak kosong
            if (!empty($request->kode_promo) && trim($request->kode_promo) !== '') {
                $promo = Promo::where('kode_promo', trim($request->kode_promo))->first();

                if ($promo) {
                    if ($promo->isActive()) {
                        $diskon = $promo->calculateDiscount($subtotal);
                        $kodePromo = $promo->kode_promo;

                        // Update kuota terpakai
                        $promo->usePromo();
                    } else {
                        // Untuk view, redirect dengan error
                        if ($request->expectsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Kode promo tidak aktif atau sudah expired'
                            ], 400);
                        } else {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Kode promo tidak aktif atau sudah expired');
                        }
                    }
                } else {
                    // Untuk view, redirect dengan error
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Kode promo tidak ditemukan'
                        ], 400);
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Kode promo tidak ditemukan');
                    }
                }
            }

            $total = $subtotal - $diskon;

            // Validasi stok untuk buku fisik
            if ($request->tipe_buku === 'fisik' && $buku->stok < $request->quantity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $buku->stok
                    ], 400);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $buku->stok);
                }
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id,
                'order_number' => Pesanan::generateOrderNumber(),
                'tipe_buku' => $request->tipe_buku,
                'quantity' => $request->quantity,
                'harga_satuan' => $hargaSatuan,
                'subtotal' => $subtotal,
                'kode_promo' => $kodePromo,
                'diskon' => $diskon,
                'total' => $total,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'no_telepon' => $request->no_telepon,
                'catatan' => $request->catatan,
                'tanggal_pesanan' => now(),
            ]);

            // Update stok jika buku fisik
            if ($request->tipe_buku === 'fisik') {
                $buku->decrement('stok', $request->quantity);
            }

            // Response berdasarkan request type (untuk view dan AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $pesanan->load(['buku', 'promo']),
                    'message' => 'Pesanan berhasil dibuat' . ($diskon > 0 ? ' dengan diskon Rp ' . number_format($diskon, 0, ',', '.') : '')
                ]);
            } else {
                // Untuk view biasa, redirect ke halaman detail pesanan
                return redirect()->route('user.pesanan.show', $pesanan)
                    ->with('success', 'Pesanan berhasil dibuat' . ($diskon > 0 ? ' dengan diskon Rp ' . number_format($diskon, 0, ',', '.') : ''));
            }
        });
    }

    public function showCreateForm(Request $request)
    {
        $bukuId = $request->input('buku_id') ?? $request->buku_id;
        $tipeBuku = $request->input('tipe_buku', 'fisik'); // Default ke 'fisik'
        $quantity = $request->input('quantity', 1);

        // Validasi tipe buku
        if (!in_array($tipeBuku, ['fisik', 'ebook'])) {
            $tipeBuku = 'fisik';
        }

        $buku = Book::with(['kategori', 'promo'])->findOrFail($bukuId);

        return view('user.pesanan.create', compact('buku', 'tipeBuku', 'quantity'));
    }

    public function show(Pesanan $pesanan)
    {
        // Pastikan user hanya bisa melihat pesanannya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->load(['buku.kategori', 'pembayaran']);

        return view('user.pesanan.show', compact('pesanan'));
    }

    public function payment(Pesanan $pesanan)
    {
        // Pastikan user hanya bisa melihat pesanannya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // PENTING: Load fresh data dari database dengan relasi
        $pesanan = $pesanan->fresh(['buku', 'pembayaran']);
        
        // Atau gunakan cara ini untuk memastikan data terbaru
        $pesanan = Pesanan::with(['buku', 'pembayaran'])->find($pesanan->id);

        return view('user.pesanan.payment', compact('pesanan'));
    }

    public function uploadPayment(Request $request, Pesanan $pesanan)
    {
        // Pastikan user hanya bisa upload untuk pesanannya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Validasi
        $request->validate([
            'bank_pengirim' => 'required|string|max:50',
            'nama_pengirim' => 'required|string|max:100',
            'nomor_rekening_pengirim' => 'nullable|string|max:50',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Upload file
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            // Jika sudah ada pembayaran sebelumnya, hapus file lama
            if ($pesanan->pembayaran) {
                if ($pesanan->pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pesanan->pembayaran->bukti_pembayaran)) {
                    Storage::disk('public')->delete($pesanan->pembayaran->bukti_pembayaran);
                }
                $pesanan->pembayaran->delete();
            }

            // Buat record pembayaran baru
            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'invoice_number' => Pembayaran::generateInvoiceNumber(),
                'metode_pembayaran' => 'Transfer Bank',
                'bank_pengirim' => $request->bank_pengirim,
                'nama_pengirim' => $request->nama_pengirim,
                'nomor_rekening_pengirim' => $request->nomor_rekening_pengirim,
                'jumlah_transfer' => $pesanan->total,
                'bukti_pembayaran' => $buktiPath,
                'keterangan' => $request->keterangan,
                'status' => 'menunggu_verifikasi',
                'tanggal_pembayaran' => now()
            ]);

            // Update status pesanan
            $pesanan->update(['status' => 'menunggu_verifikasi']);

            DB::commit();

            return redirect()->route('user.pesanan.payment', $pesanan)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran. Silakan coba lagi.');
        }
    }

    public function downloadEbook(Pesanan $pesanan)
    {
        // Pastikan user hanya bisa download pesanannya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Pastikan pesanan adalah e-book dan sudah terverifikasi
        if (!$pesanan->canDownloadEbook()) {
            return back()->with('error', 'E-book belum dapat didownload. Pastikan pembayaran sudah terverifikasi.');
        }

        $buku = $pesanan->buku;

        // Debug: Log informasi file
        \Log::info('Attempting to download ebook', [
            'user_id' => Auth::id(),
            'pesanan_id' => $pesanan->id,
            'book_id' => $buku->id,
            'file_buku' => $buku->file_buku,
            'book_title' => $buku->judul_buku
        ]);

        // Cek apakah field file_buku ada dan tidak kosong
        if (!$buku->file_buku) {
            \Log::error('E-book file field is empty', [
                'book_id' => $buku->id,
                'book_title' => $buku->judul_buku
            ]);
            return back()->with('error', 'File e-book tidak tersedia untuk buku ini. Silakan hubungi customer service.');
        }

        // Daftar kemungkinan lokasi file
        $possiblePaths = [
            'ebooks/' . $buku->file_buku,
            'books/' . $buku->file_buku,
            'files/' . $buku->file_buku,
            'uploads/ebooks/' . $buku->file_buku,
            'uploads/books/' . $buku->file_buku,
            $buku->file_buku // path langsung
        ];

        $actualPath = null;
        $usedDisk = null;

        // Cek di disk public terlebih dahulu
        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                $actualPath = $path;
                $usedDisk = 'public';
                break;
            }
        }

        // Jika tidak ditemukan di public, cek di disk local
        if (!$actualPath) {
            foreach ($possiblePaths as $path) {
                if (Storage::disk('local')->exists($path)) {
                    $actualPath = $path;
                    $usedDisk = 'local';
                    break;
                }
            }
        }

        // Jika masih tidak ditemukan, cek di path absolut
        if (!$actualPath) {
            $rootPaths = [
                storage_path('app/public/' . $buku->file_buku),
                storage_path('app/' . $buku->file_buku),
                public_path('storage/' . $buku->file_buku),
                public_path('uploads/' . $buku->file_buku)
            ];

            foreach ($rootPaths as $path) {
                if (file_exists($path)) {
                    try {
                        $fileName = $this->generateEbookFileName($buku);
                        
                        \Log::info('E-book downloaded from absolute path', [
                            'user_id' => Auth::id(),
                            'pesanan_id' => $pesanan->id,
                            'file_path' => $path
                        ]);

                        return response()->download($path, $fileName);
                    } catch (\Exception $e) {
                        \Log::error('Failed to download from absolute path', [
                            'path' => $path,
                            'error' => $e->getMessage()
                        ]);
                        continue;
                    }
                }
            }
        }

        // Log semua path yang dicoba jika file tidak ditemukan
        if (!$actualPath) {
            \Log::error('E-book file not found in any location', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesanan->id,
                'book_id' => $buku->id,
                'file_buku' => $buku->file_buku,
                'searched_storage_paths' => $possiblePaths,
                'searched_absolute_paths' => $rootPaths ?? []
            ]);

            return back()->with('error', 'File e-book tidak ditemukan di server. Silakan hubungi customer service dengan menyebutkan nomor pesanan: ' . $pesanan->order_number);
        }

        try {
            // Generate nama file untuk download
            $fileName = $this->generateEbookFileName($buku);
            
            // Log successful download attempt
            \Log::info('E-book download successful', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesanan->id,
                'book_id' => $buku->id,
                'file_path' => $actualPath,
                'disk' => $usedDisk,
                'downloaded_at' => now()
            ]);

            // Download file
            return Storage::disk($usedDisk)->download($actualPath, $fileName);

        } catch (\Exception $e) {
            \Log::error('E-book download failed', [
                'user_id' => Auth::id(),
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage(),
                'file_path' => $actualPath,
                'disk' => $usedDisk
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi customer service.');
        }
    }

    /**
     * Generate nama file yang aman untuk download
     */
    private function generateEbookFileName($buku)
    {
        // Bersihkan nama file dari karakter yang tidak diinginkan
        $cleanTitle = preg_replace('/[^A-Za-z0-9\-_\s]/', '', $buku->judul_buku);
        $cleanTitle = preg_replace('/\s+/', '_', trim($cleanTitle));
        $cleanTitle = substr($cleanTitle, 0, 50); // Batasi panjang nama
        
        $cleanAuthor = preg_replace('/[^A-Za-z0-9\-_\s]/', '', $buku->penulis);
        $cleanAuthor = preg_replace('/\s+/', '_', trim($cleanAuthor));
        $cleanAuthor = substr($cleanAuthor, 0, 30); // Batasi panjang nama
        
        // Ambil ekstensi file asli
        $originalExtension = pathinfo($buku->file_buku, PATHINFO_EXTENSION);
        
        // Jika tidak ada ekstensi, gunakan pdf sebagai default
        if (empty($originalExtension)) {
            $originalExtension = 'pdf';
        }
        
        return $cleanTitle . '_by_' . $cleanAuthor . '.' . $originalExtension;
    }

    /**
     * Check ketersediaan e-book untuk AJAX request
     */
    public function checkEbookAvailability(Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            return response()->json(['available' => false, 'message' => 'Unauthorized']);
        }

        if ($pesanan->tipe_buku !== 'ebook') {
            return response()->json(['available' => false, 'message' => 'Not an e-book order']);
        }

        if (!$pesanan->pembayaran || $pesanan->pembayaran->status !== 'terverifikasi') {
            return response()->json(['available' => false, 'message' => 'Payment not verified']);
        }

        if (!$pesanan->buku->file_buku) {
            return response()->json(['available' => false, 'message' => 'E-book file not available']);
        }

        // Check if file exists
        $filePath = $pesanan->buku->file_buku;
        $possiblePaths = [
            'ebooks/' . $filePath,
            'books/' . $filePath,
            'files/' . $filePath,
            'uploads/ebooks/' . $filePath,
            'uploads/books/' . $filePath,
            $filePath
        ];

        $fileExists = false;
        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path) || Storage::disk('local')->exists($path)) {
                $fileExists = true;
                break;
            }
        }

        // Check absolute paths if not found in storage
        if (!$fileExists) {
            $absolutePaths = [
                storage_path('app/public/' . $filePath),
                storage_path('app/' . $filePath),
                public_path('storage/' . $filePath),
                public_path('uploads/' . $filePath)
            ];

            foreach ($absolutePaths as $path) {
                if (file_exists($path)) {
                    $fileExists = true;
                    break;
                }
            }
        }

        return response()->json([
            'available' => $fileExists,
            'message' => $fileExists ? 'E-book ready for download' : 'E-book file not found',
            'can_download' => $pesanan->canDownloadEbook() && $fileExists
        ]);
    }

    /**
     * Method untuk admin check file e-book (debugging)
     */
    public function checkEbookFile(Pesanan $pesanan)
    {
        // Hanya untuk admin atau development
        if (!auth()->user() || (!auth()->user()->hasRole('admin') && !config('app.debug'))) {
            abort(403);
        }

        $buku = $pesanan->buku;
        $result = [
            'book_id' => $buku->id,
            'book_title' => $buku->judul_buku,
            'file_buku' => $buku->file_buku,
            'file_exists' => false,
            'file_path' => null,
            'file_size' => null,
            'searched_paths' => []
        ];

        if (!$buku->file_buku) {
            $result['error'] = 'Field file_buku kosong';
            return response()->json($result);
        }

        $possiblePaths = [
            'ebooks/' . $buku->file_buku,
            'books/' . $buku->file_buku,
            'files/' . $buku->file_buku,
            'uploads/ebooks/' . $buku->file_buku,
            'uploads/books/' . $buku->file_buku,
            $buku->file_buku
        ];

        foreach ($possiblePaths as $path) {
            $publicExists = Storage::disk('public')->exists($path);
            $localExists = Storage::disk('local')->exists($path);
            
            $result['searched_paths'][] = [
                'path' => $path,
                'public_exists' => $publicExists,
                'local_exists' => $localExists
            ];

            if ($publicExists) {
                $result['file_exists'] = true;
                $result['file_path'] = $path;
                $result['disk'] = 'public';
                try {
                    $result['file_size'] = Storage::disk('public')->size($path);
                } catch (\Exception $e) {
                    $result['file_size'] = 'Error: ' . $e->getMessage();
                }
                break;
            } elseif ($localExists) {
                $result['file_exists'] = true;
                $result['file_path'] = $path;
                $result['disk'] = 'local';
                try {
                    $result['file_size'] = Storage::disk('local')->size($path);
                } catch (\Exception $e) {
                    $result['file_size'] = 'Error: ' . $e->getMessage();
                }
                break;
            }
        }

        // Check absolute paths
        if (!$result['file_exists']) {
            $absolutePaths = [
                storage_path('app/public/' . $buku->file_buku),
                storage_path('app/' . $buku->file_buku),
                public_path('storage/' . $buku->file_buku),
                public_path('uploads/' . $buku->file_buku)
            ];

            foreach ($absolutePaths as $path) {
                $exists = file_exists($path);
                $result['searched_paths'][] = [
                    'absolute_path' => $path,
                    'exists' => $exists,
                    'size' => $exists ? filesize($path) : null
                ];

                if ($exists) {
                    $result['file_exists'] = true;
                    $result['file_path'] = $path;
                    $result['disk'] = 'absolute';
                    $result['file_size'] = filesize($path);
                    break;
                }
            }
        }

        return response()->json($result);
    }

    public function cancel(Pesanan $pesanan)
    {
        // Pastikan user hanya bisa cancel pesanannya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa cancel jika status masih menunggu pembayaran
        if ($pesanan->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok jika buku fisik
            if ($pesanan->tipe_buku === 'fisik') {
                $pesanan->buku->increment('stok', $pesanan->quantity);
            }

            // Update status
            $pesanan->update(['status' => 'dibatalkan']);

            DB::commit();

            return redirect()->route('user.pesanan.index')
                ->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }

    /**
     * Validate promo code via AJAX
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $promo = Promo::where('kode_promo', trim($request->kode_promo))->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan'
            ]);
        }

        if (!$promo->isActive()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak aktif atau sudah expired'
            ]);
        }

        $diskon = $promo->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'message' => 'Kode promo valid',
            'diskon' => $diskon,
            'diskon_formatted' => 'Rp ' . number_format($diskon, 0, ',', '.'),
            'total_after_discount' => $request->subtotal - $diskon,
            'total_after_discount_formatted' => 'Rp ' . number_format($request->subtotal - $diskon, 0, ',', '.'),
            'promo_info' => [
                'nama' => $promo->nama_promo,
                'tipe' => $promo->tipe_diskon,
                'nilai' => $promo->nilai_diskon,
                'berlaku_sampai' => $promo->tanggal_berakhir ? $promo->tanggal_berakhir->format('d F Y') : null
            ]
        ]);
    }

    /**
     * Get order status for real-time updates
     */
    public function getOrderStatus(Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesanan->load(['pembayaran']);

        return response()->json([
            'order_status' => $pesanan->status,
            'payment_status' => $pesanan->pembayaran ? $pesanan->pembayaran->status : null,
            'can_download' => $pesanan->canDownloadEbook(),
            'can_upload_payment' => $pesanan->canUploadPayment(),
            'updated_at' => $pesanan->updated_at->toISOString()
        ]);
    }
}

