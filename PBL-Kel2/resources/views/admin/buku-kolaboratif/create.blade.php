@extends('admin.layouts.app')

@section('title', 'Tambah Buku Kolaboratif')

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.buku-kolaboratif.index') }}" 
           class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Buku Kolaboratif</h1>
            <p class="text-gray-600 dark:text-gray-400">Buat buku baru untuk penerbitan kolaborasi</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.buku-kolaboratif.store') }}" method="POST" enctype="multipart/form-data" id="bukuForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Utama -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Buku <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="judul" 
                                   name="judul" 
                                   value="{{ old('judul') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan judul buku"
                                   required>
                        </div>
                        
                        <div>
                            <label for="kategori_buku_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori_buku_id" 
                                    name="kategori_buku_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoriBuku as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_buku_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="harga_per_bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga per Bab (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="harga_per_bab" 
                                   name="harga_per_bab" 
                                   value="{{ old('harga_per_bab') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="50000"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        
                        <div>
                            <label for="total_bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Bab <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="total_bab" 
                                   name="total_bab" 
                                   value="{{ old('total_bab', 10) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="10"
                                   min="1"
                                   max="50"
                                   required
                                   onchange="generateBabFields()">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Deskripsi lengkap tentang buku ini..."
                                      required>{{ old('deskripsi') }}</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="gambar_sampul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gambar Sampul
                            </label>
                            <input type="file" 
                                   id="gambar_sampul" 
                                   name="gambar_sampul" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Format: JPG, PNG, GIF. Maksimal 2MB.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Bab -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daftar Bab</h3>
                    
                    <div id="babContainer">
                        <!-- Bab fields akan di-generate oleh JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Preview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Bab:</span>
                            <span id="previewTotalBab" class="text-sm font-medium text-gray-900 dark:text-white ml-2">0</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Harga per Bab:</span>
                            <span id="previewHargaPerBab" class="text-sm font-medium text-gray-900 dark:text-white ml-2">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Nilai Proyek:</span>
                            <span id="previewTotalNilai" class="text-sm font-medium text-blue-600 dark:text-blue-400 ml-2">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Buku
                        </button>
                        
                        <a href="{{ route('admin.buku-kolaboratif.index') }}" 
                           class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function generateBabFields() {
    const totalBab = parseInt(document.getElementById('total_bab').value) || 0;
    const container = document.getElementById('babContainer');
    const hargaPerBab = parseInt(document.getElementById('harga_per_bab').value) || 0;
    
    // Clear existing fields
    container.innerHTML = '';
    
    // Update preview
    updatePreview();
    
    if (totalBab > 0) {
        for (let i = 1; i <= totalBab; i++) {
            const babDiv = document.createElement('div');
            babDiv.className = 'border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4';
            babDiv.innerHTML = `
                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Bab ${i}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Judul Bab <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="bab_data[${i-1}][judul_bab]" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Judul bab ${i}"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tingkat Kesulitan <span class="text-red-500">*</span>
                        </label>
                        <select name="bab_data[${i-1}][tingkat_kesulitan]" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required>
                            <option value="mudah">Mudah</option>
                            <option value="sedang" selected>Sedang</option>
                            <option value="sulit">Sulit</option>
                        </select>
                    </div>
                    <div>
                                              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Harga Khusus (Rp)
                        </label>
                        <input type="number" 
                               name="bab_data[${i-1}][harga]" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="${hargaPerBab}"
                               min="0"
                               step="1000">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan untuk menggunakan harga default</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Deskripsi Bab
                        </label>
                        <textarea name="bab_data[${i-1}][deskripsi]" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Deskripsi singkat untuk bab ${i}"></textarea>
                    </div>
                </div>
            `;
            container.appendChild(babDiv);
        }
    }
}

function updatePreview() {
    const totalBab = parseInt(document.getElementById('total_bab').value) || 0;
    const hargaPerBab = parseInt(document.getElementById('harga_per_bab').value) || 0;
    const totalNilai = totalBab * hargaPerBab;
    
    document.getElementById('previewTotalBab').textContent = totalBab;
    document.getElementById('previewHargaPerBab').textContent = 'Rp ' + hargaPerBab.toLocaleString('id-ID');
    document.getElementById('previewTotalNilai').textContent = 'Rp ' + totalNilai.toLocaleString('id-ID');
}

// Event listeners
document.getElementById('total_bab').addEventListener('input', generateBabFields);
document.getElementById('harga_per_bab').addEventListener('input', function() {
    updatePreview();
    generateBabFields(); // Regenerate to update placeholder values
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    generateBabFields();
});
</script>
@endsection
