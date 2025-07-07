@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Laporan Penerbitan</h1>
                <a href="{{ route('admin.laporanPenerbitanKolaborasi.show', $laporan->id) }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            @if(session('error'))
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4 dark:bg-red-800 dark:border-red-600 dark:text-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.laporanPenerbitanKolaborasi.update', $laporan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                <i class="fas fa-book mr-2"></i>Informasi Dasar
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="kode_buku"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kode Buku *
                                    </label>
                                    <input type="text" name="kode_buku" id="kode_buku"
                                        value="{{ old('kode_buku', $laporan->kode_buku) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                        placeholder="Masukkan kode buku">
                                    @error('kode_buku')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="judul"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Judul Buku *
                                    </label>
                                    <input type="text" name="judul" id="judul"
                                        value="{{ old('judul', $laporan->judul) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                        placeholder="Masukkan judul buku">
                                    @error('judul')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="isbn"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ISBN
                                    </label>
                                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $laporan->isbn) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                        placeholder="Masukkan ISBN (opsional)">
                                    @error('isbn')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                <i class="fas fa-calendar mr-2"></i>Informasi Penerbitan
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="tanggal_terbit"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal Terbit
                                    </label>
                                    <input type="date" name="tanggal_terbit" id="tanggal_terbit"
                                        value="{{ old('tanggal_terbit', $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('Y-m-d') : '') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                                    @error('tanggal_terbit')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status *
                                    </label>
                                    <select name="status" id="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                                        <option value="">Pilih Status</option>
                                        <option value="draft" {{ old('status', $laporan->status) == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                        <option value="proses" {{ old('status', $laporan->status) == 'proses' ? 'selected' : '' }}>
                                            Dalam Proses
                                        </option>
                                        <option value="pending" {{ old('status', $laporan->status) == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="terbit" {{ old('status', $laporan->status) == 'terbit' ? 'selected' : '' }}>
                                            Sudah Terbit
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                <i class="fas fa-chart-pie mr-2"></i>Progress Buku
                            </h3>

                            <div class="text-center mb-4">
                                <div class="relative inline-flex items-center justify-center w-24 h-24">
                                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-gray-300 dark:text-gray-600" stroke="currentColor"
                                            stroke-width="3" fill="none"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="text-blue-600" stroke="currentColor" stroke-width="3" fill="none"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ $laporan->persentase_selesai }}, 100"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span
                                            class="text-lg font-bold text-gray-900 dark:text-white">{{ $laporan->persentase_selesai }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="bg-white dark:bg-gray-600 p-3 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $laporan->total_bab_disetujui }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Bab Selesai</div>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-3 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-400">{{ $laporan->total_bab_buku }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Bab</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                <i class="fas fa-sticky-note mr-2"></i>Catatan
                            </h3>

                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catatan Tambahan
                                </label>
                                <textarea name="catatan" id="catatan" rows="6"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                    placeholder="Masukkan catatan tambahan untuk laporan ini...">{{ old('catatan', $laporan->catatan) }}</textarea>
                                @error('catatan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>Informasi Buku Kolaboratif
                            </h4>
                            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <p><strong>Judul Asli:</strong> {{ $laporan->bukuKolaboratif->judul }}</p>
                                @if($laporan->bukuKolaboratif->deskripsi)
                                    <p><strong>Deskripsi:</strong> {{ Str::limit($laporan->bukuKolaboratif->deskripsi, 100) }}
                                    </p>
                                @endif
                                <p><strong>Total Bab:</strong> {{ $laporan->total_bab_buku }} bab</p>
                                <p><strong>Bab Selesai:</strong> {{ $laporan->total_bab_disetujui }} bab</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.laporanPenerbitanKolaborasi.show', $laporan->id) }}"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-save mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-generate kode buku jika kosong
        document.getElementById('judul').addEventListener('input', function () {
            const kodeBuku = document.getElementById('kode_buku');
            if (!kodeBuku.value) {
                const judul = this.value;
                const words = judul.split(' ').slice(0, 3); // Ambil 3 kata pertama
                const kode = words.map(word => word.substring(0, 3).toUpperCase()).join('');
                const timestamp = new Date().getFullYear().toString().slice(-2);
                kodeBuku.value = `BK-${kode}-${timestamp}`;
            }
        });

        // Validasi tanggal terbit
        document.getElementById('tanggal_terbit').addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            const status = document.getElementById('status').value;

            if (status === 'terbit' && selectedDate > today) {
                alert('Tanggal terbit tidak boleh di masa depan jika status sudah terbit');
                this.value = '';
            }
        });

        // Update status berdasarkan tanggal terbit
        document.getElementById('status').addEventListener('change', function () {
            const tanggalTerbit = document.getElementById('tanggal_terbit');
            const today = new Date().toISOString().split('T')[0];

            if (this.value === 'terbit' && !tanggalTerbit.value) {
                tanggalTerbit.value = today;
            }
        });

        // Format ISBN input
        document.getElementById('isbn').addEventListener('input', function () {
            let value = this.value.replace(/[^0-9X]/g, '');
            if (value.length === 10 || value.length === 13) {
                // Format ISBN-10 atau ISBN-13
                if (value.length === 10) {
                    value = value.replace(/(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4');
                } else {
                    value = value.replace(/(\d{3})(\d{1})(\d{3})(\d{5})(\d{1})/, '$1-$2-$3-$4-$5');
                }
            }
            this.value = value;
        });

        // Konfirmasi sebelum submit jika mengubah status ke terbit
        document.querySelector('form').addEventListener('submit', function (e) {
            const status = document.getElementById('status').value;
            const originalStatus = '{{ $laporan->status }}';

            if (status === 'terbit' && originalStatus !== 'terbit') {
                if (!confirm('Yakin ingin mengubah status menjadi "Sudah Terbit"? Status ini menandakan buku sudah dipublikasikan.')) {
                    e.preventDefault();
                }
            }
        });
    </script>
@endsection