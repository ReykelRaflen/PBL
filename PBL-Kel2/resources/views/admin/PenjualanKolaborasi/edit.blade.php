@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Edit Laporan Penjualan</h1>
                <a href="{{ route('penjualanKolaborasi.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-700 dark:text-white">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('penjualanKolaborasi.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Buku
                            </label>
                            <input type="text" name="judul" id="judul" 
                                   value="{{ old('judul', $laporan->judul) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                   required>
                        </div>

                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Penulis
                            </label>
                            <input type="text" name="penulis" id="penulis" 
                                   value="{{ old('penulis', $laporan->penulis) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                   required>
                        </div>

                        <div>
                            <label for="bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bab
                            </label>
                            <input type="text" name="bab" id="bab" 
                                   value="{{ old('bab', $laporan->bab) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                   required>
                        </div>

                                                <div>
                            <label for="jumlah_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah Pembayaran
                            </label>
                            <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" 
                                   value="{{ old('jumlah_pembayaran', $laporan->jumlah_pembayaran) }}"
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                   required>
                        </div>

                        <div>
                            <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Pembayaran
                            </label>
                            <select name="status_pembayaran" id="status_pembayaran"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    required>
                                <option value="menunggu_verifikasi" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'menunggu_verifikasi' ? 'selected' : '' }}>
                                    Menunggu Verifikasi
                                </option>
                                <option value="sukses" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'sukses' ? 'selected' : '' }}>
                                    Sukses
                                </option>
                                <option value="tidak sesuai" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'tidak sesuai' ? 'selected' : '' }}>
                                    Tidak Sesuai
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <label for="pesanan_kolaborasi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Pilih Pesanan Buku
                            </label>
                            <select name="pesanan_kolaborasi_id" id="pesanan_kolaborasi_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Tidak terkait dengan pesanan</option>
                                @foreach($pesananBuku as $pesanan)
                                    <option value="{{ $pesanan->id }}" 
                                            {{ old('pesanan_kolaborasi_id', $laporan->pesanan_kolaborasi_id) == $pesanan->id ? 'selected' : '' }}>
                                        {{ $pesanan->nomor_pesanan }} - {{ $pesanan->bukuKolaboratif->judul ?? 'Judul tidak ditemukan' }} 
                                        ({{ $pesanan->user->name ?? 'Penulis tidak ditemukan' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-gray-500 dark:text-gray-400">
                                Pilih pesanan yang terkait dengan laporan ini (opsional)
                            </small>
                        </div>

                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bukti Pembayaran
                            </label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            
                            @if($laporan->bukti_pembayaran)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">File saat ini:</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm text-blue-600">{{ $laporan->bukti_pembayaran }}</span>
                                        <a href="{{ route('penjualanKolaborasi.download-bukti', $laporan->id) }}" 
                                           class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <small class="text-gray-500 dark:text-gray-400">
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.
                            </small>
                        </div>

                        <div>
                            <label for="catatan_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Admin
                            </label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="Catatan tambahan dari admin...">{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('penjualanKolaborasi.index') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-fill form berdasarkan pesanan yang dipilih
        document.getElementById('pesanan_kolaborasi_id').addEventListener('change', function() {
            const pesananId = this.value;
            
            if (pesananId) {
                // Ambil data pesanan via AJAX
                fetch(`/admin/dashboard/laporan-penjualan-kolaborasi/get-pesanan/${pesananId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('judul').value = data.pesanan.buku_kolaboratif.judul;
                            document.getElementById('penulis').value = data.pesanan.user.name;
                            document.getElementById('bab').value = `Bab ${data.pesanan.bab_buku.nomor_bab} - ${data.pesanan.bab_buku.judul_bab}`;
                            document.getElementById('jumlah_pembayaran').value = data.pesanan.jumlah_bayar;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // Preview gambar bukti pembayaran
        document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                    this.value = '';
                    return;
                }

                // Preview gambar (opsional)
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Buat preview jika diperlukan
                    console.log('File valid dan siap diupload');
                };
                reader.readAsDataURL(file);
            }
        });

        // Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const jumlahPembayaran = document.getElementById('jumlah_pembayaran').value;
            
            if (parseFloat(jumlahPembayaran) <= 0) {
                e.preventDefault();
                alert('Jumlah pembayaran harus lebih dari 0');
                return false;
            }

            // Konfirmasi sebelum submit
            if (!confirm('Yakin ingin mengupdate laporan ini?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
