@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Laporan Penerbitan</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.penerbitanIndividu.show', $laporan->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('admin.penerbitanIndividu.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Sumber Data -->
            @if($laporan->penerbitanIndividu)
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg border border-blue-200 dark:border-blue-700 mb-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                Laporan ini dibuat dari naskah: {{ $laporan->penerbitanIndividu->nomor_pesanan }}
                            </p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                Pemesan: {{ $laporan->penerbitanIndividu->user->name }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.penerbitanIndividu.update', $laporan->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kode Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku', $laporan->kode_buku) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan kode buku">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contoh: BK202412001</p>
                    </div>

                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ISBN
                        </label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $laporan->isbn) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan ISBN (opsional)">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contoh: 978-602-1234-56-7</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $laporan->judul) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan judul buku">
                    </div>

                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Penulis <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $laporan->penulis) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan nama penulis">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Terbit
                        </label>
                        <input type="date" name="tanggal_terbit" id="tanggal_terbit" 
                            value="{{ old('tanggal_terbit', $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('Y-m-d') : '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika belum diterbitkan</p>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ old('status', $laporan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ old('status', $laporan->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="terbit" {{ old('status', $laporan->status) == 'terbit' ? 'selected' : '' }}>Terbit</option>
                        </select>
                    </div>
                </div>

                                <!-- Informasi Tambahan -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div>
                            <span class="font-medium">Dibuat:</span> {{ $laporan->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir Diupdate:</span> {{ $laporan->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('admin.penerbitanIndividu.show', $laporan->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Status change handler
        document.getElementById('status').addEventListener('change', function() {
            const tanggalTerbitField = document.getElementById('tanggal_terbit');
            const isbnField = document.getElementById('isbn');
            
            if (this.value === 'terbit') {
                // Jika status terbit, set tanggal hari ini jika kosong
                if (!tanggalTerbitField.value) {
                    tanggalTerbitField.value = new Date().toISOString().split('T')[0];
                }
                // Focus ke ISBN jika kosong
                if (!isbnField.value) {
                    setTimeout(() => isbnField.focus(), 100);
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            const tanggalTerbit = document.getElementById('tanggal_terbit').value;
            const isbn = document.getElementById('isbn').value;
            
            // Validasi untuk status terbit
            if (status === 'terbit') {
                if (!tanggalTerbit) {
                    e.preventDefault();
                    alert('Tanggal terbit harus diisi untuk status "Terbit"');
                    document.getElementById('tanggal_terbit').focus();
                    return false;
                }
                
                if (!isbn) {
                    const confirmWithoutISBN = confirm('Buku dengan status "Terbit" biasanya memiliki ISBN. Apakah Anda yakin ingin melanjutkan tanpa ISBN?');
                    if (!confirmWithoutISBN) {
                        e.preventDefault();
                        document.getElementById('isbn').focus();
                        return false;
                    }
                }
            }
        });

        // ISBN format validation
        document.getElementById('isbn').addEventListener('input', function() {
            let value = this.value.replace(/[^0-9X-]/g, '');
            
            // Format ISBN-13 (978-xxx-xxx-xx-x)
            if (value.length > 3 && value.startsWith('978')) {
                value = value.replace(/(\d{3})(\d{1,3})(\d{1,3})(\d{1,2})(\d{0,1})/, function(match, p1, p2, p3, p4, p5) {
                    let formatted = p1;
                    if (p2) formatted += '-' + p2;
                    if (p3) formatted += '-' + p3;
                    if (p4) formatted += '-' + p4;
                    if (p5) formatted += '-' + p5;
                    return formatted;
                });
            }
            // Format ISBN-10 (xxx-xxx-xx-x)
            else if (value.length <= 10) {
                value = value.replace(/(\d{1,3})(\d{1,3})(\d{1,2})(\d{0,1})/, function(match, p1, p2, p3, p4) {
                    let formatted = p1;
                    if (p2) formatted += '-' + p2;
                    if (p3) formatted += '-' + p3;
                    if (p4) formatted += '-' + p4;
                    return formatted;
                });
            }
            
            this.value = value;
        });

        // Real-time kode buku validation
        document.getElementById('kode_buku').addEventListener('input', function() {
            const value = this.value.toUpperCase();
            this.value = value;
            
            // Simple validation pattern
            const pattern = /^BK\d{6,8}$/;
            if (value && !pattern.test(value)) {
                this.setCustomValidity('Format kode buku: BK + tahun + bulan + nomor urut (contoh: BK202412001)');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
@endsection
