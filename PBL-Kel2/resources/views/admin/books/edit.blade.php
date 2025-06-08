@extends('admin.layouts.app')

@section('title', 'Edit Buku')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Buku: {{ $book->judul_buku }}</h1>
        <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

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
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                   value="{{ old('judul_buku', $book->judul_buku) }}"
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
                                   value="{{ old('penulis', $book->penulis) }}"
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
                                   value="{{ old('penerbit', $book->penerbit) }}"
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
                                       value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
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
                                       value="{{ old('isbn', $book->isbn) }}"
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
                                            {{ old('kategori_id', $book->kategori_id) == $category->id ? 'selected' : '' }}>
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
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $book->deskripsi) }}</textarea>
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
                                           value="{{ old('harga', $book->harga) }}"
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
                                       value="{{ old('stok', $book->stok) }}"
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
                                            {{ old('promo_id', $book->promo_id) == $promo->id ? 'selected' : '' }}>
                                        {{ $promo->kode_promo }} - {{ $promo->keterangan }}
                                        ({{ $promo->tipe === 'Persentase' ? $promo->besaran.'%' : 'Rp '.number_format($promo->besaran) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('promo_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Saat Ini -->
                        @if($book->cover)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cover Saat Ini
                            </label>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Current Cover" 
                                     class="w-48 h-64 object-cover rounded shadow mx-auto">
                            </div>
                        </div>
                        @endif

                                              <!-- Upload Cover -->
                        <div>
                            <label for="cover" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ $book->cover ? 'Ganti Cover Buku' : 'Cover Buku' }}
                            </label>
                            <input type="file" 
                                   id="cover" 
                                   name="cover" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('cover') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                            @error('cover')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Preview Cover Baru -->
                            <div id="cover-preview" class="mt-3" style="display: none;">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview Cover Baru:</p>
                                <img id="cover-image" src="#" alt="Preview Cover" class="w-32 h-44 object-cover rounded shadow">
                            </div>
                        </div>

                        <!-- File E-book -->
                        <div>
                            <label for="file_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ $book->file_buku ? 'Ganti File E-book (PDF)' : 'File E-book (PDF)' }}
                            </label>
                            <input type="file" 
                                   id="file_buku" 
                                   name="file_buku" 
                                   accept=".pdf"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('file_buku') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: PDF. Maksimal 10MB. Kosongkan jika tidak ingin mengubah.</p>
                            @if($book->file_buku)
                                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">File saat ini: {{ basename($book->file_buku) }}</p>
                            @endif
                            @error('file_buku')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer dengan Tombol Simpan -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                            </svg>
                            Update Buku
                        </button>
                        <a href="{{ route('admin.books.show', $book->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Field wajib diisi
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputCover = document.getElementById('cover');
    const previewContainer = document.getElementById('cover-preview');
    const previewImage = document.getElementById('cover-image');

    inputCover.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Validasi harga promo
    const hargaInput = document.getElementById('harga');
    const promoSelect = document.getElementById('promo_id');
    
    function updateHargaInfo() {
        const harga = parseFloat(hargaInput.value) || 0;
        const promoId = promoSelect.value;
        
        // Hapus info sebelumnya
        const existingInfo = document.getElementById('harga-promo-info');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        if (harga > 0 && promoId) {
            const selectedOption = promoSelect.options[promoSelect.selectedIndex];
            const optionText = selectedOption.text;
            
            // Extract promo info dari text option
            if (optionText.includes('%')) {
                const percentage = parseFloat(optionText.match(/(\d+)%/)[1]);
                const hargaPromo = harga * (1 - percentage / 100);
                
                const infoDiv = document.createElement('div');
                infoDiv.id = 'harga-promo-info';
                infoDiv.className = 'mt-2 p-2 bg-green-100 dark:bg-green-800 rounded text-sm text-green-700 dark:text-green-300';
                infoDiv.innerHTML = `Harga setelah promo: Rp ${new Intl.NumberFormat('id-ID').format(hargaPromo)}`;
                
                hargaInput.parentNode.parentNode.appendChild(infoDiv);
            }
        }
    }
    
    hargaInput.addEventListener('input', updateHargaInfo);
    promoSelect.addEventListener('change', updateHargaInfo);
});
</script>
@endpush
@endsection
