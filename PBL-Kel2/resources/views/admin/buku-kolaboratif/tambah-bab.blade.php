@extends('admin.layouts.app')

@section('title', 'Tambah Bab - ' . $bukuKolaboratif->judul)

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Bab Baru</h1>
                <p class="text-gray-600 mt-1">
                    Buku: {{ $bukuKolaboratif->judul }}
                </p>
            </div>
            <a href="{{ route('admin.buku-kolaboratif.show', $bukuKolaboratif->id) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.buku-kolaboratif.store-bab', $bukuKolaboratif->id) }}" 
              method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Bab -->
                <div class="md:col-span-2">
                    <label for="judul_bab" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Bab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="judul_bab" 
                           name="judul_bab" 
                           value="{{ old('judul_bab') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul_bab') border-red-500 @enderror"
                           required>
                    @error('judul_bab')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" 
                              name="deskripsi" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi bab (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga (Rp)
                    </label>
                    <input type="number" 
                           id="harga" 
                           name="harga" 
                           value="{{ old('harga', $bukuKolaboratif->harga_per_bab) }}"
                           min="0"
                           step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror">
                    <p class="text-sm text-gray-500 mt-1">
                        Kosongkan untuk menggunakan harga default (Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }})
                    </p>
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tingkat Kesulitan -->
                <div>
                    <label for="tingkat_kesulitan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Kesulitan <span class="text-red-500">*</span>
                    </label>
                    <select id="tingkat_kesulitan" 
                            name="tingkat_kesulitan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tingkat_kesulitan') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Tingkat Kesulitan</option>
                                             <option value="mudah" {{ old('tingkat_kesulitan') == 'mudah' ? 'selected' : '' }}>
                            Mudah
                        </option>
                        <option value="sedang" {{ old('tingkat_kesulitan') == 'sedang' ? 'selected' : '' }}>
                            Sedang
                        </option>
                        <option value="sulit" {{ old('tingkat_kesulitan') == 'sulit' ? 'selected' : '' }}>
                            Sulit
                        </option>
                    </select>
                    @error('tingkat_kesulitan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Informasi Bab:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Bab akan dibuat dengan status "Tersedia"</li>
                            <li>Nomor bab akan ditentukan otomatis berdasarkan urutan</li>
                            <li>Total bab saat ini: {{ $bukuKolaboratif->babBuku->count() }} dari {{ $bukuKolaboratif->total_bab }} bab</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('admin.buku-kolaboratif.show', $bukuKolaboratif->id) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Tambah Bab
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Format currency input
    document.getElementById('harga').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
</script>
@endpush
