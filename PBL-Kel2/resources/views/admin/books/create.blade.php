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
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
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
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('penerbit') border-red-500 @enderror">
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
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('tahun_terbit') border-red-500 @enderror">
                                @error('tahun_terbit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    ISBN
                                </label>
                                <input type="text" 
                                       id="isbn" 
                                       name="isbn" 
                                       value="{{ old('isbn') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('isbn') border-red-500 @enderror">
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
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
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
                                    Harga <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="number" 
                                           id="harga" 
                                           name="harga" 
                                           value="{{ old('harga') }}"
                                           min="0"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('harga') border-red-500 @enderror" 
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
                                       required>
                                @error('stok')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Promo -->
                        <div>
                            <label for="promo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Promo
                            </label>
                            <select id="promo_id" 
                                    name="promo_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('promo_id') border-red-500 @enderror">
                                <option value="">Pilih Promo (Opsional)</option>
                                @foreach($promos as $promo)
                                    <option value="{{ $promo->id }}" 
                                            {{ old('promo_id') == $promo->id ? 'selected' : '' }}>
                                        {{ $promo->kode_promo }} - {{ $promo->keterangan }}
                                        ({{ $promo->tipe === 'Persentase' ? $promo->besaran.'%' : 'Rp '.number_format($promo->besaran) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('promo_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Cover -->
                        <div>
                            <label for="cover" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cover Buku
                            </label>
                            <input type="file" 
                                   id="cover" 
                                   name="cover" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('cover') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                            @error('cover')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Upload File Buku -->
                        <div>
                            <label for="file_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File E-book (PDF)
                            </label>
                            <input type="file" 
                                   id="file_buku" 
                                   name="file_buku" 
                                   accept=".pdf"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('file_buku') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: PDF. Maksimal 10MB.</p>
                            @error('file_buku')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Cover -->
                        <div id="cover-preview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Preview Cover
                            </label>
                            <div class="text-center">
                                <img id="cover-image" src="#" alt="Preview Cover" 
                                     class="w-48 h-64 object-cover rounded shadow mx-auto">
                            </div>
                        </div>

                        <!-- Info File PDF -->
                        <div id="pdf-info" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Info File PDF
                            </label>
                            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p id="pdf-name" class="text-sm font-medium text-blue-800 dark:text-blue-200"></p>
                                        <p id="pdf-size" class="text-xs text-blue-600 dark:text-blue-400"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Promo Otomatis (Info) -->
                        <div id="promo-info" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga Setelah Promo
                            </label>
                            <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-3">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            Harga Final: <span id="final-price">Rp 0</span>
                                        </p>
                                        <p class="text-xs text-green-600 dark:text-green-400">
                                            Hemat: <span id="discount-amount">Rp 0</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="text-red-500">*</span> Field wajib diisi
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="window.location.href='{{ route('admin.books.index') }}'"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Batal
                        </button>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Simpan Buku
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview cover image
    const coverInput = document.getElementById('cover');
    const coverPreview = document.getElementById('cover-preview');
    const coverImage = document.getElementById('cover-image');

    coverInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validasi ukuran file (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file cover terlalu besar. Maksimal 2MB.');
                this.value = '';
                coverPreview.classList.add('hidden');
                return;
            }

            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                this.value = '';
                coverPreview.classList.add('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                coverImage.src = e.target.result;
                coverPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            coverPreview.classList.add('hidden');
        }
    });

    // Info file PDF
    const pdfInput = document.getElementById('file_buku');
    const pdfInfo = document.getElementById('pdf-info');
    const pdfName = document.getElementById('pdf-name');
    const pdfSize = document.getElementById('pdf-size');

    pdfInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validasi ukuran file (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file PDF terlalu besar. Maksimal 10MB.');
                this.value = '';
                pdfInfo.classList.add('hidden');
                return;
            }

            // Validasi tipe file
            if (file.type !== 'application/pdf') {
                alert('File harus berupa PDF.');
                this.value = '';
                pdfInfo.classList.add('hidden');
                return;
            }

            pdfName.textContent = file.name;
            pdfSize.textContent = formatFileSize(file.size);
            pdfInfo.classList.remove('hidden');
        } else {
            pdfInfo.classList.add('hidden');
        }
    });

    // Kalkulasi harga promo
    const hargaInput = document.getElementById('harga');
    const promoSelect = document.getElementById('promo_id');
    const promoInfo = document.getElementById('promo-info');
    const finalPrice = document.getElementById('final-price');
    const discountAmount = document.getElementById('discount-amount');

    function calculatePromoPrice() {
        const harga = parseFloat(hargaInput.value) || 0;
        const selectedPromo = promoSelect.options[promoSelect.selectedIndex];
        
        if (harga > 0 && selectedPromo.value) {
            const promoText = selectedPromo.textContent;
            let finalHarga = harga;
            let hemat = 0;

            // Extract promo info dari text option
            if (promoText.includes('%')) {
                const percentage = parseFloat(promoText.match(/(\d+(?:\.\d+)?)%/)[1]);
                hemat = harga * (percentage / 100);
                finalHarga = harga - hemat;
            } else if (promoText.includes('Rp')) {
                const nominal = parseFloat(promoText.match(/Rp\s*([\d,]+)/)[1].replace(/,/g, ''));
                hemat = Math.min(nominal, harga);
                finalHarga = harga - hemat;
            }

            finalPrice.textContent = formatRupiah(finalHarga);
            discountAmount.textContent = formatRupiah(hemat);
            promoInfo.classList.remove('hidden');
        } else {
            promoInfo.classList.add('hidden');
        }
    }

    hargaInput.addEventListener('input', calculatePromoPrice);
    promoSelect.addEventListener('change', calculatePromoPrice);

    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const judulBuku = document.getElementById('judul_buku').value.trim();
        const penulis = document.getElementById('penulis').value.trim();
        const harga = document.getElementById('harga').value;
        const stok = document.getElementById('stok').value;

        if (!judulBuku || !penulis || !harga || !stok) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }

        if (parseFloat(harga) <= 0) {
            e.preventDefault();
            alert('Harga harus lebih dari 0.');
            return;
        }

        if (parseInt(stok) < 0) {
            e.preventDefault();
            alert('Stok tidak boleh negatif.');
            return;
        }
    });

    // Auto-generate ISBN (optional)
    const isbnInput = document.getElementById('isbn');
    const generateIsbnBtn = document.createElement('button');
    generateIsbnBtn.type = 'button';
    generateIsbnBtn.className = 'mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300';
    generateIsbnBtn.textContent = 'Generate ISBN';
    generateIsbnBtn.addEventListener('click', function() {
        const isbn = '978' + Math.random().toString().substr(2, 10);
        isbnInput.value = isbn;
    });
    isbnInput.parentNode.appendChild(generateIsbnBtn);
});
</script>
@endpush
@endsection
