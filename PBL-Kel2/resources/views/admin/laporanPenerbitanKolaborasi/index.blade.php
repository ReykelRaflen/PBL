@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Laporan Penerbitan Kolaborasi</h1>

            <!-- Statistik Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['total'] }}</h3>
                            <p class="text-sm opacity-90">Total Buku</p>
                        </div>
                        <i class="fas fa-book text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-yellow-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('proses')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['proses'] }}</h3>
                            <p class="text-sm opacity-90">Dalam Proses</p>
                        </div>
                        <i class="fas fa-cog text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('terbit')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['terbit'] }}</h3>
                            <p class="text-sm opacity-90">Sudah Terbit</p>
                        </div>
                        <i class="fas fa-check-circle text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-orange-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('pending')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['pending'] }}</h3>
                            <p class="text-sm opacity-90">Pending</p>
                        </div>
                        <i class="fas fa-clock text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-purple-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['dengan_isbn'] }}</h3>
                            <p class="text-sm opacity-90">Dengan ISBN</p>
                        </div>
                        <i class="fas fa-barcode text-2xl opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('admin.laporanPenerbitanKolaborasi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                placeholder="Kode buku, judul, ISBN...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                                <option value="">Semua Status</option>
                                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>
                                    Dalam Proses
                                </option>
                                <option value="terbit" {{ request('status') == 'terbit' ? 'selected' : '' }}>
                                    Sudah Terbit
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.laporanPenerbitanKolaborasi.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4 dark:bg-green-800 dark:border-green-600 dark:text-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4 dark:bg-red-800 dark:border-red-600 dark:text-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

                       <div class="overflow-x-auto shadow-sm border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ISBN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Terbit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($laporans as $laporan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $laporan->kode_buku }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        ID: {{ $laporan->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $laporan->judul }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $laporan->bukuKolaboratif->deskripsi ? Str::limit($laporan->bukuKolaboratif->deskripsi, 50) : 'Tidak ada deskripsi' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($laporan->isbn)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                <i class="fas fa-barcode mr-1"></i>
                                                {{ $laporan->isbn }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Belum ada ISBN</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-700 dark:text-gray-300">
                                                    {{ $laporan->total_bab_disetujui }}/{{ $laporan->total_bab_buku }} Bab
                                                </span>
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    {{ $laporan->persentase_selesai }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $laporan->persentase_selesai }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($laporan->tanggal_terbit)
                                            {{ $laporan->tanggal_terbit->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Belum ditetapkan</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }}">
                                        @switch($laporan->status)
                                            @case('proses')
                                                <i class="fas fa-cog mr-1"></i>
                                                @break
                                            @case('terbit')
                                                <i class="fas fa-check-circle mr-1"></i>
                                                @break
                                            @case('pending')
                                                <i class="fas fa-clock mr-1"></i>
                                                @break
                                            @case('draft')
                                                <i class="fas fa-file-alt mr-1"></i>
                                                @break
                                        @endswitch
                                        {{ $laporan->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Tombol Detail -->
                                        <a href="{{ route('admin.laporanPenerbitanKolaborasi.show', $laporan->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.laporanPenerbitanKolaborasi.edit', $laporan->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                            title="Edit Laporan">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        <!-- Tombol Download All -->
                                        @if($laporan->total_bab_disetujui > 0)
                                            <a href="{{ route('admin.laporanPenerbitanKolaborasi.downloadAll', $laporan->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-purple-500 text-white rounded-md hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Download Semua Naskah">
                                                <i class="fas fa-download text-xs"></i>
                                            </a>
                                        @endif

                                        <!-- Tombol Delete -->
                                        <form action="{{ route('admin.laporanPenerbitanKolaborasi.destroy', $laporan->id) }}" method="POST"
                                            class="inline" onsubmit="return confirmDelete('{{ $laporan->kode_buku }}', '{{ $laporan->judul_buku }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Hapus Laporan">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-book text-4xl mb-4 opacity-50"></i>
                                        <p class="text-lg font-medium mb-2">Belum ada laporan penerbitan</p>
                                        <p class="text-sm">Laporan akan muncul otomatis ketika naskah kolaborasi disetujui</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($laporans->hasPages())
                <div class="mt-6">
                    {{ $laporans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterByStatus(status) {
            const url = new URL(window.location);
            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        function confirmDelete(kodeBuku, judulBuku) {
            return confirm(`Yakin ingin menghapus laporan "${kodeBuku} - ${judulBuku}"?\n\nPerhatian: Tindakan ini tidak dapat dibatalkan.`);
        }

        // Auto refresh setiap 5 menit untuk update progress
        setInterval(function() {
            if (!document.hidden) {
                location.reload();
            }
        }, 300000);
    </script>
@endsection
