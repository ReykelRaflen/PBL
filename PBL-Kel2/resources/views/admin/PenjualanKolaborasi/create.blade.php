@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Tambah Laporan Pembayaran Baru</h1>
            <a href="{{ route('penjualanKolaborasi.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">Terdapat kesalahan dalam pengisian form:</h3>
                        <ul class="mt-2 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('penjualanKolaborasi.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Fields -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Informasi Pembayaran -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Informasi Pembayaran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nomor_invoice" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor Invoice <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_invoice" id="nomor_invoice" 
                                       value="{{ old('nomor_invoice') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                       placeholder="Contoh: INV-2024-001"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Format: INV-YYYY-XXX</p>
                            </div>
                            
                            <div>
                                <label for="jumlah_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" 
                                           value="{{ old('jumlah_pembayaran') }}"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                           placeholder="0"
                                           min="0" step="1000" required>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Masukkan nominal dalam Rupiah</p>
                            </div>
                            
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                       max="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                            
                            <div>
                                <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="status_pembayaran" id="status_pembayaran" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="menunggu_verifikasi" {{ old('status_pembayaran') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                                        Menunggu Verifikasi
                                    </option>
                                    <option value="sukses" {{ old('status_pembayaran') == 'sukses' ? 'selected' : '' }}>
                                        Sukses
                                    </option>
                                    <option value="tidak sesuai" {{ old('status_pembayaran') == 'tidak sesuai' ? 'selected' : '' }}>
                                        Tidak Sesuai
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Buku -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Informasi Buku & Bab
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Judul Buku <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul" id="judul" 
                                       value="{{ old('judul') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                       placeholder="Masukkan judul buku"
                                       required>
                            </div>
                            
                            <div>
                                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Penulis <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="penulis" id="penulis" 
                                       value="{{ old('penulis') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                       placeholder="Masukkan nama penulis"
                                       required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bab yang Dipesan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="bab" id="bab" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                          placeholder="Contoh: Bab 1 - Pengenalan, Bab 2 - Konsep Dasar"
                                          required>{{ old('bab') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Sebutkan bab-bab yang dipesan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesanan Buku -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Pesanan Buku (Opsional)
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="pesanan_buku_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih Pesanan Buku
                                </label>
                                <select name="pesanan_buku_id" id="pesanan_buku_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                    <option value="">Tidak terkait dengan pesanan</option>
                                    @if(isset($pesananBuku))
                                        @foreach($pesananBuku as $pesanan)
                                            <option value="{{ $pesanan->id }}" 
                                                                                                    data-judul="{{ $pesanan->babBuku->buku->judul ?? '' }}"
                                                    data-penulis="{{ $pesanan->pengguna->name ?? '' }}"
                                                    data-bab="{{ $pesanan->babBuku->judul ?? '' }}"
                                                    data-harga="{{ $pesanan->babBuku->harga ?? 0 }}"
                                                    {{ old('pesanan_buku_id') == $pesanan->id ? 'selected' : '' }}>
                                                {{ $pesanan->nomor_pesanan }} - {{ $pesanan->babBuku->buku->judul ?? 'Judul tidak tersedia' }}
                                                ({{ $pesanan->pengguna->name ?? 'Penulis tidak diketahui' }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih pesanan jika pembayaran terkait dengan pesanan tertentu</p>
                            </div>
                            
                            <!-- Auto-fill notification -->
                            <div id="autoFillNotification" class="hidden bg-blue-50 border border-blue-200 p-3 rounded-md">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-blue-700">Data buku akan diisi otomatis berdasarkan pesanan yang dipilih</span>
                                    <button type="button" id="autoFillBtn" class="ml-auto text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Isi Otomatis
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Admin -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Catatan Admin
                        </h3>
                        <div>
                            <label for="catatan_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan/Keterangan
                            </label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="Masukkan catatan admin jika diperlukan...">{{ old('catatan_admin') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan ini akan terlihat oleh penulis</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Upload Bukti Pembayaran -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Bukti Pembayaran
                        </h3>
                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                                   accept="image/*,.pdf,.doc,.docx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                Format: JPG, PNG, PDF, DOC, DOCX<br>
                                Maksimal: 5MB
                            </p>
                            
                            <!-- File Preview -->
                            <div id="filePreview" class="mt-4 hidden">
                                <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <span id="fileName" class="text-sm text-blue-700 font-medium"></span>
                                        <div id="fileSize" class="text-xs text-blue-600"></div>
                                    </div>
                                    <button type="button" onclick="clearFileInput()" class="ml-2 text-blue-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3 hidden">
                                    <img id="previewImg" src="" alt="Preview" class="w-full max-w-xs rounded-lg shadow-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <button type="button" onclick="generateInvoiceNumber()" 
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                </svg>
                                Generate Nomor Invoice
                            </button>
                            
                            <button type="button" onclick="setCurrentDate()" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Set Tanggal Hari Ini
                            </button>
                            
                            <button type="button" onclick="clearForm()" 
                                    class="w-full px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Form
                            </button>
                        </div>
                    </div>

                    <!-- Form Guidelines -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Panduan Pengisian
                        </h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Pastikan nomor invoice unik dan belum digunakan</li>
                            <li>• Upload bukti pembayaran yang jelas dan valid</li>
                            <li>• Pilih pesanan buku jika pembayaran terkait pesanan</li>
                            <li>• Status "Menunggu Verifikasi" untuk pembayaran baru</li>
                            <li>• Isi catatan admin jika ada informasi penting</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Simpan Data</h3>
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Laporan
                            </button>
                            
                            <button type="submit" name="save_and_continue" value="1"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Simpan & Tambah Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// File input preview and validation
document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            this.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        // Show file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.classList.remove('hidden');
        
        // Show image preview if it's an image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    } else {
        preview.classList.add('hidden');
        imagePreview.classList.add('hidden');
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function clearFileInput() {
    document.getElementById('bukti_pembayaran').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('imagePreview').classList.add('hidden');
}

// Generate invoice number
function generateInvoiceNumber() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    
    const invoiceNumber = `INV-${year}${month}${day}-${random}`;
    document.getElementById('nomor_invoice').value = invoiceNumber;
    
    // Show notification
    showNotification('Nomor invoice berhasil digenerate: ' + invoiceNumber, 'success');
}

// Set current date
function setCurrentDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
    showNotification('Tanggal berhasil diset ke hari ini', 'success');
}

// Clear form
function clearForm() {
    if (confirm('Yakin ingin menghapus semua data yang sudah diisi?')) {
        document.getElementById('createForm').reset();
        document.getElementById('filePreview').classList.add('hidden');
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('autoFillNotification').classList.add('hidden');
        showNotification('Form berhasil direset', 'info');
    }
}

// Auto-fill from pesanan buku
document.getElementById('pesanan_buku_id').addEventListener('change', function(e) {
    const selectedOption = e.target.selectedOptions[0];
    const notification = document.getElementById('autoFillNotification');
    
    if (selectedOption.value) {
        notification.classList.remove('hidden');
    } else {
        notification.classList.add('hidden');
    }
});

// Auto-fill button functionality
document.getElementById('autoFillBtn').addEventListener('click', function() {
    const select = document.getElementById('pesanan_buku_id');
    const selectedOption = select.selectedOptions[0];
    
    if (selectedOption.value) {
        const judul = selectedOption.getAttribute('data-judul');
        const penulis = selectedOption.getAttribute('data-penulis');
        const bab = selectedOption.getAttribute('data-bab');
        const harga = selectedOption.getAttribute('data-harga');
        
        if (judul) document.getElementById('judul').value = judul;
        if (penulis) document.getElementById('penulis').value = penulis;
        if (bab) document.getElementById('bab').value = bab;
        if (harga) document.getElementById('jumlah_pembayaran').value = harga;
        
        showNotification('Data berhasil diisi otomatis dari pesanan', 'success');
        document.getElementById('autoFillNotification').classList.add('hidden');
    }
});

// Format currency input
document.getElementById('jumlah_pembayaran').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});

// Form validation before submit
document.getElementById('createForm').addEventListener('submit', function(e) {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;
    
    // Reset previous error states
    requiredFields.forEach(field => {
        field.classList.remove('border-red-500');
    });
    
    // Check each required field
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        }
    });
    
    // Check file upload specifically
    const fileInput = document.getElementById('bukti_pembayaran');
    if (!fileInput.files.length) {
        isValid = false;
        fileInput.classList.add('border-red-500');
        if (!firstInvalidField) {
            firstInvalidField = fileInput;
        }
    }
    
    // Check invoice number uniqueness (you might want to add AJAX validation here)
    const invoiceNumber = document.getElementById('nomor_invoice').value;
    if (invoiceNumber && !invoiceNumber.match(/^INV-\d{4}-?\d*$/)) {
        showNotification('Format nomor invoice tidak valid. Gunakan format: INV-YYYY-XXX', 'error');
        document.getElementById('nomor_invoice').classList.add('border-red-500');
        isValid = false;
        if (!firstInvalidField) {
            firstInvalidField = document.getElementById('nomor_invoice');
        }
    }
    
    if (!isValid) {
        e.preventDefault();
        showNotification('Mohon lengkapi semua field yang wajib diisi dengan benar.', 'error');
        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return false;
    }
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    
    // Reset button after 10 seconds (fallback)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});

// Show notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    let bgColor, textColor, icon;
    switch (type) {
        case 'success':
            bgColor = 'bg-green-500';
            textColor = 'text-white';
            icon = `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>`;
            break;
        case 'error':
            bgColor = 'bg-red-500';
            textColor = 'text-white';
            icon = `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>`;
            break;
        default:
            bgColor = 'bg-blue-500';
            textColor = 'text-white';
            icon = `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`;
    }
    
    notification.className += ` ${bgColor} ${textColor}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">${icon}</div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button class="inline-flex text-white hover:text-gray-200 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    setCurrentDate();
    
    // Focus on first input
    document.getElementById('nomor_invoice').focus();
    
    // Add input event listeners for real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });
});

// Prevent form submission on Enter key (except in textarea)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'submit') {
        e.preventDefault();
    }
});
</script>
@endsection

