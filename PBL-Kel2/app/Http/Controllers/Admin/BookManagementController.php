<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookManagementController extends Controller
{
    public function index(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $query = Book::with(['kategori', 'promo']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%')
                  ->orWhere('penerbit', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan promo
        if ($request->has('promo_id') && $request->promo_id) {
            $query->where('promo_id', $request->promo_id);
        }

        // Filter berdasarkan ketersediaan e-book
        if ($request->has('has_ebook') && $request->has_ebook) {
            $query->hasEbook();
        }

        // Filter berdasarkan stok
        if ($request->has('stock_status')) {
            if ($request->stock_status === 'available') {
                $query->available();
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stok', 0);
            }
        }

        $books = $query->latest()->paginate(10);

        // Ambil kategori untuk filter
        $categories = KategoriBuku::where('status', true)->get();
        
        // Ambil promo untuk filter
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.index', compact('books', 'categories', 'promos'));
    }

    public function create()
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $categories = KategoriBuku::where('status', true)->get();
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.create', compact('categories', 'promos'));
    }

    public function store(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        // Debug: Log semua data request
        Log::info('Store Request Data:', [
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_cover' => $request->hasFile('cover'),
            'has_file_buku' => $request->hasFile('file_buku')
        ]);

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku,isbn',
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'harga' => 'required|numeric|min:0',
            'harga_ebook' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'nullable|file|mimes:pdf|max:10240',
            'promo_id' => 'nullable|exists:promos,id',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Penulis wajib diisi.',
            'harga.required' => 'Harga buku wajib diisi.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'harga_ebook.min' => 'Harga e-book tidak boleh negatif.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'isbn.unique' => 'ISBN sudah digunakan.',
            'cover.image' => 'File cover harus berupa gambar.',
            'cover.mimes' => 'Cover harus berformat JPEG, PNG, JPG, atau GIF.',
            'cover.max' => 'Ukuran cover maksimal 2MB.',
            'file_buku.mimes' => 'File e-book harus berformat PDF.',
            'file_buku.max' => 'Ukuran file e-book maksimal 10MB.',
        ]);

        // Validasi khusus: jika ada file e-book, harus ada harga e-book
        if ($request->hasFile('file_buku') && empty($validated['harga_ebook'])) {
            return back()->withErrors(['harga_ebook' => 'Harga e-book wajib diisi jika mengunggah file e-book.'])
                        ->withInput();
        }

        try {
            DB::beginTransaction();

            // Handle file upload cover
            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                Log::info('Cover file info:', [
                    'original_name' => $coverFile->getClientOriginalName(),
                    'size' => $coverFile->getSize(),
                    'mime_type' => $coverFile->getMimeType(),
                    'is_valid' => $coverFile->isValid()
                ]);

                if ($coverFile->isValid()) {
                    $validated['cover'] = $coverFile->store('covers', 'public');
                    Log::info('Cover stored at: ' . $validated['cover']);
                } else {
                    throw new \Exception('File cover tidak valid');
                }
            }

            // Handle file upload e-book
            if ($request->hasFile('file_buku')) {
                $pdfFile = $request->file('file_buku');
                Log::info('PDF file info:', [
                    'original_name' => $pdfFile->getClientOriginalName(),
                    'size' => $pdfFile->getSize(),
                    'mime_type' => $pdfFile->getMimeType(),
                    'is_valid' => $pdfFile->isValid()
                ]);

                if ($pdfFile->isValid()) {
                    $validated['file_buku'] = $pdfFile->store('books', 'public');
                    Log::info('PDF stored at: ' . $validated['file_buku']);
                } else {
                    throw new \Exception('File PDF tidak valid');
                }
            }

            // Buat buku baru
            $book = Book::create($validated);

            // Hitung harga promo otomatis jika ada promo
            if (isset($validated['promo_id']) && $validated['promo_id']) {
                $book->calculatePromoPrice();
                $book->save();
            }

            DB::commit();

            return redirect()->route('admin.books.index')
                ->with('success', 'Buku berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($validated['cover'])) {
                Storage::disk('public')->delete($validated['cover']);
            }
            if (isset($validated['file_buku'])) {
                Storage::disk('public')->delete($validated['file_buku']);
            }

            Log::error('Error creating book: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan buku: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function show($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::with(['kategori', 'promo'])->findOrFail($id);
        return view('admin.books.show', compact('book'));
    }

    public function edit($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::with(['kategori', 'promo'])->findOrFail($id);
        $categories = KategoriBuku::where('status', true)->get();
        $promos = Promo::where('status', 'Aktif')->get();

        return view('admin.books.edit', compact('book', 'categories', 'promos'));
    }

    public function update(Request $request, $id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $book = Book::findOrFail($id);

        // Debug: Log semua data request
        Log::info('Update Request Data:', [
            'book_id' => $id,
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_cover' => $request->hasFile('cover'),
            'has_file_buku' => $request->hasFile('file_buku')
        ]);

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:buku,isbn,' . $book->id,
            'kategori_id' => 'nullable|exists:kategori_buku,id',
            'harga' => 'required|numeric|min:0',
            'harga_ebook' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'nullable|file|mimes:pdf|max:10240',
            'promo_id' => 'nullable|exists:promos,id',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'penulis.required' => 'Penulis wajib diisi.',
            'harga.required' => 'Harga buku wajib diisi.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'harga_ebook.min' => 'Harga e-book tidak boleh negatif.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'isbn.unique' => 'ISBN sudah digunakan.',
            'cover.image' => 'File cover harus berupa gambar.',
            'cover.mimes' => 'Cover harus berformat JPEG, PNG, JPG, atau GIF.',
            'cover.max' => 'Ukuran cover maksimal 2MB.',
            'file_buku.mimes' => 'File e-book harus berformat PDF.',
            'file_buku.max' => 'Ukuran file e-book maksimal 10MB.',
        ]);

        // Validasi khusus: jika ada file e-book (existing atau baru), harus ada harga e-book
        $hasEbookFile = $request->hasFile('file_buku') || $book->file_buku;
        if ($hasEbookFile && empty($validated['harga_ebook'])) {
            return back()->withErrors(['harga_ebook' => 'Harga e-book wajib diisi jika ada file e-book.'])
                        ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldCover = $book->cover;
            $oldFile = $book->file_buku;

            // Handle file upload cover
            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                Log::info('Update - Cover file info:', [
                    'original_name' => $coverFile->getClientOriginalName(),
                    'size' => $coverFile->getSize(),
                    'mime_type' => $coverFile->getMimeType(),
                    'is_valid' => $coverFile->isValid()
                ]);

                if ($coverFile->isValid()) {
                    $validated['cover'] = $coverFile->store('covers', 'public');
                    Log::info('Update - Cover stored at: ' . $validated['cover']);
                } else {
                    throw new \Exception('File cover tidak valid');
                }
            }

            // Handle file upload e-book
            if ($request->hasFile('file_buku')) {
                $pdfFile = $request->file('file_buku');
                Log::info('Update - PDF file info:', [
                    'original_name' => $pdfFile->getClientOriginalName(),
                    'size' => $pdfFile->getSize(),
                    'mime_type' => $pdfFile->getMimeType(),
                    'is_valid' => $pdfFile->isValid()
                ]);
                                if ($pdfFile->isValid()) {
                    $validated['file_buku'] = $pdfFile->store('books', 'public');
                    Log::info('Update - PDF stored at: ' . $validated['file_buku']);
                } else {
                    throw new \Exception('File PDF tidak valid');
                }
            }

            // Update buku
            $book->update($validated);

            // Hitung ulang harga promo
            if (isset($validated['promo_id']) && $validated['promo_id']) {
                $book->calculatePromoPrice();
                $book->save();
            }

            // Hapus file lama setelah update berhasil
            if ($request->hasFile('cover') && $oldCover) {
                Storage::disk('public')->delete($oldCover);
                Log::info('Old cover deleted: ' . $oldCover);
            }
            if ($request->hasFile('file_buku') && $oldFile) {
                Storage::disk('public')->delete($oldFile);
                Log::info('Old PDF deleted: ' . $oldFile);
            }

            DB::commit();

            return redirect()->route('admin.books.index')
                ->with('success', 'Buku berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file baru yang sudah diupload jika ada error
            if ($request->hasFile('cover') && isset($validated['cover'])) {
                Storage::disk('public')->delete($validated['cover']);
            }
            if ($request->hasFile('file_buku') && isset($validated['file_buku'])) {
                Storage::disk('public')->delete($validated['file_buku']);
            }

            Log::error('Error updating book: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'book_id' => $id
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui buku: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        try {
            $book = Book::findOrFail($id);

            DB::beginTransaction();

            // Simpan path file untuk dihapus
            $coverPath = $book->cover;
            $filePath = $book->file_buku;

            // Hapus buku dari database
            $book->delete();

            // Hapus file terkait
            if ($coverPath) {
                Storage::disk('public')->delete($coverPath);
                Log::info('Cover deleted on book deletion: ' . $coverPath);
            }
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
                Log::info('PDF deleted on book deletion: ' . $filePath);
            }

            DB::commit();

            return redirect()->route('admin.books.index')
                ->with('success', 'Buku berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting book: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'book_id' => $id
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus buku: ' . $e->getMessage()]);
        }
    }

    /**
     * Debug method untuk memeriksa konfigurasi upload
     */
    public function checkUploadConfig()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $config = [
            'php_version' => PHP_VERSION,
            'max_file_uploads' => ini_get('max_file_uploads'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'file_uploads' => ini_get('file_uploads') ? 'Enabled' : 'Disabled',
            'storage_path' => storage_path(),
            'public_path' => public_path(),
            'storage_public_exists' => Storage::disk('public')->exists(''),
            'storage_public_writable' => is_writable(storage_path('app/public')),
            'covers_dir_exists' => Storage::disk('public')->exists('covers'),
            'books_dir_exists' => Storage::disk('public')->exists('books'),
        ];

        // Coba buat direktori jika belum ada
        try {
            if (!Storage::disk('public')->exists('covers')) {
                Storage::disk('public')->makeDirectory('covers');
                $config['covers_dir_created'] = true;
            }
            if (!Storage::disk('public')->exists('books')) {
                Storage::disk('public')->makeDirectory('books');
                $config['books_dir_created'] = true;
            }
        } catch (\Exception $e) {
            $config['directory_creation_error'] = $e->getMessage();
        }

        return response()->json($config);
    }

    /**
     * Test upload method
     */
    public function testUpload(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Log::info('Test upload started', [
                'has_files' => $request->hasFile('test_file'),
                'all_files' => $request->allFiles()
            ]);

            if ($request->hasFile('test_file')) {
                $file = $request->file('test_file');
                
                $fileInfo = [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage()
                ];

                Log::info('Test file info:', $fileInfo);

                if ($file->isValid()) {
                    $path = $file->store('test', 'public');
                    
                    // Hapus file test setelah berhasil
                    Storage::disk('public')->delete($path);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Upload test berhasil',
                        'file_info' => $fileInfo,
                        'stored_path' => $path
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'File tidak valid',
                        'file_info' => $fileInfo
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada file yang diupload'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Test upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

