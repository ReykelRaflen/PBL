@extends('admin.layouts.app')

@section('title', 'Edit Buku Kolaboratif')

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.buku-kolaboratif.show', $bukuKolaboratif->id) }}" 
           class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Buku Kolaboratif</h1>
            <p class="text-gray-600 dark:text-gray-400">Edit informasi buku "{{ $bukuKolaboratif->judul }}"</p>
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

    <form action="{{ route('admin.buku-kolaboratif.update', $bukuKolaboratif->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                                   value="{{ old('judul', $bukuKolaboratif->judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
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
                                    <option value="{{ $kategori->id }}" 
                                            {{ old('kategori_buku_id', $bukuKolaboratif->kategori_buku_id) == $kategori->id ? 'selected' : '' }}>
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
                                <option value="aktif" {{ old('status', $bukuKolaboratif->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $bukuKolaboratif->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="selesai" {{ old('status', $bukuKolaboratif->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="harga_per_bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga per Bab (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="harga_per_bab" 
                                   name="harga_per_bab" 
                                   value="{{ old('harga_per_bab', $bukuKolaboratif->harga_per_bab) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
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
                                   value="{{ old('total_bab', $bukuKolaboratif->total_bab) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   min="1"
                                   max="50"
                                   required>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Saat ini ada {{ $bukuKolaboratif->babBuku->count() }} bab yang sudah dibuat
                            </p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      required>{{ old('deskripsi', $bukuKolaboratif->deskripsi) }}</textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="gambar_sampul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gambar Sampul
                            </label>
                            
                            @if($bukuKolaboratif->gambar_sampul)
                                <div class="mb-3">
                                    <img src="{{ $bukuKolaboratif->gambar_sampul_url }}" 
                                         alt="Sampul saat ini" 
                                         class="w-32 h-40 object-cover rounded-lg">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gambar sampul saat ini</p>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   id="gambar_sampul" 
                                   name="gambar_sampul" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.
                            </p>
                        </div>
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
                            <span id="previewTotalBab" class="text-sm font-medium text-gray-900 dark:text-white ml-2">{{ $bukuKolaboratif->total_bab }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Harga per Bab:</span>
                            <span id="previewHargaPerBab" class="text-sm font-medium text-gray-900 dark:text-white ml-2">{{ $bukuKolaboratif->harga_format }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Nilai Proyek:</span>
                            <span id="previewTotalNilai" class="text-sm font-medium text-blue-600 dark:text-blue-400 ml-2">
                                Rp {{ number_format($bukuKolaboratif->harga_per_bab * $bukuKolaboratif->total_bab, 0, ',', '.') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Bab Sudah Dibuat:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white ml-2">{{ $bukuKolaboratif->babBuku->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Saat Ini -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Saat Ini</h3>
                    
                    @php
                        $babStats = $bukuKolaboratif->getBabStatistics();
                    @endphp
                    <div class="grid grid-cols-3 gap-2 text-center mb-4">
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $babStats['tersedia'] }}</div>
                            <div class="text-xs text-green-600 dark:text-green-400">Tersedia</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $babStats['dipesan'] }}</div>
                            <div class="text-xs text-yellow-600 dark:text-yellow-400">Dipesan</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $babStats['selesai'] }}</div>
                            <div class="text-xs text-blue-600 dark:text-blue-400">Selesai</div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bukuKolaboratif->progress_percentage }}%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Progress Penyelesaian</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('admin.buku-kolaboratif.show', $bukuKolaboratif->id) }}" 
                           class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Warning -->
                @if($bukuKolaboratif->babBuku->whereIn('status', ['dipesan', 'selesai'])->count() > 0)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-2"></i>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Perhatian</h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                    Beberapa bab sudah dipesan atau selesai. Perubahan pada total bab atau harga dapat mempengaruhi pesanan yang ada.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    const totalBab = parseInt(document.getElementById('total_bab').value) || 0;
    const hargaPerBab = parseInt(document.getElementById('harga_per_bab').value) || 0;
    const totalNilai = totalBab * hargaPerBab;
    
    document.getElementById('previewTotalBab').textContent = totalBab;
    document.getElementById('previewHargaPerBab').textContent = 'Rp ' + hargaPerBab.toLocaleString('id-ID');
    document.getElementById('previewTotalNilai').textContent = 'Rp ' + totalNilai.toLocaleString('id-ID');
}

// Event listeners
document.getElementById('total_bab').addEventListener('input', updatePreview);
document.getElementById('harga_per_bab').addEventListener('input', updatePreview);
</script>
@endsection
