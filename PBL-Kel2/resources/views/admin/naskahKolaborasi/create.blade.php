@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Tambah Naskah Kolaborasi</h1>
                <a href="{{ route('naskahKolaborasi.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-700 dark:text-white">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('naskahKolaborasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penulis *
                            </label>
                            <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Penulis</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="buku_kolaboratif_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Buku Kolaboratif *
                            </label>
                            <select name="buku_kolaboratif_id" id="buku_kolaboratif_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Buku</option>
                                @foreach($bukuKolaboratif as $buku)
                                    <option value="{{ $buku->id }}" {{ old('buku_kolaboratif_id') == $buku->id ? 'selected' : '' }}>
                                        {{ $buku->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_kolaboratif_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bab_buku_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bab Buku *
                            </label>
                            <select name="bab_buku_id" id="bab_buku_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Bab</option>
                            </select>
                            @error('bab_buku_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nomor_pesanan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor Pesanan *
                            </label>
                            <input type="text" name="nomor_pesanan" id="nomor_pesanan" value="{{ old('nomor_pesanan') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Masukkan nomor pesanan">
                            @error('nomor_pesanan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status_penulisan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Penulisan *
                            </label>
                            <select name="status_penulisan" id="status_penulisan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Pilih Status</option>
                                <option value="belum_mulai" {{ old('status_penulisan') == 'belum_mulai' ? 'selected' : '' }}>
                                    Belum Mulai</option>
                                <option value="dapat_mulai" {{ old('status_penulisan') == 'dapat_mulai' ? 'selected' : '' }}>
                                    Dapat Mulai</option>
                                <option value="sedang_ditulis" {{ old('status_penulisan') == 'sedang_ditulis' ? 'selected' : '' }}>Sedang Ditulis</option>
                                <option value="sudah_kirim" {{ old('status_penulisan') == 'sudah_kirim' ? 'selected' : '' }}>
                                    Sudah Dikirim</option>
                                <option value="revisi" {{ old('status_penulisan') == 'revisi' ? 'selected' : '' }}>Perlu
                                    Revisi</option>
                                <option value="disetujui" {{ old('status_penulisan') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="selesai" {{ old('status_penulisan') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="ditolak" {{ old('status_penulisan') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                            @error('status_penulisan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_deadline"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Deadline
                            </label>
                            <input type="date" name="tanggal_deadline" id="tanggal_deadline"
                                value="{{ old('tanggal_deadline') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            @error('tanggal_deadline')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <label for="judul_naskah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Naskah *
                            </label>
                            <input type="text" name="judul_naskah" id="judul_naskah" value="{{ old('judul_naskah') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Masukkan judul naskah">
                            @error('judul_naskah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi_naskah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi Naskah
                            </label>
                            <textarea name="deskripsi_naskah" id="deskripsi_naskah" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Deskripsi singkat tentang naskah">{{ old('deskripsi_naskah') }}</textarea>
                            @error('deskripsi_naskah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="file_naskah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Naskah
                            </label>
                            <input type="file" name="file_naskah" id="file_naskah" accept=".pdf,.doc,.docx"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <small class="text-gray-500">Format: PDF, DOC, DOCX. Maksimal 10MB</small>
                            @error('file_naskah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_kata"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah Kata
                            </label>
                            <input type="number" name="jumlah_kata" id="jumlah_kata" value="{{ old('jumlah_kata') }}"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Jumlah kata dalam naskah">
                            @error('jumlah_kata')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan_penulis"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Penulis
                            </label>
                            <textarea name="catatan_penulis" id="catatan_penulis" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Catatan dari penulis">{{ old('catatan_penulis') }}</textarea>
                            @error('catatan_penulis')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Admin
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Catatan dari admin">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 pt-6 border-t">
                    <a href="{{ route('naskahKolaborasi.index') }}"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-3">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

       <script>
// Load bab berdasarkan buku yang dipilih
document.getElementById('buku_kolaboratif_id').addEventListener('change', function () {
    const bukuId = this.value;
    const babSelect = document.getElementById('bab_buku_id');
    
    console.log('Buku ID selected:', bukuId);

    // Clear existing options
    babSelect.innerHTML = '<option value="">Loading...</option>';

    if (bukuId) {
        const url = `/admin/api/buku-kolaboratif/${bukuId}/bab`;
        console.log('Fetching URL:', url);
        
        // Fetch bab data
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response OK:', response.ok);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Clear loading option
            babSelect.innerHTML = '<option value="">Pilih Bab</option>';
            
            if (data.error) {
                console.error('API Error:', data.error);
                babSelect.innerHTML = '<option value="">Error: ' + data.error + '</option>';
                return;
            }
            
            if (!Array.isArray(data) || data.length === 0) {
                console.log('No bab data found');
                babSelect.innerHTML = '<option value="">Tidak ada bab tersedia</option>';
            } else {
                console.log('Adding bab options:', data.length);
                data.forEach(bab => {
                    const option = document.createElement('option');
                    option.value = bab.id;
                    option.textContent = `Bab ${bab.nomor_bab}: ${bab.judul_bab}`;
                    
                    // Disable jika status tidak tersedia
                    if (bab.status !== 'tersedia') {
                        option.disabled = true;
                        option.textContent += ` (${bab.status_text || bab.status})`;
                    }
                    
                    babSelect.appendChild(option);
                    console.log('Added option:', option.textContent);
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            babSelect.innerHTML = '<option value="">Error loading bab: ' + error.message + '</option>';
        });
    } else {
        babSelect.innerHTML = '<option value="">Pilih Bab</option>';
    }
});

// Generate nomor pesanan otomatis jika kosong
document.addEventListener('DOMContentLoaded', function() {
    const nomorPesananInput = document.getElementById('nomor_pesanan');
    if (nomorPesananInput && !nomorPesananInput.value) {
        const today = new Date();
        const dateStr = today.getFullYear() + 
                       String(today.getMonth() + 1).padStart(2, '0') + 
                       String(today.getDate()).padStart(2, '0');
        const randomNum = Math.floor(Math.random() * 9999) + 1;
        const nomor = `NK-${dateStr}-${String(randomNum).padStart(4, '0')}`;
        nomorPesananInput.value = nomor;
    }
});
</script>

@endsection
