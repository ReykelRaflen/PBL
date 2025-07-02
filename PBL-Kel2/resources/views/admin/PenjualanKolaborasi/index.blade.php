@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Verifikasi Pembayaran Buku Kolaboratif</h1>

            <!-- Statistik Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['total'] }}</h3>
                            <p class="text-sm opacity-90">Total Pembayaran</p>
                        </div>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-yellow-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['menunggu'] }}</h3>
                            <p class="text-sm opacity-90">Menunggu Verifikasi</p>
                        </div>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['sukses'] }}</h3>
                            <p class="text-sm opacity-90">Disetujui</p>
                        </div>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-red-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['ditolak'] }}</h3>
                            <p class="text-sm opacity-90">Ditolak</p>
                        </div>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-purple-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}
                            </h3>
                            <p class="text-sm opacity-90">Total Nilai</p>
                        </div>
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                            </path>
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('penjualanKolaborasi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Judul, penulis, invoice...">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                                    Menunggu Verifikasi
                                </option>
                                <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="tidak sesuai" {{ request('status') == 'tidak sesuai' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('penjualanKolaborasi.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Reset
                            </a>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('penjualanKolaborasi.create') }}"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Manual
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 dark:bg-green-700 dark:text-white">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-700 dark:text-white">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border dark:text-white">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-3 text-left">Invoice</th>
                            <th class="p-3 text-left">Buku & Bab</th>
                            <th class="p-3 text-left">Penulis</th>
                            <th class="p-3 text-left">Jumlah</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr class="border-t dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="p-3">
                                    <div>
                                        <strong class="text-blue-600">{{ $item->nomor_invoice ?? $item->invoice }}</strong>
                                        @if($item->pesananBuku)
                                            <br><small class="text-gray-500">ID: {{ $item->pesananBuku->nomor_pesanan }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        <strong>{{ $item->judul }}</strong>
                                        <br><small class="text-gray-500">{{ $item->bab }}</small>
                                        @if($item->pesananBuku && $item->pesananBuku->babBuku)
                                            <br><span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($item->pesananBuku->babBuku->tingkat_kesulitan) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        {{ $item->penulis }}
                                        @if($item->pesananBuku && $item->pesananBuku->pengguna)
                                            <br><small class="text-gray-500">{{ $item->pesananBuku->pengguna->email }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <strong class="text-green-600">
                                        {{ $item->jumlah_pembayaran_formatted ?? 'Rp ' . number_format($item->jumlah_pembayaran ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="p-3">
                                    <div>
                                        {{ $item->tanggal->format('d/m/Y') }}
                                        @if($item->tanggal_verifikasi)
                                            <br><small class="text-gray-500">Verifikasi:
                                                {{ $item->tanggal_verifikasi->format('d/m/Y H:i') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status_badge }}">
                                        {{ $item->status_text }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Lihat Detail -->
                                        <a href="{{ route('penjualanKolaborasi.show', $item->id) }}"
                                            class="inline-flex items-center justify-center p-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Tombol Download Bukti -->
                                        @if($item->bukti_pembayaran)
                                            <a href="{{ route('penjualanKolaborasi.download-bukti', $item->id) }}"
                                                class="inline-flex items-center justify-center p-2 bg-purple-500 text-white rounded hover:bg-purple-600"
                                                title="Download Bukti">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Tombol Verifikasi (hanya untuk status menunggu) -->
                                        @if($item->status_pembayaran === 'menunggu_verifikasi')
                                            <div class="flex gap-1">
                                                <!-- Setujui -->
                                                <form action="{{ route('penjualanKolaborasi.verifikasi', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menyetujui pembayaran ini?')"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="sukses">
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center p-2 bg-green-500 text-white rounded hover:bg-green-600"
                                                        title="Setujui Pembayaran">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <!-- Tolak -->
                                                <button type="button"
                                                    class="inline-flex items-center justify-center p-2 bg-red-500 text-white rounded hover:bg-red-600"
                                                    title="Tolak Pembayaran" onclick="openRejectModal({{ $item->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('penjualanKolaborasi.edit', $item->id) }}"
                                            class="inline-flex items-center justify-center p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('penjualanKolaborasi.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus laporan ini?')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center p-2 bg-red-500 text-white rounded hover:bg-red-600"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-50" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p>Belum ada laporan pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($laporan->hasPages())
                <div class="mt-6">
                    {{ $laporan->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tolak Pembayaran -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tolak Pembayaran</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="tidak sesuai">

                    <div class="mb-4">
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan
                        </label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Jelaskan alasan penolakan pembayaran..." required></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/penjualan-kolaborasi/${id}/verifikasi`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('catatan_admin').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection