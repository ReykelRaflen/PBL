@extends('admin.layouts.app')

@section('title', 'Tambah Buku')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Buku Baru</h1>
        <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">Terdapat kesalahan pada form:</h3>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
            @csrf
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Judul Buku -->
                        <div>
                            <label for="judul_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Buku <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="judul_buku" 
                                   name="judul_buku" 
                                   value="{{ old('judul_buku') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('judul_buku') border-red-500 @enderror" 
                                   placeholder="Masukkan judul buku"
                                   required>
                            @error('judul_buku')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penulis -->
                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penulis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="penulis" 
                                   name="penulis" 
                                   value="{{ old('penulis') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('penulis') border-red-500 @enderror" 
                                   placeholder="Masukkan nama penulis"
                                   required>
                            @error('penulis')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penerbit -->
                        <div>
                            <label for="penerbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penerbit
                            </label>
                            <input type="text" 
                                   id="penerbit" 
                                   name="penerbit" 
                                   value="{{ old('penerbit') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('penerbit') border-red-500 @enderror"
                                   placeholder="Masukkan nama penerbit">
                            @error('penerbit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Terbit & ISBN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun Terbit
                                </label>
                                <input type="number" 
                                       id="tahun_terbit" 
                                       name="tahun_terbit" 
                                       value="{{ old('tahun_terbit') }}"
                                       min="1900" 
                                       max="{{ date('Y') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('tahun_terbit') border-red-500 @enderror"
                                       placeholder="{{ date('Y') }}">
                                @error('tahun_terbit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    ISBN
                                    <button type="button" id="generateIsbn" class="ml-2 text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Generate
                                    </button>
                                </label>
                                <input type="text" 
                                       id="isbn" 
                                       name="isbn" 
                                       value="{{ old('isbn') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('isbn') border-red-500 @enderror"
                                       placeholder="978-xxx-xxx-xxx-x">
                                @error('isbn')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori
                            </label>
                            <select id="kategori_id" 
                                    name="kategori_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('kategori_id') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Masukkan deskripsi buku">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- Harga & Stok -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Harga Buku Fisik <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="number" 
                                           id="harga" 
                                           name="harga" 
                                           value="{{ old('harga') }}"
                                           min="0"
                                           step="1000"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('harga') border-red-500 @enderror" 
                                           placeholder="0"
                                           required>
                                </div>
                                @error('harga')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="stok" 
                                       name="stok" 
                                       value="{{ old('stok', 0) }}"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('stok') border-red-500 @enderror" 
                                       placeholder="0"
                                       required>
                                @error('stok')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Harga E-book -->
                        <div>
                            <label for="harga_ebook" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga E-book
                                <span class="text-xs text-gray-500 dark:text-gray-400">(Wajib jika ada file e-book)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                                <input type="number" 
                                       id="harga_ebook" 
                                       name="harga_ebook" 
                                       value="{{ old('harga_ebook') }}"
                                       min="0"
                                       step="1000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('harga_ebook') border-red-500 @enderror" 
                                       placeholder="0">
                                                                   </div>
                            @error('harga_ebook')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Promo -->
                        <div>
                            <label for="promo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Promo
                                <span class="text-xs text-gray-500 dark:text-gray-400">(Opsional)</span>
                            </label>
                            <select id="promo_id" 
                                    name="promo_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('promo_id') border-red-500 @enderror">
                                <option value="">Pilih Promo (Opsional)</option>
                                @foreach($promos as $promo)
                                    <option value="{{ $promo->id }}" 
                                            data-type="{{ $promo->tipe }}"
                                            data-amount="{{ $promo->besaran }}"
                                            {{ old('promo_id') == $promo->id ? 'selected' : '' }}>
                                        {{ $promo->kode_promo }} - {{ $promo->keterangan }}
                                        ({{ $promo->tipe === 'Persentase' ? $promo->besaran.'%' : 'Rp '.number_format($promo->besaran) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('promo_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Harga Promo -->
                            <div id="promo-preview" class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hidden">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Preview Harga Promo:</strong>
                                </p>
                                <div id="promo-calculation" class="text-sm text-yellow-700 dark:text-yellow-300 mt-1"></div>
                            </div>
                        </div>

                        <!-- Upload Cover -->
                        <div>
                            <label for="cover" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cover Buku
                                <span class="text-xs text-gray-500 dark:text-gray-400">(JPG, JPEG, PNG - Max: 2MB)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="cover" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload cover</span>
                                            <input id="cover" name="cover" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                            @error('cover')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Preview Cover -->
                            <div id="cover-preview" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview Cover:</p>
                                <div class="relative inline-block">
                                    <img id="cover-image" src="#" alt="Preview Cover" class="w-32 h-44 object-cover rounded shadow">
                                    <button type="button" id="remove-cover" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File E-book -->
                        <div>
                            <label for="file_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File E-book (PDF)
                                <span class="text-xs text-gray-500 dark:text-gray-400">(PDF - Max: 10MB)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="file_buku" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload PDF</span>
                                            <input id="file_buku" name="file_buku" type="file" class="sr-only" accept=".pdf">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF hingga 10MB</p>
                                </div>
                            </div>
                            @error('file_buku')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Preview File PDF -->
                            <div id="pdf-preview" class="mt-3 hidden">
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <p id="pdf-name" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                                        <p id="pdf-size" class="text-xs text-gray-500 dark:text-gray-400"></p>
                                    </div>
                                    <button type="button" id="remove-pdf" class="ml-3 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Peringatan untuk E-book -->
                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-300">
                                        <p class="font-medium">Catatan E-book:</p>
                                        <ul class="mt-1 list-disc list-inside text-xs">
                                            <li>Jika mengunggah file e-book, harga e-book wajib diisi</li>
                                            <li>File akan dienkripsi untuk keamanan</li>
                                            <li>Pastikan file PDF tidak memiliki password</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer dengan tombol aksi -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Simpan Buku
                        </button>
                        
                        <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Batal
                        </a>
                    </div>
                    
                                        <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-red-500">*</span> Field wajib diisi
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('bookForm');
    const coverInput = document.getElementById('cover');
    const pdfInput = document.getElementById('file_buku');
    const hargaInput = document.getElementById('harga');
    const hargaEbookInput = document.getElementById('harga_ebook');
    const promoSelect = document.getElementById('promo_id');
    const generateIsbnBtn = document.getElementById('generateIsbn');
    const isbnInput = document.getElementById('isbn');

    // Preview elements
    const coverPreview = document.getElementById('cover-preview');
    const coverImage = document.getElementById('cover-image');
    const removeCoverBtn = document.getElementById('remove-cover');
    const pdfPreview = document.getElementById('pdf-preview');
    const pdfName = document.getElementById('pdf-name');
    const pdfSize = document.getElementById('pdf-size');
    const removePdfBtn = document.getElementById('remove-pdf');
    const promoPreviewDiv = document.getElementById('promo-preview');
    const promoCalculation = document.getElementById('promo-calculation');

    // Generate ISBN
    generateIsbnBtn.addEventListener('click', function() {
        const isbn = generateRandomISBN();
        isbnInput.value = isbn;
    });

    function generateRandomISBN() {
        const prefix = '978';
        const group = Math.floor(Math.random() * 9) + 1;
        const publisher = Math.floor(Math.random() * 900) + 100;
        const title = Math.floor(Math.random() * 900) + 100;
        
        // Calculate check digit
        const digits = (prefix + group + publisher + title).split('').map(Number);
        let sum = 0;
        for (let i = 0; i < digits.length; i++) {
            sum += digits[i] * (i % 2 === 0 ? 1 : 3);
        }
        const checkDigit = (10 - (sum % 10)) % 10;
        
        return `${prefix}-${group}-${publisher}-${title}-${checkDigit}`;
    }

    // Cover upload handling
    coverInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar (JPG, JPEG, PNG)');
                this.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 2MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                coverImage.src = e.target.result;
                coverPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove cover
    removeCoverBtn.addEventListener('click', function() {
        coverInput.value = '';
        coverPreview.classList.add('hidden');
    });

    // PDF upload handling
    pdfInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (file.type !== 'application/pdf') {
                alert('File harus berupa PDF');
                this.value = '';
                return;
            }

            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 10MB');
                this.value = '';
                return;
            }

            pdfName.textContent = file.name;
            pdfSize.textContent = formatFileSize(file.size);
            pdfPreview.classList.remove('hidden');

            // Make e-book price required if PDF is uploaded
            hargaEbookInput.required = true;
            const label = hargaEbookInput.parentElement.querySelector('label');
            label.innerHTML = 'Harga E-book <span class="text-red-500">*</span> <span class="text-xs text-gray-500 dark:text-gray-400">(Wajib karena ada file e-book)</span>';
        }
    });

    // Remove PDF
    removePdfBtn.addEventListener('click', function() {
        pdfInput.value = '';
        pdfPreview.classList.add('hidden');
        
        // Make e-book price optional again
        hargaEbookInput.required = false;
        const label = hargaEbookInput.parentElement.querySelector('label');
        label.innerHTML = 'Harga E-book <span class="text-xs text-gray-500 dark:text-gray-400">(Wajib jika ada file e-book)</span>';
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Promo calculation
    function calculatePromo() {
        const selectedOption = promoSelect.options[promoSelect.selectedIndex];
        const harga = parseFloat(hargaInput.value) || 0;
        const hargaEbook = parseFloat(hargaEbookInput.value) || 0;

        if (selectedOption.value && (harga > 0 || hargaEbook > 0)) {
            const promoType = selectedOption.dataset.type;
            const promoAmount = parseFloat(selectedOption.dataset.amount);

            let calculation = '<div class="space-y-1">';
            
            if (harga > 0) {
                let discountedPrice;
                if (promoType === 'Persentase') {
                    discountedPrice = harga - (harga * promoAmount / 100);
                } else {
                    discountedPrice = Math.max(0, harga - promoAmount);
                }
                
                calculation += `
                    <div>
                        <span class="font-medium">Buku Fisik:</span> 
                        Rp ${formatNumber(harga)} → Rp ${formatNumber(discountedPrice)}
                        <span class="text-green-600">(Hemat: Rp ${formatNumber(harga - discountedPrice)})</span>
                    </div>
                `;
            }

            if (hargaEbook > 0) {
                let discountedPriceEbook;
                if (promoType === 'Persentase') {
                    discountedPriceEbook = hargaEbook - (hargaEbook * promoAmount / 100);
                } else {
                    discountedPriceEbook = Math.max(0, hargaEbook - promoAmount);
                }
                
                calculation += `
                    <div>
                        <span class="font-medium">E-book:</span> 
                        Rp ${formatNumber(hargaEbook)} → Rp ${formatNumber(discountedPriceEbook)}
                        <span class="text-green-600">(Hemat: Rp ${formatNumber(hargaEbook - discountedPriceEbook)})</span>
                    </div>
                `;
            }

            calculation += '</div>';
            promoCalculation.innerHTML = calculation;
            promoPreviewDiv.classList.remove('hidden');
        } else {
            promoPreviewDiv.classList.add('hidden');
        }
    }

    // Event listeners for promo calculation
    promoSelect.addEventListener('change', calculatePromo);
    hargaInput.addEventListener('input', calculatePromo);
    hargaEbookInput.addEventListener('input', calculatePromo);

    // Format number function
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(Math.round(num));
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const errors = [];

        // Check if PDF is uploaded but no e-book price
        if (pdfInput.files.length > 0 && !hargaEbookInput.value) {
            errors.push('Harga e-book wajib diisi jika mengunggah file PDF');
            isValid = false;
        }

        // Check if e-book price is set but no PDF
        if (hargaEbookInput.value && pdfInput.files.length === 0) {
            if (!confirm('Anda mengisi harga e-book tapi tidak mengunggah file PDF. Lanjutkan?')) {
                isValid = false;
            }
        }

        // Validate harga vs harga_ebook
        const harga = parseFloat(hargaInput.value) || 0;
        const hargaEbook = parseFloat(hargaEbookInput.value) || 0;
        
        if (harga > 0 && hargaEbook > 0 && hargaEbook > harga) {
            errors.push('Harga e-book tidak boleh lebih mahal dari harga buku fisik');
            isValid = false;
        }

        // Show loading state
        if (isValid) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }

        if (!isValid) {
            e.preventDefault();
            if (errors.length > 0) {
                alert('Kesalahan:\n' + errors.join('\n'));
            }
        }
    });

    // Drag and drop functionality
    const dropZones = document.querySelectorAll('[class*="border-dashed"]');
    
    dropZones.forEach(dropZone => {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = this.querySelector('input[type="file"]');
                if (input) {
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            }
        });
    });

    // Real-time validation feedback
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                validateField(this);
            }
        });
    });

    function validateField(field) {
        const isValid = field.checkValidity();
        if (isValid) {
            field.classList.remove('border-red-500');
            field.classList.add('border-green-500');
        } else {
            field.classList.remove('border-green-500');
            field.classList.add('border-red-500');
        }
    }

    // Initialize promo calculation on page load
    calculatePromo();

    // Auto-format number inputs
    [hargaInput, hargaEbookInput].forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters except decimal point
            let value = this.value.replace(/[^\d]/g, '');
            this.value = value;
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            form.submit();
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            if (confirm('Yakin ingin membatalkan? Data yang belum disimpan akan hilang.')) {
                window.location.href = '{{ route("admin.books.index") }}';
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Custom file upload styling */
.file-upload-zone {
    transition: all 0.3s ease;
}

.file-upload-zone:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.dark .file-upload-zone:hover {
    background-color: rgba(59, 130, 246, 0.1);
}

/* Loading animation for form submission */
.form-loading {
    position: relative;
    pointer-events: none;
}

/* Preview image styling */
#cover-preview img {
    transition: transform 0.2s ease;
}

#cover-preview img:hover {
    transform: scale(1.05);
}

/* Form validation styling */
.border-red-500 {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.border-green-500 {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-1.lg\\:grid-cols-2 {
        gap: 1rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .space-x-3 > * + * {
        margin-left: 0.5rem;
    }
    
    .flex.space-x-3 {
        flex-direction: column;
        space: 0;
    }
    
    .flex.space-x-3 > * + * {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
}

/* Focus styles for accessibility */
input:focus,
textarea:focus,
select:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    ring-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Animation for notifications */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notification {
    animation: slideInRight 0.3s ease-out;
}

/* Spinner animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Hover effects */
.hover-scale:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

/* Button loading state */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* File preview styling */
.file-preview {
    border: 2px dashed #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.file-preview.dragover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.dark .file-preview.dragover {
    background-color: rgba(59, 130, 246, 0.1);
}
</style>
@endpush

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@endsection



