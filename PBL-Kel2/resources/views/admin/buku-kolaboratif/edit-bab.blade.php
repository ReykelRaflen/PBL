@extends('admin.layouts.app')

@section('title', 'Edit Bab - ' . $babBuku->judul_bab)

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Bab</h1>
                <p class="text-gray-600 mt-1">
                    Buku: {{ $bukuKolaboratif->judul }} - Bab {{ $babBuku->nomor_bab }}
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
        <form action="{{ route('admin.buku-kolaboratif.update-bab', [$bukuKolaboratif->id, $babBuku->id]) }}" 
              method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Bab -->
                <div class="md:col-span-2">
                    <label for="judul_bab" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Bab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="judul_bab" 
                           name="judul_bab" 
                           value="{{ old('judul_bab', $babBuku->judul_bab) }}"
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
                              placeholder="Deskripsi bab (opsional)">{{ old('deskripsi', $babBuku->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="harga" 
                           name="harga" 
                           value="{{ old('harga', $babBuku->harga) }}"
                           min="0"
                           step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror"
                           required>
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
                        <option value="mudah" {{ old('tingkat_kesulitan', $babBuku->tingkat_kesulitan) == 'mudah' ? 'selected' : '' }}>
                            Mudah
                        </option>
                        <option value="sedang" {{ old('tingkat_kesulitan', $babBuku->tingkat_kesulitan) == 'sedang' ? 'selected' : '' }}>
                            Sedang
                        </option>
                        <option value="sulit" {{ old('tingkat_kesulitan', $babBuku->tingkat_kesulitan) == 'sulit' ? 'selected' : '' }}>
                            Sulit
                        </option>
                    </select>
                    @error('tingkat_kesulitan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Status</option>
                        <option value="tersedia" {{ old('status', $babBuku->status) == 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="dipesan" {{ old('status', $babBuku->status) == 'dipesan' ? 'selected' : '' }}>
                            Dipesan
                        </option>
                        <option value="selesai" {{ old('status', $babBuku->status) == 'selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Bab (Read Only) -->
                <div>
                    <label for="nomor_bab" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Bab
                    </label>
                    <input type="text" 
                           id="nomor_bab" 
                           value="Bab {{ $babBuku->nomor_bab }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"
                           readonly>
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
                    Update Bab
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
