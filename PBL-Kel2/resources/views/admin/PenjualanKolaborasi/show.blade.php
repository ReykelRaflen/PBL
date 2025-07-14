@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Laporan Penjualan Kolaborasi</h1>
                <a href="{{ route('penjualanKolaborasi.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informasi Pembayaran -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nomor
                                    Invoice</label>
                                <p class="text-lg font-semibold text-blue-600">
                                    {{ $laporan->nomor_invoice }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Jumlah
                                    Pembayaran</label>
                                <p class="text-lg font-semibold text-green-600">
                                    Rp {{ number_format($laporan->jumlah_pembayaran, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                    Pembayaran</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $laporan->status_pembayaran === 'sukses' ? 'bg-green-500' : 
                                       ($laporan->status_pembayaran === 'menunggu_verifikasi' ? 'bg-yellow-500' : 'bg-red-500') }} text-white">
                                    {{ ucwords(str_replace('_', ' ', $laporan->status_pembayaran)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Buku -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Informasi Buku & Bab</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Judul Buku</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $laporan->judul }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nama
                                    Penulis</label>
                                <p class="text-gray-900 dark:text-white">{{ $laporan->penulis }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Bab yang
                                    Dipesan</label>
                                <p class="text-gray-900 dark:text-white">{{ $laporan->bab }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pesanan (jika ada) -->
                    @if($laporan->pesananKolaborasi)
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-semibold mb-4">Detail Pesanan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nomor
                                        Pesanan</label>
                                    <p class="text-gray-900 dark:text-white font-mono">
                                        {{ $laporan->pesananKolaborasi->nomor_pesanan ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Email
                                        Penulis</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $laporan->pesananKolaborasi->user->email ?? '-' }}
                                    </p>
                                </div>
                                @if($laporan->pesananKolaborasi->babBuku)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tingkat
                                            Kesulitan</label>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($laporan->pesananKolaborasi->babBuku->tingkat_kesulitan ?? '-') }}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Harga Bab</label>
                                        <p class="text-gray-900 dark:text-white">Rp
                                            {{ number_format($laporan->jumlah_pembayaran ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status
                                        Penulisan</label>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ ($laporan->pesananKolaborasi->status_penulisan ?? '') === 'dapat_mulai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucwords(str_replace('_', ' ', $laporan->pesananKolaborasi->status_penulisan ?? 'Belum ditentukan')) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                        Pesanan</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $laporan->pesananKolaborasi->created_at ? $laporan->pesananKolaborasi->created_at->format('d F Y H:i') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Catatan Admin -->
                    @if($laporan->catatan_admin)
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Catatan Admin</h4>
                            <p class="text-yellow-700 dark:text-yellow-300">{{ $laporan->catatan_admin }}</p>
                            @if($laporan->tanggal_verifikasi)
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">
                                    Diverifikasi pada: {{ \Carbon\Carbon::parse($laporan->tanggal_verifikasi)->format('d F Y H:i') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Bukti Pembayaran -->
                    @if($laporan->bukti_pembayaran)
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Bukti Pembayaran</h3>
                            <div class="text-center">
                                @php
                                    $extension = pathinfo($laporan->bukti_pembayaran, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    $imagePath = Str::startsWith($laporan->bukti_pembayaran, 'bukti-pembayaran/') 
                                        ? $laporan->bukti_pembayaran 
                                        : 'bukti/' . $laporan->bukti_pembayaran;
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Bukti Pembayaran"
                                        class="w-full max-w-sm mx-auto rounded-lg shadow-md mb-4 cursor-pointer"
                                        onclick="openImageModal('{{ asset('storage/' . $imagePath) }}')">
                                @else
                                    <div class="flex flex-col items-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">File: {{ basename($laporan->bukti_pembayaran) }}</p>
                                    </div>
                                @endif
                                
                                <a href="{{ route('penjualanKolaborasi.download-bukti', $laporan->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Aksi Verifikasi -->
                    @if($laporan->status_pembayaran === 'menunggu_verifikasi')
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Verifikasi Pembayaran</h3>
                            
                            <!-- Setujui -->
                            <form action="{{ route('penjualanKolaborasi.accept', $laporan->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menyetujui pembayaran ini?')" class="mb-3">
                                @csrf
                                <input type="hidden" name="status" value="sukses">
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Pembayaran
                                </button>
                            </form>

                                                       <!-- Tolak -->
                            <button type="button" onclick="openRejectModal()"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Pembayaran
                            </button>
                        </div>
                    @endif

                    <!-- Aksi Lainnya -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Aksi Lainnya</h3>
                        <div class="space-y-3">
                            <a href="{{ route('penjualanKolaborasi.edit', $laporan->id) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Laporan
                            </a>
                            <form action="{{ route('penjualanKolaborasi.destroy', $laporan->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus laporan ini? Data tidak dapat dikembalikan!')"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Hapus Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Pembayaran -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tolak Pembayaran</h3>
                <form action="{{ route('penjualanKolaborasi.reject', $laporan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="tidak_sesuai">
                    <div class="mb-4">
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan alasan penolakan pembayaran..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Gambar -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full rounded-lg">
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="closeAlert('successAlert')" class="ml-2 text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
                <button onclick="closeAlert('errorAlert')" class="ml-2 text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            // Reset form
            document.querySelector('#rejectModal form').reset();
        }

        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function closeAlert(alertId) {
            document.getElementById(alertId).style.display = 'none';
        }

        // Close modals when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeRejectModal();
                closeImageModal();
            }
        });

        // Auto close alerts after 5 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (successAlert) {
                successAlert.style.display = 'none';
            }
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000);

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const rejectForm = document.querySelector('#rejectModal form');
            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    const textarea = document.getElementById('catatan_admin');
                    if (textarea.value.trim().length < 10) {
                        e.preventDefault();
                        alert('Alasan penolakan harus minimal 10 karakter');
                        textarea.focus();
                        return false;
                    }
                });
            }
        });
    </script>
@endsection
